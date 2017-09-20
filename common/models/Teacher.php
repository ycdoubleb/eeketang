<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property string $id
 * @property string $name
 * @property string $school
 * @property string $job_title
 * @property string $img
 * @property integer $sex
 * @property string $created_at
 * @property string $updated_at
 */
class Teacher extends ActiveRecord
{
    //姓别
    public static $sex_keys = ['保密','男','女'];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacher}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school'], 'string'],
            [['sex', 'created_at', 'updated_at'], 'integer'],
            [['name', 'job_title'], 'string', 'max' => 50],
            [['img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'school' => Yii::t('app', 'School'),
            'job_title' => Yii::t('app', 'Job Title'),
            'img' => Yii::t('app', 'Teacher Img'),
            'sex' => Yii::t('app', 'Sex'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
