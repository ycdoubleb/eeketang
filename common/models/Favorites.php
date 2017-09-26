<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%favorites}}".
 *
 * @property string $id       
 * @property string $course_id              课程ID
 * @property string $user_id                用户ID
 * @property string $tags                   标签
 * @property string $created_at
 * @property string $updated_at
 */
class Favorites extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%favorites}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'user_id'], 'required'],
            [['course_id', 'created_at', 'updated_at'], 'integer'],
            [['user_id'], 'string', 'max' => 32],
            [['tags'], 'string', 'max' => 255],
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
            'tags' => Yii::t('app', 'Tags'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
