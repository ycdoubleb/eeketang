<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%play_log}}".
 *
 * @property string $id
 * @property string $course_id          课程ID  
 * @property string $user_id            用户ID
 * @property string $created_at
 * @property string $updated_at
 */
class PlayLog extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%play_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['course_id', 'user_id'], 'required'],
            [['course_id', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 32],
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
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

}
