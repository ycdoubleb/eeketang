<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "k12_buyunity_log".
 *
 * @property string $id
 * @property string $buyunit_id
 * @property string $buyunit_name
 * @property string $created_at
 * @property string $updated_at
 */
class BuyunityLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'k12_buyunity_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['buyunit_id'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
            [['buyunit_id'], 'string', 'max' => 32],
            [['buyunit_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'buyunit_id' => 'Buyunit ID',
            'buyunit_name' => 'Buyunit Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
