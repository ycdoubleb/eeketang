<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%note}}".
 *
 * @property string $id
 * @property string $course_id
 * @property string $user_id
 * @property string $content
 * @property string $zan_num
 * @property integer $is_show
 * @property string $created_at
 * @property string $updated_at
 */
class Note extends ActiveRecord
{
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%note}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'user_id'], 'required'],
            [['course_id', 'zan_num', 'is_show', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 32],
            [['content'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course'),
            'user_id' => Yii::t('app', 'User'),
            'content' => Yii::t('app', 'Content'),
            'zan_num' => Yii::t('app', 'Zan Num'),
            'is_show' => Yii::t('app', 'Is Show'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
