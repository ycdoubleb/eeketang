<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agent_experience_card".
 *
 * @property string $id
 * @property string $experience_code
 * @property string $unit_name
 * @property integer $use_times
 * @property string $mobile
 * @property integer $status
 * @property string $delete_flag
 * @property string $dealer_user_id
 * @property string $create_by
 * @property string $create_date
 * @property string $update_by
 * @property string $update_date
 * @property string $audit_flag
 */
class ExperienceCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_experience_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'experience_code', 'dealer_user_id'], 'required'],
            [['use_times', 'status'], 'integer'],
            [['create_date', 'update_date'], 'safe'],
            [['id', 'experience_code', 'mobile', 'delete_flag', 'dealer_user_id', 'create_by', 'update_by'], 'string', 'max' => 32],
            [['unit_name'], 'string', 'max' => 100],
            [['audit_flag'], 'string', 'max' => 2],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'experience_code' => 'Experience Code',
            'unit_name' => 'Unit Name',
            'use_times' => 'Use Times',
            'mobile' => 'Mobile',
            'status' => 'Status',
            'delete_flag' => 'Delete Flag',
            'dealer_user_id' => 'Dealer User ID',
            'create_by' => 'Create By',
            'create_date' => 'Create Date',
            'update_by' => 'Update By',
            'update_date' => 'Update Date',
            'audit_flag' => 'Audit Flag',
        ];
    }
}
