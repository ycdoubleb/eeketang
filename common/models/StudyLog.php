<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%study_log}}".
 *
 * @property string $id
 * @property string $course_id
 * @property string $user_id
 * @property string $studytime
 * @property string $created_at
 * @property string $updated_at
 */
class StudyLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%study_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_id', 'user_id'], 'required'],
            [['course_id', 'studytime', 'created_at', 'updated_at'], 'integer'],
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
            'studytime' => Yii::t('app', 'Studytime'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
