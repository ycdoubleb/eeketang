<?php

namespace common\models;

use common\models\course\CoursewaveNode;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%examine_result}}".
 *
 * @property string $id
 * @property string $node_id        关联的节点ID
 * @property string $user_id        用户ID
 * @property string $score          分数
 * @property string $data           附加数据
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CoursewaveNode $node    课件节点
 */
class ExamineResult extends ActiveRecord
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
        return '{{%examine_result}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['score', 'created_at', 'updated_at'], 'integer'],
            [['data'], 'string'],
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
            'score' => Yii::t('app', 'Score'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * 课件节点
     * @return ActiveQuery
     */
    public function getNode()
    {
        return $this->hasOne(CoursewaveNode::className(), ['id' => 'node_id']);
    }
}
