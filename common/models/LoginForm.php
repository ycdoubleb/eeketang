<?php
namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    protected $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required','message' =>Yii::t('app', 'Username').Yii::t('app', 'Can not be empty.')],
            [['password'], 'required','message' =>Yii::t('app', 'Password').Yii::t('app', 'Can not be empty.')],
            [['role'], 'integer'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            
            $hasLogin = Yii::$app->user->login($this->getUser(), $this->rememberMe ?Yii::$app->params['user.autoLoginDuration'] : 0);
            
            if($hasLogin){
                //使用session和表tbl_admin_session记录登录账号的token:time&id&ip,并进行MD5加密  
                $id = Yii::$app->user->id;     //登录用户的ID  
                $username = $this->username; //登录账号  
                $ip = Yii::$app->request->userIP; //登录用户主机IP  
                $token = md5(sprintf("%s&%s&%s",time(),$id,$ip));  //将用户登录时的时间、用户ID和IP联合加密成token存入表  

                $session = Yii::$app->session;  
                $session->set(md5(sprintf("%s&%s",$id,$username)),$token);  //将token存到session变量中  
                //？存session token值没必要取键名为$id&$username ,目的是标识用户登录token的键，$id或$username就可以  
                
                $this->insertSession($id,$token);//将token存到tbl_admin_session  
                //生成访问令牌
                $this->onGenerateAccessToken();
            }
            
            return $hasLogin;
        } else {
            return false;
        }
    }
    
    public function insertSession($id,$sessionToken)  
    {  
        $loginAdmin = AdminSession::findOne(['id' => $id]); //查询admin_session表中是否有用户的登录记录  
        if(!$loginAdmin){ //如果没有记录则新建此记录  
            $sessionModel = new AdminSession();  
            $sessionModel->id = $id;  
            $sessionModel->session_token = $sessionToken;  
            $result = $sessionModel->save();  
        }else{          //如果存在记录（则说明用户之前登录过）则更新用户登录token  
            $loginAdmin->session_token = $sessionToken;  
            $result = $loginAdmin->update();  
        }  
        return $result;  
    } 

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
    
    /**
     * 登录校验成功后，为用户生成新的token
     * 如果token失效，则重新生成token
     */
    public function onGenerateAccessToken ()
    {
        if (!User::isAccessTokenValid($this->_user->access_token)){
            $this->_user->generateAccessToken();
            $this->_user->save(false);
        }
    }
}
