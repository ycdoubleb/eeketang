<?php

namespace common\models\course;

use Yii;

/**
 * This is the model class for table "{{%coursewave_node}}".
 *
 * @property string $id
 * @property string $parent_id
 * @property string $course_id
 * @property string $title
 * @property integer $type
 * @property string $sign
 * @property integer $level
 * @property integer $is_show
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CoursewaveNodeResult[] $coursewaveNodeResults
 * @property ExamineResult[] $examineResults
 */
class CoursewaveNode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coursewave_node}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['course_id', 'type', 'level', 'is_show', 'created_at', 'updated_at'], 'integer'],
            [['id', 'parent_id'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 50],
            [['sign'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'title' => Yii::t('app', 'Title'),
            'type' => Yii::t('app', 'Type'),
            'sign' => Yii::t('app', 'Sign'),
            'level' => Yii::t('app', 'Level'),
            'is_show' => Yii::t('app', 'Is Show'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoursewaveNodeResults()
    {
        return $this->hasMany(CoursewaveNodeResult::className(), ['node_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExamineResults()
    {
        return $this->hasMany(ExamineResult::className(), ['node_id' => 'id']);
    }
}
