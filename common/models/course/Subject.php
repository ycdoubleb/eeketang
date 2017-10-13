<?php

namespace common\models\course;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%subject}}".
 *
 * @property string $id
 * @property string $name
 * @property integer $sort_order
 * @property string $img
 * @property string $created_at
 * @property string $updated_at
 */
class Subject extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%subject}}';
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order', 'created_at', 'updated_at'], 'integer'],
            [['name','img'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'img' => Yii::t('app', 'Img'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 
     * @param * $condition              自定义条件
     * @param boolen $key_to_value      是否返回键值对数据
     * 
     * @return array 
     */
    public static function getSubject($condition = null ,$key_to_value = true){
        $result = Subject::find();
        if($condition!=null){
            $result->andFilterWhere($condition);
        }
        $result = $result->all();
        return $key_to_value ? ArrayHelper::map($result, 'id', 'name') : $result;
    }
}
