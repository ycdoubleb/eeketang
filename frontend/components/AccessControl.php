<?php

namespace frontend\components;

use common\models\Buyunit;
use common\models\BuyunityLog;
use common\models\ExperienceLog;
use Yii;
use yii\base\ActionFilter;
use yii\base\Module;
use yii\di\Instance;
use yii\web\ForbiddenHttpException;
use yii\web\User;

/**
 * Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control. 
 * As its name indicates, ACF is an action filter that can be attached to a controller or a module as a behavior. 
 * ACF will check a set of access rules to make sure the current user can access the requested action.
 *
 * To use AccessControl, declare it in the application config as behavior.
 * For example.
 *
 * ```
 * 'as access' => [
 *     'class' => 'mdm\admin\components\AccessControl',
 *     'allowActions' => ['site/login', 'site/error']
 * ]
 * ```
 *
 * @property User $user
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AccessControl extends ActionFilter {

    /**
     * @var User User for check access.
     */
    private $_user = 'user';

    /**
     * @var array List of action that not need to check access.
     */
    public $allowActions = [];

    /**
     * Get user
     * @return User
     */
    public function getUser() {
        if (!$this->_user instanceof User) {
            $this->_user = Instance::ensure($this->_user, User::className());
        }
        return $this->_user;
    }

    /**
     * Set user
     * @param User|string $user
     */
    public function setUser($user) {
        $this->_user = $user;
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action) {
        $actionId = $action->getUniqueId();
        $user = $this->getUser();
        $ip = Yii::$app->request->userIP;
        $session_key = md5(sprintf("%s&%s", \Yii::$app->name, $ip));
        $session = Yii::$app->session;
        return true;
        if($session->get($session_key)){
            return true;
        }
        
        $buyunit = Buyunit::searchByIp($ip);
        if($buyunit == null){
            return \Yii::$app->getResponse()->redirect(['/site/unauthorized','ip' => $ip]);
        }else{
            if($buyunit['is_experience'])
                $this->saveExperienceLog($buyunit, $ip);
            else    
                $this->saveBuyunityLog($buyunit);
            $session->set($session_key,$buyunit);  //将token存到session变量中  
            return true;
        }
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param  User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user) {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * @inheritdoc
     */
    protected function isActive($action) {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        $user = $this->getUser();
        if ($user->getIsGuest()) {
            $loginUrl = null;
            if (is_array($user->loginUrl) && isset($user->loginUrl[0])) {
                $loginUrl = $user->loginUrl[0];
            } else if (is_string($user->loginUrl)) {
                $loginUrl = $user->loginUrl;
            }
            if (!is_null($loginUrl) && trim($loginUrl, '/') === $uniqueId) {
                return false;
            }
        }

        if ($this->owner instanceof Module) {
            // convert action uniqueId into an ID relative to the module
            $mid = $this->owner->getUniqueId();
            $id = $uniqueId;
            if ($mid !== '' && strpos($id, $mid . '/') === 0) {
                $id = substr($id, strlen($mid) + 1);
            }
        } else {
            $id = $action->id;
        }

        foreach ($this->allowActions as $route) {
            if (substr($route, -1) === '*') {
                $route = rtrim($route, "*");
                if ($route === '' || strpos($id, $route) === 0) {
                    return false;
                }
            } else {
                if ($id === $route) {
                    return false;
                }
            }
        }

        if ($action->controller->hasMethod('allowAction') && in_array($action->id, $action->controller->allowAction())) {
            return false;
        }

        return true;
    }

    /**
     * 保存购买商登录日志
     * @param array $logs                  日志
     */
    protected function saveBuyunityLog($logs)
    {
        $buyunitLogs = [
            'buyunit_id' => $logs['buyunity_id'],
            'buyunit_name' => $logs['buyunity_name'],
            'created_at' => time(),
            'updated_at' => time(),
        ];
        /** 添加$Logs数组到表里 */
        if($buyunitLogs != null)
            Yii::$app->db->createCommand()->insert(BuyunityLog::tableName(), $buyunitLogs)->execute();
    }
    
    /**
     * 保存验证日志
     * @param array $logs                  日志
     */
    protected function saveExperienceLog($logs, $ip)
    {
        $experienceLogs = [
            'experience_id' => $logs['experience_id'],
            'experience_code' => $logs['experience_code'],
            'experience_ip' => $ip,
            'unit_name' => $logs['experience_unit'],
            'created_at' => time(),
            'updated_at' => time(),
        ];
        /** 添加$Logs数组到表里 */
        if($experienceLogs != null)
            Yii::$app->db->createCommand()->insert(ExperienceLog::tableName(), $experienceLogs)->execute();
    }
    
}
