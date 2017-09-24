<?php

namespace common\models;

use common\models\course\Course;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_profile".
 *
 * @property string $id
 * @property string $user_id
 * @property string $class_id
 * @property string $start_time
 * @property string $created_at
 * @property string $updated_at
 */
class UserProfile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['class_id', 'start_time', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'class_id' => Yii::t('app', 'Class ID'),
            'start_time' => Yii::t('app', 'Start Time'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
        
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * 
     * @param type $insert 
     */
    public function beforeSave($insert) 
    {
        if (parent::beforeSave($insert)) {
            $this->start_time = strtotime(date('Y', strtotime('-'.$this->start_time.'year')).'-09-01 00:00:00');
            return true;
        } else
            return false;
    }
    
    /**
     * 获取计算年级
     * @param type $default    默认值
     * @return integer|string
     */
    public function getGrade($default = true)
    {
        $year = date('Y', time()) - date('Y', $this->start_time);
        if(!$default)
            return $year;
        else
            return isset(Course::$grade_keys[$year]) ? Course::$grade_keys[$year] : null;
            
    }
}
