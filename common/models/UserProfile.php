<?php

namespace common\models;

use Yii;

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
class UserProfile extends \yii\db\ActiveRecord
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

}
