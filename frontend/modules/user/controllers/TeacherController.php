<?php

namespace frontend\modules\user\controllers;

use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\Favorites;
use common\models\StudyLog;
use common\models\WebUser;
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
                'only' => ['sync', 'subject', 'diathesis', 'study', 'favorites', 'delete'],
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
//        $rank_results = $this->getWebUserStudentRanking();
        $view = \Yii::$app->view;
        $view->params['webUserRank']['cour_num'] = 35;//reset($rank_results);
        
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
        $rank_results = $this->getWebUserStudentRanking();
        $first_results = $this->getWebUserStudentRanking(['user_id'=>null,'rank'=>1]);
        $view = \Yii::$app->view;
        $view->params['webUserRank'] = reset($rank_results);
        $view->params['rankFirst'] = reset($first_results);
        
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
        $rank_results = $this->getWebUserStudentRanking();
        $first_results = $this->getWebUserStudentRanking(['user_id'=>null,'rank'=>1]);
        $view = \Yii::$app->view;
        $view->params['webUserRank'] = reset($rank_results);
        $view->params['rankFirst'] = reset($first_results);
        
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
        $rank_results = $this->getWebUserStudentRanking();
        $first_results = $this->getWebUserStudentRanking(['user_id'=>null,'rank'=>1]);
        $view = \Yii::$app->view;
        $view->params['webUserRank'] = reset($rank_results);
        $view->params['rankFirst'] = reset($first_results);
        
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
        $rank_results = $this->getWebUserStudentRanking();
        $first_results = $this->getWebUserStudentRanking(['user_id'=>null,'rank'=>1]);
        $view = \Yii::$app->view;
        $view->params['webUserRank'] = reset($rank_results);
        $view->params['rankFirst'] = reset($first_results);
        
        return $this->render('favorites', $results);
    }
    
    /**
     * 删除我的收藏
     * Renders the index view for the module
     * @return string
     */
    public function actionDelete($id = null)
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
     * 根据学生学习时长排名
     * @param array $params (['school_id', 'user_id', 'rank'])                 
     * @return array
     */
    public function getWebUserStudentRanking($params=[])
    {
        $school_id = ArrayHelper::getValue($params, 'school_id',Yii::$app->user->identity->school_id);    //学校id
        $user_id = ArrayHelper::getValue($params, 'user_id',Yii::$app->user->id);                         //用户id             
        $rank = ArrayHelper::getValue($params, 'rank');                                                   //排名                                                                             
        //var_dump($user_id);exit;
        //查询计算所有用户的学习时长
        $log_query = (new Query())->select(['StudyLog.user_id', 'SUM(StudyLog.studytime) AS studytime'])
            ->from(['StudyLog' => StudyLog::tableName()]);
        $log_query->groupBy('StudyLog.user_id');
        //按学校排名
        $rank_qurey = (new Query())->select(['COUNT(LogRank.studytime) + 1'])
            ->from(['LogRank' => $log_query]);
        $rank_qurey->leftJoin(['WebUserRank' => WebUser::tableName()], 'WebUserRank.id = LogRank.user_id');
        $rank_qurey->where('WebUserRank.school_id = WebUser.school_id');
        $rank_qurey->andWhere(['WebUserRank.role' => WebUser::ROLE_STUDENT]);
        $rank_qurey->andWhere('LogRank.studytime > SUM(StudyLog.studytime)');
        //查询所有用户的学校排名
        $all_query = (new Query())->select(['WebUser.real_name','WebUser.avatar', 
            'COUNT(StudyLog.id) AS cour_num',
            "({$rank_qurey->createCommand()->getRawSql()}) AS rank",
        ])->from(['StudyLog' => StudyLog::tableName()]);
        $all_query->leftJoin(['WebUser' => WebUser::tableName()], 'WebUser.id = StudyLog.user_id');
        $all_query->filterWhere(['WebUser.school_id' => $school_id]);
        $all_query->andFilterWhere(['WebUser.id' => $user_id]);
        $all_query->andFilterWhere(['WebUser.role' => WebUser::ROLE_STUDENT]);
        $all_query->groupBy('StudyLog.user_id');
        //条件查询
        $result_query = (new Query())->from(['WebUserRank' => $all_query]);
        $result_query->andFilterWhere(['WebUserRank.rank' => $rank]);
        //查询结果
        return $result_query->all();
    }
}
