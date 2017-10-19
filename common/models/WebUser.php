<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UnauthorizedHttpException;
use yii\web\UploadedFile;

/**
 * This is the model class for table "security_web_user".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $real_name
 * @property integer $sex
 * @property string $tel
 * @property string $school_id
 * @property integer $subjects
 * @property integer $source
 * @property string $organization
 * @property string $create_time
 * @property integer $status
 * @property string $end_time
 * @property integer $role
 * @property string $avatar
 * @property integer $usages
 * @property string $name
 * @property integer $account_non_locked
 * @property string $remarks
 * @property integer $max_user
 * @property string $purchase
 * @property string $edu_id
 * @property string $workgroup_id
 * @property string $workgroup_name
 * @property string $workgroup_code
 * @property string $access_token
 * @property string $last_login_time
 * @property string $auth_key
 * 
 * @property UserProfile $profile
 */
class WebUser extends ActiveRecord implements IdentityInterface
{
     /** 创建场景 */
    const SCENARIO_CREATE = 'create';

    /** 更新场景 */
    const SCENARIO_UPDATE = 'update';
    
    //已停账号
    const STATUS_STOP = 1;
    //活动账号
    const STATUS_ACTIVE = 0;

    /** 性别 男 */
    const SEX_MALE = 1;

    /** 性别 女 */
    const SEX_WOMAN = 2;
    
    /** 角色 学生 */
    const ROLE_STUDENT = 1;
    /** 角色 老师 */
    const ROLE_TEACHER = 2;

    /**
     * 性别
     * @var array 
     */
    public static $sexName = [
        self::SEX_MALE => '男',
        self::SEX_WOMAN => '女',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'security_web_user';
    }
    
    public function scenarios() {
        return [
            self::SCENARIO_CREATE =>
            ['username', 'real_name', 'sex', 'password', 'password2', 'tel', 'avatar'],
            self::SCENARIO_UPDATE =>
            ['username', 'real_name', 'sex', 'password', 'password2', 'tel', 'avatar'],
            self::SCENARIO_DEFAULT => ['username']
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['sex', 'subjects', 'source', 'status', 'role', 'usages', 'account_non_locked', 'max_user', 'purchase'], 'integer'],
            [['create_time', 'last_login_time'], 'safe'],
            [['remarks'], 'string'],
            [['id', 'password', 'tel', 'organization', 'end_time'], 'string', 'max' => 100],
            [['username', 'real_name'], 'string', 'max' => 50],
            [['school_id', 'avatar', 'name', 'edu_id', 'workgroup_id', 'workgroup_name', 'workgroup_code'], 'string', 'max' => 255],
            [['access_token'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 32],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'real_name' => Yii::t('app', 'Real Name'),
            'sex' => Yii::t('app', 'Sex'),
            'tel' => Yii::t('app', 'Tel'),
            'school_id' => Yii::t('app', 'School ID'),
            'subjects' => Yii::t('app', 'Subjects'),
            'source' => Yii::t('app', 'Source'),
            'organization' => Yii::t('app', 'Organization'),
            'create_time' => Yii::t('app', 'Create Time'),
            'status' => Yii::t('app', 'Status'),
            'end_time' => Yii::t('app', 'End Time'),
            'role' => Yii::t('app', 'Role'),
            'avatar' => Yii::t('app', 'Avatar'),
            'usages' => Yii::t('app', 'Usages'),
            'name' => Yii::t('app', 'Name'),
            'account_non_locked' => Yii::t('app', 'Account Non Locked'),
            'remarks' => Yii::t('app', 'Remarks'),
            'max_user' => Yii::t('app', 'Max User'),
            'purchase' => Yii::t('app', 'Purchase'),
            'edu_id' => Yii::t('app', 'Edu ID'),
            'workgroup_id' => Yii::t('app', 'Workgroup ID'),
            'workgroup_name' => Yii::t('app', 'Workgroup Name'),
            'workgroup_code' => Yii::t('app', 'Workgroup Code'),
            'access_token' => Yii::t('app', 'Access Token'),
            'last_login_time' => Yii::t('app', 'Last Login Time'),
            'auth_key' => Yii::t('app', 'Auth Key'),
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
    public static function findByUsername($username, $role) {
        return static::findOne(['username' => $username, 'role' => $role, 'status' => self::STATUS_ACTIVE]);
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
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        if(($profile = UserProfile::findOne(['user_id' => $this->id])) !== null)
            return $profile;
        else {
            return new UserProfile();
        }
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
