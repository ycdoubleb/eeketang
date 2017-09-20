<?php

namespace common\models\course;

use Yii;
use yii\caching\Cache;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%course_category}}".
 *
 * @property string $id         
 * @property string $name                   分类名称
 * @property string $mobile_name            移动端名称
 * @property string $parent_id              父级id
 * @property string $parent_id_path         继承id路径
 * @property integer $level                 等级：1分类、2学科
 * @property integer $sort_order            排序
 * @property integer $is_show               是否显示
 * @property string $image                  图片
 * @property integer $is_hot                是否热门
 * 
 * @property CourseCategory $parent         分类
 * @property string $fullPath               全路径
 */
class CourseCategory extends ActiveRecord
{
    /* @var $cache Cache */
    private static $cache;
    /**
     * @see cache
     */
    private static $cacheKey = 'eekt_course_category';
    
    /**
     * 分类[id,name,mobile_name,parent_id,parent_id_path,level,sort_order,is_show,image,is_hot]
     * @var array
     */
    private static $categorys;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'level', 'sort_order', 'is_show', 'is_hot'], 'integer'],
            [['name', 'mobile_name', 'parent_id_path', 'image'], 'string', 'max' => 255],
        ];
    }
    
    public function beforeSave($insert) {
        if(parent::beforeSave($insert)){
            if($this->mobile_name == ""){
                $this->mobile_name = $this->name;
            }
            //设置等级
            if(empty($this->parent_id)){
                $this->parent_id = 0;
            }
            $this->level = $this->parent_id == 0 ? 1 : self::getCatById($this->parent_id)->level+1;
            return true;
        }
        return false;
    }
    
    /**
     * 更新父级继承路径
     */
    public function updateParentPath(){
        //设置继承路径
        $parent = self::getCatById($this->parent_id);
        $this->parent_id_path = ($this->level == 1 ? "0" : "$parent->parent_id_path").",$this->id";
        $this->update(false, ['parent_id_path']);
    }
    
    /**
     * 父级
     * @return ActiveQuery
     */
    public function getParent(){
        self::initCache();
        return self::getCatById($this->parent_id);
    }
    
    /**
     * 获取全路径
     */
    public function getFullPath(){
        self::initCache();
        $parentids = array_values(array_filter(explode(',', $this->parent_id_path)));
        $path = '';
        foreach ($parentids as $index => $id){
            $path .= ($index == 0 ? '' : ' > ').self::getCatById($id)->name;
        }
        return $path;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'mobile_name' => Yii::t('app', 'Mobile Name'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'parent_id_path' => Yii::t('app', 'Parent Id Path'),
            'level' => Yii::t('app', 'Level'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'is_show' => Yii::t('app', 'Is Show'),
            'image' => Yii::t('app', 'Image'),
            'is_hot' => Yii::t('app', 'Is Hot'),
        ];
    }
    
    //==========================================================================
    //
    // Cache
    //
    //==========================================================================
    /* 初始缓存 */
    private static function initCache()
    {
        if (self::$cache == null) {
            self::$cache = Instance::ensure([
                'class' => 'yii\caching\FileCache',
                'cachePath' => FRONTEND_DIR.'/runtime/cache'
            ], Cache::className());
        }
        self::loadFromCache();
    }
    
    /**
     * 取消缓存
     */
    public static function invalidateCache() 
    {
        self::initCache();
        if(self::$cache !== null)
        {
            self::$cache->delete(self::$cacheKey);
            self::$categorys = null;
        }
    }
    
    /**
     * 从缓存中获取数据
     */
    private static function loadFromCache()
    {
        if (self::$categorys !== null || !self::$cache instanceof Cache) {
            return;
        }
        $data = self::$cache->get(self::$cacheKey);
        if (is_array($data) && isset($data[0])) {
            //从缓存取出团队与团队成员数据
            self::$categorys = ArrayHelper::index($data[0], 'id');
            return;
        }
        $categoryDatas = self::find()->asArray()->all();
        //没有缓存则从数据库获取数据
        self::$categorys = ArrayHelper::index($categoryDatas,'id');
        
        self::$cache->set(self::$cacheKey, [$categoryDatas]);
    }
    //==========================================================================
    //
    // public method
    //
    //==========================================================================
    /**
     * 获取分类
     * @param array $condition      默认返回所有分类
     * @param bool $key_to_value    返回键值对形式
     * @param bool $include_unshow  是否包括隐藏的分类
     * 
     * @return array(array|Array) 
     */
    public static function getCatsByLevel($level = 1, $key_to_value = false , $include_unshow = false){
        self::initCache();
        $categorys = [];
        foreach (self::$categorys as $id => $category) {
            if($category['level'] == $level && ($include_unshow || $category['is_show'] == 1)){
                $categorys[] = $category;
            }
        }
        
        return $key_to_value ? ArrayHelper::map($categorys, 'id', 'name') : $categorys;
    }
    
    /**
     * 获取分类的子级
     * @param integer $id               分类ID
     * @param bool $key_to_value        返回键值对形式
     * @param bool $recursion           是否递归
     * @param bool $include_unshow      是否包括隐藏的分类
     * 
     * @return array [array|key=value]
     */
    public static function getCatChildren($id, $key_to_value = false, $recursion = false, $include_unshow = false){
        self::initCache();
        $childrens = [];
        foreach (self::$categorys as $c_id => $category) {
            if($category['parent_id'] == $id && ($include_unshow || $category['is_show'] == 1)){
                $childrens[] = $category;
                if($recursion){
                    $childrens = array_merge($childrens, self::getCatChildren($c_id, $key_to_value, $recursion, $include_unshow));
                }
            }
        }
        return $key_to_value ? ArrayHelper::map($childrens, 'id', 'name') : $childrens;
    }
    
    /**
     * 获取分类的子级ID
     * @param integer $id               分类ID
     * @param bool $recursion           是否递归
     * @param bool $include_unshow      是否包括隐藏的分类
     * 
     * @return array [id,id...]
     */
    public static function getCatChildrenIds($id, $recursion = false, $include_unshow = false){
       self::initCache();
        $childrens = [];
        foreach (self::$categorys as $c_id => $category) {
            if($category['parent_id'] == $id && ($include_unshow || $category['is_show'] == 1)){
                $childrens[] = $c_id;
                if($recursion){
                    $childrens = array_merge($childrens, self::getCatChildrenIds($c_id, $recursion, $include_unshow));
                }
            }
        }
        return $childrens;
    }
    /**
     * 反回当前（包括父级）分类同级的所有分类
     * @param integer $id               分类ID
     * @param bool $$containerSelfLevel 是否包括该分类同级分类
     * @param bool $recursion           是否递归（向上级递归）
     * @param bool $include_unshow      是否包括隐藏的分类
     * 
     * @return array [[level_1],[level_2],..]
     */
    public static function getSameLevelCats($id, $containerSelfLevel=false, $recursion = true, $include_unshow = false){
        $catgegory = self::getCatById($id);
        $categorys = ($containerSelfLevel && !empty($id)) ? [self::getCatChildren($id,true,false,$include_unshow)] : [];
        do{
            if($catgegory == null){
                //当前分类为空时返回顶级分类
                $categorys []= self::getCatsByLevel(1,true);
                break;
            }else{
                array_unshift($categorys, self::getCatChildren($catgegory->parent_id,true,false,$include_unshow));
                if(!$recursion)break;
            }
            if($catgegory->parent_id == 0)break;
        }while(($catgegory = self::getCatById($catgegory->parent_id)) != null);
        return $categorys;
    }
    
    /**
     * 获取分类
     * @param integer $id
     */
    public static function getCatById($id){
        self::initCache();
        if(isset(self::$categorys[$id])){
            return new CourseCategory(self::$categorys[$id]);
        }
        return null;
    }
}
