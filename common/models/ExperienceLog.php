<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "k12_experience_log".
 *
 * @property string $id
 * @property string $experience_id
 * @property string $experience_code
 * @property string $experience_ip
 * @property string $unit_name
 * @property string $created_at
 * @property string $updated_at
 */
class ExperienceLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'k12_experience_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['experience_id', 'experience_code', 'experience_ip', 'unit_name'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['experience_id', 'experience_code'], 'string', 'max' => 32],
            [['experience_ip'], 'string', 'max' => 64],
            [['unit_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'experience_id' => 'Experience ID',
            'experience_code' => 'Experience Code',
            'experience_ip' => 'Experience Ip',
            'unit_name' => 'Unit Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
