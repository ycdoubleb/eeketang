<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property string $id                     id
 * @property string $parent_id              父级id
 * @property string $relate_id              关联id
 * @property string $name                   菜单名
 * @property string $alias                  别名
 * @property string $module                 所属模块
 * @property string $link                   链接
 * @property string $image                  图像
 * @property integer $is_show               是否显示：1为是0为否
 * @property integer $is_jump               是否跳转：1为是0为否
 * @property integer $level                 等级
 * @property string $position               菜单位置
 * @property integer $sort_order            排序索引
 * @property string $des                    菜单描述
 * @property integer $created_at             
 * @property integer $updated_at            
 * 
 * @property Menu $parent                   分类 
 */
class Menu extends ActiveRecord
{
    /** 后端位置 */
    const POSITION_BACKEND = 'backend';
    /** 前端位置 */
    const POSITION_FRONTEND = 'frontend';
    
    /**
     * 位置名称
     * @var array 
     */
    public static $positionName = [
        self::POSITION_BACKEND => '后台',
        self::POSITION_FRONTEND => '前端',
    ];
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
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
            [['name', 'alias', 'module', 'link'], 'required'],
            [['parent_id', 'relate_id', 'is_show', 'is_jump', 'level', 'sort_order', 'created_at', 'updated_at'], 'integer'],
            [['name', 'alias', 'module', 'position'], 'string', 'max' => 60],
            [['link', 'image'], 'string', 'max' => 255],
            [['des'], 'string'],
        ];
    }
    
     public function beforeSave($insert) {
        if(parent::beforeSave($insert)){
            //设置等级
            $this->level = $this->parent_id == 0 ?  1 : 2;
            //图片上传
            $upload = UploadedFile::getInstance($this, 'image');
            if($upload != null)
            {
                $string = $upload->name;
                $array = explode('.',$string);
                //获取后缀名，默认为 jpg 
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/site/memu/image/');
                $upload->saveAs($uploadpath.$this->alias.'.'.$ext);
                $this->image = '/filedata/site/memu/image/'.$this->alias.'.'.$ext;
            }
            
            if(trim($this->image) == '')
                  $this->image = $this->getOldAttribute ('image');
            
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'relate_id' => Yii::t('app', 'Relate ID'),
            'name' => Yii::t('app', 'Name'),
            'alias' => Yii::t('app', 'Alias'),
            'module' => Yii::t('app', 'Module'),
            'link' => Yii::t('app', 'Link'),
            'image' => Yii::t('app', 'Image'),
            'is_show' => Yii::t('app', 'Is Show'),
            'is_jump' => Yii::t('app', 'Is Jump'),
            'level' => Yii::t('app', 'Level'),
            'position' => Yii::t('app', 'Position'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'des' => Yii::t('app', 'Des'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    /**
     * 获取所有菜单数据
     * @return object
     */
    public static function getMenus ($position, $level = 1) {
        return Menu::find()->where(['is_show' => true, 'position' => $position])
               ->andFilterWhere(['level' => $level])->orderBy('sort_order asc')->all();
    }
    
    /**
     * 父级
     * @return ActiveQuery
     */
    public function getParent(){
        return $this->hasOne(Menu::className(), ['id'=>'parent_id']);
    }
    
    /**
     * 获取分类
     * @param array $condition 默认返回所有分类
     * @return array
     */
    public static function getCats($condition){
        return ArrayHelper::map(Menu::find()->orFilterWhere($condition)->all(), 'id', 'name');
    }
    
    /**
     * 组装菜单导航
     * @param string $position          菜单位置
     * @return array
     */
    public static function getMenusNavItem($position){
        $menus = self::getMenus($position, null);
        $menuItems = [];
        foreach($menus as $_menu){
            /* @var $_menu Menu */
            if($_menu->parent_id == 0){
                $children = self::getNavItemChildren($menus, $_menu->id);
                $item = [
                    'label'=> $_menu->name,
                ];
                if(count($children)>0){
                    $item['url'] = ["/{$_menu->module}{$_menu->link}"];
                    $item['items'] = $children;
                }else
                    $item['url'] = ["/{$_menu->module}{$_menu->link}"];
                $item['alias'] = $_menu->alias; 
                $item['module'] = $_menu->module;                 
                $menuItems[] = $item;
            }
        }
        return $menuItems;
    }
    
    /**
     * 获取二级导航
     * @param Menu $menu                
     * @param array $allMenus           获取所有导航
     * @param integer $parent_id        父级ID
     * @return array
     */
    private static function getNavItemChildren($allMenus, $parent_id){
        $items = [];
        foreach($allMenus as $menu){
            /* @var $menu Menu */
            if($menu->parent_id == $parent_id){
                $items[]=[
                    'label'=> $menu->name,
                    'url'=> ["/{$menu->module}{$menu->link}"],
                    'alias' => $menu->alias,
                    'module' => $menu->module,
                ];
            }
        }
        
        return $items;
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     */
    private function fileExists($uploadpath) {

        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        return $uploadpath;
    }
}
