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
class StudentController extends Controller
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
        $rank_results = $this->getWebUserStudentRanking();
        $first_results = $this->getWebUserStudentRanking(['user_id'=>null,'rank'=>1]);
        $view = \Yii::$app->view;
        $view->params['webUserRank'] = reset($rank_results);
        $view->params['rankFirst'] = reset($first_results);
        
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
    public function actionDeleteFavorites($id = null)
    {
        if($id !== null)
            Favorites::findOne($id)->delete();
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
     * @param array $params (['school_id'=> '学校id', 'user_id' => '用户id', 'rank' => '名次']) 
     * @return array
     */
    public function getWebUserStudentRanking($params=[])
    {
        $school_id = ArrayHelper::getValue($params, 'school_id',Yii::$app->user->identity->school_id);    //学校id
        $user_id = ArrayHelper::getValue($params, 'user_id',Yii::$app->user->id);                         //用户id             
        $rank = ArrayHelper::getValue($params, 'rank');                                                   //名次
        //查找用户的总学习时长
        $log_query = (new Query())
            ->select(['StudyLog.user_id','WebUser.real_name','WebUser.avatar','SUM(StudyLog.studytime) AS studytime'])
            ->from(['StudyLog' => StudyLog::tableName()]);
        $log_query->leftJoin(['WebUser' => WebUser::tableName()], 'WebUser.id = StudyLog.user_id');
        $log_query->where(['WebUser.school_id' => $school_id]);
        $log_query->andWhere(['WebUser.role' => WebUser::ROLE_STUDENT]);
        $log_query->groupBy('StudyLog.user_id');
        //子查询，查排名
        $sub_query = (new Query())
            ->select(['UserStudytime.user_id','UserStudytime.real_name','UserStudytime.avatar','UserStudytime.studytime',
                '@curRank := IF (@PrevRank = UserStudytime.studytime ,@curRank ,@incRank) AS rank',
                '@incRank := @incRank + IF (@PrevRank = UserStudytime.studytime,0,1)',/*@incRank := @incRank + 1,*/
                '@prevRank := (UserStudytime.studytime)']);
        $sub_query->from(['UserStudytime' => $log_query, 'Var' => '(SELECT @curRank := 0 ,@prevRank := NULL,@incRank := 1)']);
        $sub_query->orderBy(['UserStudytime.studytime' => SORT_DESC]);
        //最终查询结果
        $query = (new Query())->from(['RankResult' => $sub_query]);
        $query->filterWhere(['RankResult.user_id' => $user_id]);
        $query->andFilterWhere(['RankResult.rank' => $rank]);
        //学习过的课程数
        $cour_num = $this->getStudyLogCourseNum($user_id);
        //排名结果
        $rank_results = [];
        foreach ($query->all() as $index=>$item) {
            unset($item['@incRank := @incRank + IF (@PrevRank = UserStudytime.studytime,0,1)']);
            unset($item['@prevRank := (UserStudytime.studytime)']);
            $item['cour_num'] = isset($cour_num[$item['user_id']])?$cour_num[$item['user_id']]:'';
            $rank_results[] = $item;
        }
       
        return $rank_results;
    }
    
    /**
     * 获取学习过的课程数
     * @param string|array $user_id
     * @return array
     */
    public function getStudyLogCourseNum($user_id=null)
    {
        if($user_id == null) $user_id = Yii::$app->user->id;
        //查询所有课程的学习时长
        $sub_query = (new Query())->select(['StudyLog.id','StudyLog.user_id','SUM(StudyLog.studytime) AS totaltime'])
            ->from(['StudyLog' => StudyLog::tableName()]);
        $sub_query->where(['user_id'=>$user_id])->groupBy('course_id');
        //查询学习超过5分钟的课程数
        $query = (new Query())->select(['StudyTime.user_id','COUNT(IF(StudyTime.totaltime/60<5,NULL,StudyTime.id)) AS cour_num'])
            ->from(['StudyTime' => $sub_query]);
        $query->groupBy('StudyTime.user_id');
       
        return ArrayHelper::map($query->all(), 'user_id', 'cour_num');
    }


    /**
     * 根据学生学习时长排名
     * @param array $params (['school_id'=> '学校id', 'user_id' => '用户id', 'rank' => '名次'])                 
     * @return array
     
    public function getWebUserStudentRanking($params=[])
    {
        $school_id = ArrayHelper::getValue($params, 'school_id',Yii::$app->user->identity->school_id);    //学校id
        $user_id = ArrayHelper::getValue($params, 'user_id',Yii::$app->user->id);                         //用户id             
        $rank = ArrayHelper::getValue($params, 'rank');                                                   //名次                                                                             
        //查询计算所有用户的学习时长
        $time_query = (new Query())->select(['StudyLog.user_id', 'SUM(StudyLog.studytime) AS studytime'])
            ->from(['StudyLog' => StudyLog::tableName()]);
        $time_query->groupBy('StudyLog.user_id');
        //查询计算用户超过5分钟学习时长的课程
        $excess_query = (new Query())->select(['IF(SUM(StudyLog.studytime)/60<5,NULL,StudyLog.id) AS id'])
            ->from(['StudyLog' => StudyLog::tableName()]);
        $excess_query->filterWhere(['StudyLog.user_id' => $user_id]);
        $excess_query->groupBy('StudyLog.course_id');
        //按学校排名
        $rank_qurey = (new Query())->select(['COUNT(LogRank.studytime) + 1'])
            ->from(['LogRank' => $time_query]);
        $rank_qurey->leftJoin(['WebUserRank' => WebUser::tableName()], 'WebUserRank.id = LogRank.user_id');
        $rank_qurey->where('WebUserRank.school_id = WebUser.school_id');
        $rank_qurey->andWhere(['WebUserRank.role' => WebUser::ROLE_STUDENT]);
        $rank_qurey->andWhere('LogRank.studytime > SUM(StudyLog.studytime)');
        //计算用户学习过的课程总数
        $num_qurey = (new Query())->select(['COUNT(LogCourNum.id)'])
            ->from(['LogCourNum' => $excess_query]);
        //查询所有用户的学校排名
        $all_query = (new Query())->select(['WebUser.real_name','WebUser.avatar', 
            "({$num_qurey->createCommand()->getRawSql()}) AS cour_num",
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
    }*/
}
