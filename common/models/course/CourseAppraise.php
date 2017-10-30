<?php

namespace common\models\course;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%course_appraise}}".
 *
 * @property string $id
 * @property string $course_id          课程ID
 * @property string $user_id            用户ID
 * @property integer $result            课程评价结果：1赞，2踩
 * @property string $created_at
 * @property string $updated_at
 */
class CourseAppraise extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_appraise}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'user_id'], 'required'],
            [['course_id', 'result', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'result' => Yii::t('app', 'Result'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 
     * @return type
     */
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }
}
