<?php

namespace frontend\modules\user\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\Favorites;
use common\models\StudyLog;
use common\models\TeacherCourse;
use frontend\modules\user\searchs\UserCourseSearch;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `user` module
 */
class TeacherController extends Controller
{
    public $layout = 'main';
    
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['sync', 'subject', 'diathesis', 'study', 'favorites'],
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
     * 同步课堂
     * Renders the index view for the module
     * @return string
     */
    public function actionSync()
    {
        $search = new UserCourseSearch();
        $results = $search->syncSearch();
        \Yii::$app->view->params['webUserRank'] = $this->getStudyLogCourseNum();
        
        if(Yii::$app->request->isAjax){
            Yii::$app->getResponse()->format = 'json';
            return [
                'tot' => $results['totalCount'],
                'cou' => $results['result']['courses'],
                'stu' => $results['result']['study'],
            ];
        }else{
            return $this->render('sync', [
                'filter' => $results['filter'],
                //'pages' => $results['pages'],
                'category' => $this->getCourseCategory(Yii::$app->request->queryParams),
                'subject' => $results['result']['subject'],
            ]);
        }
    }
    
    /**
     * 学科培优
     * Renders the index view for the module
     * @return string
     */
    public function actionSubject()
    {
        $search = new UserCourseSearch();
        $results = $search->collegeSearch(Yii::$app->request->queryParams);
        
        \Yii::$app->view->params['webUserRank'] = $this->getStudyLogCourseNum();
        
        return $this->render('college', $results);
    }
    
    /**
     * 素质提升
     * Renders the index view for the module
     * @return string
     */
    public function actionDiathesis()
    {
        $search = new UserCourseSearch();
        $results = $search->collegeSearch(Yii::$app->request->queryParams);
        
        \Yii::$app->view->params['webUserRank'] = $this->getStudyLogCourseNum();
        
        return $this->render('college', $results);
    }
    
    /**
     * 学习轨迹
     * Renders the index view for the module
     * @return string
     */
    public function actionStudy()
    {
        $search = new UserCourseSearch();
        $results = $search->studySearch();
        
        \Yii::$app->view->params['webUserRank'] = $this->getStudyLogCourseNum();
        
        return $this->render('study', $results);
    }
    
    /**
     * 我的收藏
     * Renders the index view for the module
     * @return string
     */
    public function actionFavorites()
    {
        $search = new UserCourseSearch();
        $results = $search->favoritesSearch();
        
        \Yii::$app->view->params['webUserRank'] = $this->getStudyLogCourseNum();
        
        return $this->render('favorites', $results);
    }
    
    /**
     * 删除选课的课程
     * Renders the index view for the module
     * @return string
     */
    public function actionDeleteChoice()
    {
        Yii::$app->getResponse()->format = 'json';
        $course = ArrayHelper::getValue(Yii::$app->request->post(), 'course_id');
        $courseIds = explode('&',str_replace('course_id=','',$course));
        $num = 0;
        foreach ($courseIds as $id){
            $num += Yii::$app->db->createCommand()->delete(TeacherCourse::tableName(),[
               'course_id' => $id, 'user_id' => Yii::$app->user->id])->execute();
        }
        if($num > 0) {
            return [
                'code' => '200',
                'data' => '',
                'message' => '',
            ];
        }else {
            return [
                'code' => '400',
                'data' => '',
                'message' => '',
            ];
        }
    }
    
    /**
     * 删除我的收藏
     * Renders the index view for the module
     * @return string
     */
    public function actionDeleteFavorites($id = null)
    {
        if($id !== null)
            Favorites::findOne ($id)->delete ();
        else
            Favorites::deleteAll(['user_id' => Yii::$app->user->id]);
    }

    
    
    /**
     * 获取所有课程的分类的二级分类
     * @param array $params                      
     * @return array
     */
    public function getCourseCategory($params)
    {
        //顶级分类
        $cat_id = ArrayHelper::getValue($params, 'cat_id');
        $categorys = CourseCategory::getCatChildren($cat_id, false, false, false, false);
        $catIds = ArrayHelper::getColumn($categorys, 'id');
        //查询分类下的所有课程总数
        $query = (new Query())->select(['CourseCategory.parent_id', 'COUNT(Course.id) AS total'])
                ->from(['Course' => Course::tableName()]);
        $query->leftJoin(['CourseCategory' => CourseCategory::tableName()], 'CourseCategory.id = Course.cat_id');
        $query->where(['CourseCategory.parent_id' => $catIds]);
        $query->groupBy('CourseCategory.parent_id');
        $category_total = ArrayHelper::map($query->all(), 'parent_id', 'total');
        $category_result = [];
        foreach($categorys as $cate){
            $cate['total'] = isset($category_total[$cate['id']]) ? $category_total[$cate['id']] : '';
            $category_result[] = $cate;
        }
       
        return  $category_result;
    }
    
    /**
     * 获取观摩过的课程数
     * @return array
     */
    public function getStudyLogCourseNum()
    {
        //查询所有课程的学习时长
        $query = (new Query())->select(['StudyLog.id', 'SUM(StudyLog.studytime) AS totaltime'])
            ->from(['StudyLog' => StudyLog::tableName()]);
        $query->where(['user_id' => Yii::$app->user->id])->groupBy('course_id');
        //查询学习超过5分钟的课程数
        $num_query = (new Query())->select(['COUNT(IF(StudyTime.totaltime/60<5,NULL,StudyTime.id)) AS cour_num'])
            ->from(['StudyTime' => $query]);
        return $num_query->one();
    }
}
