<?php

namespace frontend\controllers;

use common\models\Buyunit;
use common\models\PlayLog;
use common\models\StudyLog;
use common\models\WebLoginForm;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\modules\study\controllers\DefaultController;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
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
        $query = (new Query())
                ->select(['StudyLog.course_id'])
                ->from(['StudyLog' => StudyLog::tableName()])
                ->all();
        $this->getTotalRankingList();
        return $this->render('index', [
                    'manNum' => DefaultController::getCourseStudyManNum($query),
        ]);
    }

    /**
     * 链接到品牌页面
     * @return type
     */
    public function actionBrandIndex() {
        return $this->redirect(['brand']);
    }

    /**
     * 跳转到品牌页面
     * @return type
     */
    public function actionBrand() {
        $this->layout = 'main_brand';
        return $this->render('/brand/index');
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

    public function getTotalRankingList() {
        $now_start=date('Y-m-d',strtotime(date('Y-m-d', time())."-".(date('w',strtotime(date('Y-m-d', time()))) ? date('w',strtotime(date('Y-m-d', time()))) - 1 : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $last_start=date('Y-m-d',strtotime("$now_start - 7 days"));  //上周开始日期
        $last_end=date('Y-m-d',strtotime("$now_start - 1 days"));  //上周结束日期
        //var_dump(date('Y-m-d',strtotime("-1 week",time())));exit;
        var_dump($last_start,$last_end);exit;
        $query = (new Query())
                ->select(['PlayLog.course_id' ,"Count(PlayLog.user_id) AS play_num"])
                ->from(['PlayLog' => PlayLog::tableName()])
                ->groupBy('PlayLog.course_id')
                ->orderBy(["Count(PlayLog.user_id)" =>SORT_DESC]);
         //$queryCopy = clone $query;
         var_dump( $query->all());exit;
        
        //$queryCopy->c    
    }

    public function getWeekRankingList() {
        $query = (new Query())
                ->select(['PlayLog.course_id','PlayLog.user_id'])
                ->from(['PlayLog' => PlayLog::tableName()])
                ->andWhere('between', 'create_at', strtotime(''), strtotime(''))
                ->all();
        
    }

}
