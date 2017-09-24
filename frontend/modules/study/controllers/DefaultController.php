<?php

namespace frontend\modules\study\controllers;

use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\course\searchs\CourseListSearch;
use common\models\SearchLog;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `study` module
 */
class DefaultController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'view'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Renders  the index view for the module
     * @return string
     */
    public function actionIndex() {
        $params = Yii::$app->request->queryParams;
        $search = new CourseListSearch();
        $results = $search->search($params);
        $filters = []; //$this->getFilterSearch($params);

        return $this->render('index', array_merge($results, array_merge(['filters' => $filters], ['category' => CourseCategory::findOne($results['filter']['parent_cat_id'])])
        ));
    }

    /**
     * Renders View the index view for the module
     * @return string
     */
    public function actionView() {
        $params = Yii::$app->request->queryParams;
        $model = $this->findModel(ArrayHelper::getValue($params, 'id'));
        $isBuy = true; //Buyunit::checkAuthorize($model->cat_id);
        if ($isBuy) {
            $model->play_count += 1;
            $model->save(false, ['play_count']);

            return $this->render('view', [
                        'model' => $model,
                        'filter' => $params,
                        'attrs' => $this->getCourseAttr($model->id)
            ]);
        } else {
            $this->layout = '@frontend/modules/study/views/layouts/_main';
            return $this->render('/layouts/_error');
        }
    }

    /**
     * Renders Search the index view for the module
     * @return string
     */
    public function actionSearch() {
        $params = Yii::$app->request->queryParams;
        $this->saveSearchLog($params);

        return $this->redirect(['search-result', 'keyword' => ArrayHelper::getValue($params, 'keyword')]);
    }

    /**
     * Renders SearchResult the index view for the module
     * @return string
     */
    public function actionSearchResult() {
        $search = new CourseListSearch();
        $params = Yii::$app->request->queryParams;
        $result = $search->searchKeywords($params);

        if (isset($result['result']['courses']) && !empty($result['result']['courses']))
            return $this->render('_search', $result);
        else {
            $this->layout = '@frontend/modules/study/views/layouts/_main';
            return $this->render('/layouts/_prompt', $result);
        }
    }

    /**
     * Finds the WorksystemTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * 获取过滤筛选的结果
     * @param array $params                 传参数
     * @return array
     */
    public function getFilterSearch($params) {
        $cat_id = ArrayHelper::getValue($params, 'cat_id');
        $attrs = ArrayHelper::getValue($params, 'attrs', []);
        $catItems = [];         //学科
        $attrItems = [];        //属性
        //学科
        if ($cat_id != null) {
            $courseCats = (new Query())
                    ->select(['CourseCat.name AS filter_value'])
                    ->from(['CourseCat' => CourseCategory::tableName()])
                    ->where(['id' => $cat_id])
                    ->one();
            $paramsCopy = $params;
            unset($paramsCopy['cat_id']);
            $catItems = [Yii::t('app', 'Cat') => array_merge($courseCats, ['url' => Url::to(array_merge(['index'], $paramsCopy))])];
        }

        //属性
        if ($attrs != null) {
            $courseAttrs = (new Query())
                    ->select(['id', 'name'])
                    ->from(CourseAttribute::tableName());

            foreach ($attrs as $attr_arr) {
                $courseAttrs->orFilterWhere([
                    'id' => explode('_', $attr_arr['attr_id'])[0], //拆分属性id
                ]);
            }

            $courseAttrsItems = ArrayHelper::map($courseAttrs->orderBy('order')->all(), 'id', 'name');
            sort($attrs);                                                  //以升序对数组排序
            foreach ($attrs as $key => $attr) {
                $attr['attr_id'] = explode('_', $attr['attr_id'])[0];
                $attrrCopy = $attrs;
                unset($attrrCopy[$key]);
                $attrItems[$courseAttrsItems[$attr['attr_id']]] = [
                    'filter_value' => $attr['attr_value'],
                    'url' => Url::to(array_merge(['index'], array_merge($params, ['attrs' => $attrrCopy]))),
                ];
            };
        }

        $resultItems = array_merge($catItems, $attrItems);
        return $resultItems;
    }

    /**
     * 获取该课件下的所有属性
     * @param integer $course_id               课件id
     * @return type
     */
    public function getCourseAttr($course_id) {
        return (new Query())
                        ->select(['CourseAttr.value'])
                        ->from(['CourseAttr' => CourseAttr::tableName()])
                        ->leftJoin(['Attribute' => CourseAttribute::tableName()], 'Attribute.id = CourseAttr.attr_id')
                        ->where(['CourseAttr.course_id' => $course_id, 'Attribute.index_type' => 1])
                        ->orderBy(['Attribute.sort_order' => SORT_ASC])
                        ->all();
    }

    /**
     * 保存搜索日志数据
     * @param array $params
     */
    public function saveSearchLog($params) {
        $searchLogs = [
            'keyword' => ArrayHelper::getValue($params, 'keyword'),
            'created_at' => time(),
            'updated_at' => time()
        ];
        /** 添加$Logs数组到表里 */
        if ($searchLogs != null)
            Yii::$app->db->createCommand()->insert(SearchLog::tableName(), $searchLogs)->execute();
    }

}
