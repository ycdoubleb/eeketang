<?php

namespace common\models\course;

use Yii;

/**
 * This is the model class for table "{{%course_attribute}}".
 *
 * 定义一个属性，一个课程可带多个数据，属性可以有输入型、选择型
 * 
 * @property string $id                              
 * @property string $name                   属性名
 * @property string $course_model_id        所属模型id
 * @property integer $type                  类型：0唯一属性、1多行输入、2复选属性       
 * @property integer $input_type            输入类型：0手工输入、1多行输入、2列表选择
 * @property integer $sort_order            排序
 * @property integer $index_type            检索类型：0不参与检索、1关键字检索、2范围检索
 * @property string $values                 后选值，以换行符分隔
 * @property integer $is_delete             是否逻辑删除
 */
class CourseAttribute extends \yii\db\ActiveRecord
{
    //输入类型：手工输入 多行输入 列表选择
    const INPUT_TYPE_SINGLE = 0;
    const INPUT_TYPE_MULTILINE = 1;
    const INPUT_TYPE_LIST = 2;
    
    //属性类型：唯一属性 单选属性 复选属性'
    const TYPE_UNIQUE = 0;
    const TYPE_SINGLE = 1;
    const TYPE_MULTILINE = 2;
    
    //属性类型
    public static $type_keys = ['唯一属性','单选属性','复选属性'];
    //输入类型
    public static $input_type_keys = ['手工输入','多行输入','列表选择'];
    //检索类型
    public static $index_type_keys = ['否','是','范围检索'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['course_model_id', 'type', 'input_type', 'sort_order', 'index_type' , 'is_delete'], 'integer'],
            [['values'], 'string'],
            [['name'], 'string', 'max' => 50],
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
            'course_model_id' => Yii::t(null, '{Course} {Model}',['Course' => Yii::t('app', 'Course'),'Model'=>Yii::t('app', 'Model')]),
            'type' => Yii::t('app', 'Type'),
            'input_type' => Yii::t('app', 'Input Type'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'index_type' => Yii::t('app', 'Index Type'),
            'values' => Yii::t('app', 'Model Values'),
        ];
    }
}
