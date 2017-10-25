<?php

namespace frontend\controllers;

use common\models\Buyunit;
use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\course\Subject;
use common\models\PlayLog;
use common\models\Teacher;
use common\models\WebLoginForm;
use common\models\WebUser;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use const YII_ENV_TEST;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * 
     * @return type
     */
    public function actionIndex() {

        return $this->render('index', [
                    'totalQuery' => $this->getCourseRankingList(),
                    'weekQuery' => $this->getCourseRankingList($this->getWeekDate()),
                    'tm_logo' => Course::$tm_logo,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new WebLoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            Yii::$app->user->setReturnUrl(Yii::$app->request->referrer);
            $model->role = ArrayHelper::getValue(Yii::$app->request->queryParams, 'role');
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact() {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout() {
        return $this->render('about');
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionUnauthorized($ip) {
        if (Yii::$app->request->isAjax) {
            Yii::$app->getResponse()->format = 'json';
            $is_success = 0;
            $message = '无效体验码。';
            $buyunit = Buyunit::searchByIp($ip);
            if ($buyunit != null && $buyunit['is_experience']) {
                $is_success = 1;
                $message = '';
            }
            return [
                'type' => $is_success,
                'message' => $message,
            ];
        } else {
            $this->layout = '@app/views/layouts/_unauthorized';
            return $this->render('unauthorized', ['ip' => $ip]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionExperience($experience_code) {
        return $this->redirect(['site/index', 'experience_code' => $experience_code]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup() {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * 获取课程播放量排行
     * @return array                 返回课程播放量排行前九名的数据
     */
    public function getCourseRankingList($params = []) {

        $last_start = ArrayHelper::getValue($params, 'last_start');         //上周开始日期
        $last_end = ArrayHelper::getValue($params, 'last_end');             //上周结束日期

        $query = (new Query())
                ->select([
                    'PlayLog.course_id', 'Count(PlayLog.user_id) AS play_num',
                    'GROUP_CONCAT(DISTINCT PlayLog.user_id SEPARATOR \',\') as user_id',
                    'GROUP_CONCAT(DISTINCT WebUser.real_name SEPARATOR \',\') as real_name',
                    'GROUP_CONCAT(DISTINCT WebUser.avatar SEPARATOR \',\') as avatar',
                    'Course.courseware_name AS cour_name', 'Course.unit', 'Course.term',
                    'Course.tm_ver', 'Course.grade', 'Subject.img AS sub_img', 'Teacher.img AS tea_img',
                    'Category.name AS cate_name',
                    'IF(Attribute.index_type=1,GROUP_CONCAT(DISTINCT CourseAttr.value SEPARATOR \'|\'),\'\') as attr_values'
                ])
                ->from(['PlayLog' => PlayLog::tableName()])
                //关联课程
                ->leftJoin(['Course' => Course::tableName()], 'Course.id = PlayLog.course_id')
                //关联课程分类
                ->leftJoin(['CourseCategory' => CourseCategory::tableName()], 'CourseCategory.id = Course.cat_id')
                //关联课程所属学院
                ->leftJoin(['Category' => CourseCategory::tableName()], 'Category.id = CourseCategory.parent_id')
                //关联课程学科
                ->leftJoin(['Subject' => Subject::tableName()], '`Subject`.id = Course.subject_id')
                //关联课程老师
                ->leftJoin(['Teacher' => Teacher::tableName()], 'Teacher.id = Course.teacher_id')
                //关联用户
                ->leftJoin(['WebUser' => WebUser::tableName()], 'WebUser.id = PlayLog.user_id')
                //关联查询课程属性
                ->leftJoin(['CourseAttr' => CourseAttr::tableName()], 'CourseAttr.course_id = Course.id')
                //关联查询属性
                ->leftJoin(['Attribute' => CourseAttribute::tableName()], 'Attribute.id = CourseAttr.attr_id')
                ->where(['is_publish' => 1])
                //查询前一周的数据
                ->andFilterWhere(['between', 'PlayLog.created_at', $last_start, $last_end])
                ->groupBy('PlayLog.course_id')
                ->orderBy(["Count(PlayLog.user_id)" => SORT_DESC])//排倒序
                ->limit(9);

        $results = [];
        foreach ($query->all() as $index => $item) {
            $item['rank'] = $index <= 2 ? $index + 1 : '';
            $results[] = $item;
        }
        
        return $results;
    }

    /**
     * 计算周的起始和结束的时间
     * @return array
     */
    public function getWeekDate() {
        $date = date('Y-m-d');  //当前日期
        $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start = date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $now_end = date('Y-m-d', strtotime("$now_start +6 days"));  //本周结束日期
        $last_start = date('Y-m-d', strtotime("$now_start - 7 days"));  //上周开始日期
        $last_end = date('Y-m-d', strtotime("$now_start - 1 days"));  //上周结束日期

        return [
            'now_start' => strtotime($now_start),
            'now_end' => strtotime($now_end),
            'last_start' => strtotime($last_start),
            'last_end' => strtotime($last_end),
        ];
    }

}
