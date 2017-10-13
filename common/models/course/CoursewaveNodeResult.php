<?php

namespace common\models\course;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%coursewave_node_result}}".
 *
 * @property string $id
 * @property string $node_id
 * @property string $user_id
 * @property integer $result
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CoursewaveNode $node
 */
class CoursewaveNodeResult extends ActiveRecord
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
        return '{{%coursewave_node_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result', 'created_at', 'updated_at'], 'integer'],
            [['node_id'], 'string', 'max' => 32],
            [['user_id'], 'string', 'max' => 36],
            [['node_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoursewaveNode::className(), 'targetAttribute' => ['node_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'node_id' => Yii::t('app', 'Node ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'result' => Yii::t('app', 'Result'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(CoursewaveNode::className(), ['id' => 'node_id']);
    }
}
