<?php

namespace backend\modules\menu\models;

use wskeee\rbac\components\Helper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu;

/**
 * This is the model class for table "k12_menu_backend".
 *
 * @property string $id                 id
 * @property string $parent_id          父级id
 * @property string $name               菜单名称
 * @property string $alias              别名
 * @property string $link               链接
 * @property string $icon               菜单图标
 * @property integer $is_show           是否显示：1为是，0为否
 * @property integer $level             等级
 * @property integer $sort_order        排序索引
 * @property string $created_at         创建时间
 * @property string $updated_at         更新时间
 */
class MenuBackend extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_backend}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() 
    {
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
            [['name', 'alias', 'link'], 'required'],
            [['parent_id', 'is_show', 'level', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'alias', 'icon'], 'string', 'max' => 60],
            [['link'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app','ID'),
            'parent_id' => Yii::t('app','Parent ID'),
            'name' => Yii::t('app','Name'),
            'alias' => Yii::t('app','Alias'),
            'link' => Yii::t('app','Link'),
            'icon' => Yii::t('app','Icon'),
            'is_show' => Yii::t('app','Is Show'),
            'level' => Yii::t('app','Level'),
            'sort_order' => Yii::t('app','Sort Order'),
            'created_at' => Yii::t('app','Created At'),
            'updated_at' => Yii::t('app','Updated At'),
        ];
    }
    
    /**
     * 父级
     * @return ActiveQuery
     */
    public function getParent(){
        return $this->hasOne(MenuBackend::className(), ['id'=>'parent_id']);
    }
    
    /**
     * 获取分类
     * @param array $condition 默认返回所有分类
     * @return array
     */
    public static function getCats($condition){
        return ArrayHelper::map(MenuBackend::find()->orFilterWhere($condition)->all(), 'id', 'name');
    }
    
    /**
     * 获取所有菜单
     * @param integer $leve   等级
     * @return model Menu
     */
    public static function getMenus ($level = 1){
        return MenuBackend::find()->where(['is_show' => true])->andFilterWhere(['level' => $level])->orderBy('sort_order asc');
    }
    
    /**
     * 组装菜单
     * @return type
     */
    public static function getBackendMenu(){
        $menus = self::getMenus(null)->all();
        $menuItems = [];
        foreach($menus as $_menu){
            if($_menu->parent_id == null){
                $children = self::getBacItemChildren($menus,$_menu->id);
                $item = [
                    'label' => $_menu->name,
                ];
                if(count($children)>0){
                    $item['url'] = $_menu->link;
                    $item['items'] = $children;
                }  else {
                    $item['url'] = [$_menu->link];
                }
                $item['icon'] = $_menu->icon;
                $menuItems[] = $item;
            }
        }
        return $menuItems;    
    }
    
    /**
     * 获取二级菜单
     * @param Menu $menu
     * @param array $allMenus  获取所有菜单
     * @param type $parnet_id
     * @return array
     */
    private static function getBacItemChildren($allMenus, $parent_id){
        $items = [];
        foreach ($allMenus as $menu){
            /* @var $menu Menu */
            if($menu->parent_id == $parent_id){
                if(Helper::checkRoute($menu->link)){
                    $items[] = [
                        'label' => $menu->name,
                        'url' => [$menu->link],
                        'icon' => $menu->icon,
                    ];
                }
            }    
        }
        
        return $items; 
    }
    
    
}
