<?php

namespace common\models;

use wskeee\rbac\RbacManager;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;

//use wskeee\rbac\RbacManager;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property string $id
 * @property string $username   用户名
 * @property string $nickname   昵称
 * @property integer $sex       性别
 * @property string $school_id  学校id
 * @property integer $subjects  所教科目
 * @property integer $source    账号来源 1.自己申请 2.机构分发
 * @property string $organization    分发机构
 * @property string $create_time     账号生成时间
 * @property string $end_time        会员到期时间
 * @property integer $role      1.学生 2.教师 3.行政
 * @property integer $usages    账号使用情况 0 未使用 1已使用
 * 
 * @property string $auth_key       验证
 * @property string $access_token   访问令牌
 * @property string password    密码
 * @property string $password_reset_token   重置密码令牌
 * @property string $email      邮箱
 * @property string $phone      手机
 * @property string $tel        手机 与 phone一样
 * @property string $avatar     头像
 * @property integer $status    状态0停用，1正常
 * @property integer $created_at    
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface {

    /** 创建场景 */
    const SCENARIO_CREATE = 'create';

    /** 更新场景 */
    const SCENARIO_UPDATE = 'update';
    //已停账号
    const STATUS_STOP = 0;
    //活动账号
    const STATUS_ACTIVE = 10;

    /** 性别 男 */
    const SEX_MALE = 1;

    /** 性别 女 */
    const SEX_WOMAN = 2;

    /**
     * 性别
     * @var array 
     */
    public static $sexName = [
        self::SEX_MALE => '男',
        self::SEX_WOMAN => '女',
    ];

    /* 重复密码验证 */
    public $password2;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    public function scenarios() {
        return [
            self::SCENARIO_CREATE =>
            ['username', 'nickname', 'sex', 'password', 'password2', 'phone', 'avatar'],
            self::SCENARIO_UPDATE =>
            ['username', 'nickname', 'sex', 'password', 'password2', 'phone', 'avatar'],
            self::SCENARIO_DEFAULT => ['username']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
        TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['password', 'password2'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['username', 'nickname', 'email'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_UPDATE]],
            [['username'], 'unique'],
            [['password'], 'string', 'min' => 6, 'max' => 64],
            [['username'], 'string', 'max' => 36, 'on' => [self::SCENARIO_CREATE]],
            [['id', 'username', 'password', 'password_reset_token', 'email', 'avatar','access_token'], 'string', 'max' => 255],
            [['sex'], 'integer'],
            [['auth_key'], 'string', 'max' => 255],
            [['password_reset_token', 'access_token'], 'unique'],
            [['email'], 'email'],
            [['avatar'], 'image'],
            [['password2'], 'compare', 'compareAttribute' => 'password'],
            [['avatar'], 'file', 'extensions' => 'jpg, png', 'mimeTypes' => 'image/jpeg, image/png']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'username' => Yii::t('app', 'Username'),
            'nickname' => Yii::t('app', 'Nickname'),
            'sex' => Yii::t('app', 'Sex'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password' => Yii::t('app', 'Password'),
            'password2' => Yii::t('app', 'Confirm Password'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'access_token' => Yii::t('app', 'Access Token'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'status' => Yii::t('app', 'Status'),
            'avatar' => Yii::t('app', 'Avatar'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 根据id查找
     * @param type $id
     * @return type common\models\User
     */
    public static function findIdentity($id) {
        return self::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        
        if (!static::isAccessTokenValid($token)) {
            throw new UnauthorizedHttpException("token is invalid.");
        }

        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * 检查访问令牌是否有效
     * @param type $token
     * @return boolean
     */
    public static function isAccessTokenValid($token) {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.accessTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inherdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * 检查用户是否属于 ｛roleName｝ 角色
     * @param string $roleName 角色名
     * @return bool
     */
    private function isRole($roleName) {
        /* @var $authManager RbacManager */
        //$authManager = Yii::$app->authManager;
        //return $authManager->isRole($roleName, $this->id);
    }

    /**
     * 验证授权码
     * @param type $authKey 授权码
     * @return type boolean
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = \Yii::$app->security->generateRandomString();
    }

    /**
     * 设置密码
     * @param type $password
     */
    public function setPassword($password) {
        $this->password = strtoupper(md5($password));
    }

    /**
     * 密码验证
     * @param type $password    待验证密码
     * @return type boolean
     */
    public function validatePassword($password) {
        return strtoupper(md5($password)) == $this->password;
    }

    /**
     * 生成密码重致令牌
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * 删除密码重致令牌
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * 生成访问控制令牌
     */
    public function generateAccessToken() {
        $this->access_token = \Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * 
     * @param type $insert 
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (!$this->id)
                $this->id = md5(rand(1, 10000) + time());    //自动生成用户ID
            $upload = UploadedFile::getInstance($this, 'avatar');
            if ($upload != null) {
                $string = $upload->name;
                $array = explode('.', $string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array) - 1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata') . '/avatars/');
                $upload->saveAs($uploadpath . $this->username . '.' . $ext);
                $this->avatar = '/filedata/avatars/' . $this->username . '.' . $ext . '?rand=' . rand(0, 1000);
            }


            if ($this->scenario == self::SCENARIO_CREATE) {
                $this->setPassword($this->password);
                //设置默认头像
                if (trim($this->avatar) == '')
                    $this->avatar = '/filedata/avatars/default/' . ($this->sex == 1 ? 'man' : 'women') . rand(1, 25) . '.jpg';
            }else if ($this->scenario == self::SCENARIO_UPDATE) {
                if (trim($this->password) == '')
                    $this->password = $this->getOldAttribute('password');
                else
                    $this->setPassword($this->password);

                if (trim($this->avatar) == '')
                    $this->avatar = $this->getOldAttribute('avatar');
            }


            if ($this->scenario == self::SCENARIO_CREATE)
                $this->generateAuthKey();

            return true;
        } else
            return false;
    }

    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     */
    private function fileExists($uploadpath) {

        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        return $uploadpath;
    }

}
