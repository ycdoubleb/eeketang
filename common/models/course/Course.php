<?php

namespace common\models\course;

use common\models\Teacher;
use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%course}}".
 *
 * @property string $id
 * @property string $cat_id                     学科
 * @property string $template_sn                模板编号：s_00、s_01...
 * @property string $courseware_sn              课件编号：A01010101010101、A01010101010102、....
 * @property integer $type                      课件类型：flash、视频、实训
 * @property string $unit                       单元
 * @property string $name                       课程名称
 * @property string $courseware_name            课件名称
 * @property string $img                        课件图片
 * @property string $path                       课件路径
 * @property string $learning_objectives        学习目标
 * @property string $introduction               课程简介
 * @property string $synopsis                   课件摘要
 * @property string $teacher_id                 教师ID
 * @property integer $is_recommend              是否推荐
 * @property integer $is_publish                是否发布
 * @property string $content                    详细内容
 * @property integer $course_order              课程序号
 * @property integer $sort_order                排序
 * @property string $play_count                 播放次数
 * @property string $zan_count                  点赞数
 * @property string $favorites_count            收藏数
 * @property string $comment_count              评论数
 * @property string $publish_time               发布时间
 * @property string $publisher_id               发布人ID
 * @property string $keywords                   索引关键字
 * @property integer $term                       学期/册数 0~4 
 * @property string $tm_ver                     教材版本
 * @property integer $grade                     年级0~12 幼儿~高三
 * @property string $create_by                  创建人ID
 * @property string $created_at                 创建时间
 * @property string $updated_at                 更新时间
 * @property string $course_model_id            课件模型ID
 * 
 * @property CourseCategory $category           分类
 * @property Subject $subject                   学科
 * @property Teacher $teacher                   教师
 * @property CourseModel $courseModel           课程模型
 * @property User $creater                      创建人
 * @property User $publisher                    发布人
 * @property CourseTemplate $template           模板
 */
class Course extends ActiveRecord
{
    //课程类型
    public static $type_keys = [1=>'flash',2=>'视频',3=>'实训'];
    //年级
    public static $grade_keys = [0=>'',1=>'一年级',2=>'二年级',3=>'三年级',4=>'四年级',5=>'五年级',6=>'六年级',7=>'七年级',8=>'八年级',9=>'九年级',10=>'高一',11=>'高二',12=>'高三'];
    //学期、册数
    public static $term_keys = [0=>'',1=>'上册',2=>'下册',3=>'全一册'];
    //课程教材logo
    public static $tm_logo =[''=>'00','人教版'=>'01','广州版'=>'04','牛津上海版'=>'03','粤教版'=>'02'];
    //课程图片的背景颜色
    public static $backgroundColor =[
        '#d85328', '#3e7b80', '#eba4b4', '#235c91', '#615443', '#90bc41', '#e6834c', '#18a2c7', '#4b3a81', '#e58384',
        '#66b588', '#24a564', '#995a48', '#eaa154', '#3b8882', '#89b0cf', '#a47d42', '#a47086', '#eab79a', '#a1b0b7',
        '#aea62a', '#cb9863', '#d93442', '#73bfb3', '#a8d0cf', '#e4c5b3', '#7ab380', '#b69165', '#a1cbe1', '#e5b374',
        '#51706b', '#b55721', '#e29c3d', '#db5880', '#89562b', '#514f98', '#e0684f', '#5b7d40', '#dab330', '#cca44f',
        '#b05670', '#67381a', '#b28f79', '#ce578d', '#a8b62d', '#784e8e', '#136235', '#eab625', '#43a538', '#958183',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%course}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_id','subject_id', 'type', 'teacher_id', 'is_recommend', 'is_publish', 'course_order', 'sort_order', 'play_count', 'zan_count', 'favorites_count', 'comment_count', 'publish_time', 'created_at', 'updated_at', 'course_model_id','term','grade'], 'integer'],
            [['learning_objectives', 'introduction', 'unit', 'courseware_name','synopsis','content','publisher_id', 'create_by', 'template_sn', 'courseware_sn','tm_ver'], 'string'],
            [['name', 'img', 'path', 'keywords'], 'string', 'max' => 255],
        ];
    }
    
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_cat_id' => Yii::t('app', 'Parent Cat'),
            'cat_id' => Yii::t('app', 'Cat'),
            'template_sn' => Yii::t('app', 'Template'),
            'courseware_sn' => Yii::t('app', 'Courseware'),
            'type' => Yii::t('app', 'Type'),
            'unit' => Yii::t('app', 'Unit'),
            'name' => Yii::t('app', 'Course Name'),
            'courseware_name' => Yii::t('app', 'Courseware Name'),
            'img' => Yii::t('app', 'Course Img'),
            'path' => Yii::t('app', 'Course Path'),
            'learning_objectives' => Yii::t('app', 'Learning Objectives'),
            'introduction' => Yii::t('app', 'Introduction'),
            'synopsis' => Yii::t('app', 'Synopsis'),
            'teacher_id' => Yii::t('app', 'Teacher'),
            'is_recommend' => Yii::t('app', 'Is Recommend'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'content' => Yii::t('app', 'Content'),
            'course_order' => Yii::t('app', 'Sort Order'),
            'sort_order' => Yii::t('app', 'Sort Order'),
            'play_count' => Yii::t('app', 'Play Count'),
            'zan_count' => Yii::t('app', 'Zan Count'),
            'favorites_count' => Yii::t('app', 'Favorites Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'publish_time' => Yii::t('app', 'Publish Time'),
            'publisher_id' => Yii::t('app', 'Publisher'),
            'keywords' => Yii::t('app', 'Keywords'),
            'subject_id' => Yii::t('app', 'Subject'),
            'term' => Yii::t('app', 'Term'),
            'tm_ver' => Yii::t('app', 'Teaching Material Version'),
            'grade' => Yii::t('app', 'Grade'),
            'role' => Yii::t('app', 'Role'),
            'create_by' => Yii::t('app', 'Create By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'course_model_id' => Yii::t('app', '{Course} {Model}',['Course' => Yii::t('app', 'Course'),'Model' => Yii::t('app', 'Model')]),
        ];
    }
    
    /**
     * 图片上传--数据存储加后缀？r . rand(1, 10000)；
     * @param type $insert
     * @return boolean
     */
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)){
            if($this->getOldAttribute('is_publish')!=$this->is_publish){
                if($this->is_publish){
                    $this->publish_time = time();
                    $this->publisher_id = Yii::$app->user->id;
                }else{
                    $this->publish_time = null;
                    $this->publisher_id = null;
                }
            }
            //图片上传
            $upload = UploadedFile::getInstance($this, 'img');
            if($upload !== null){
                $string = $upload->name;
                $array = explode('.', $string);
                //获取后缀名，默认名为.jpg
                $ext = count($array) == 0 ? 'jpg' : $array[count($array)-1];
                $uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/courseThumbImg/');
                $upload->saveAs($uploadpath.$this->courseware_sn.'.'.$ext) ;
                $this->img = '/filedata/courseThumbImg/'.$this->courseware_sn.'.'.$ext. '?r=' . rand(1, 10000);
            }
            if(trim($this->img) == ''){
                $this->img = $this->getOldAttribute('img');
            }
            return true;
        }
        return false;
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param type $uploadpath  目标路径
     * @return type
     */
    private function fileExists($uploadpath){
        if(!file_exists($uploadpath)){
            mkdir($uploadpath);
        }
        return $uploadpath;
    }

    /**
     * 分类
     * @return ActiveQuery
     */
    public function getCategory(){
        return CourseCategory::getCatById($this->cat_id);
    }
    
    /**
     * 学科
     * @return ActiveQuery
     */
    public function getSubject(){
        return $this->hasOne(Subject::className(), ['id'=>'subject_id']);
    }
    
    /**
     * 教师
     * @return ActiveQuery
     */
    public function getTeacher(){
        return $this->hasOne(Teacher::className(), ['id'=>'teacher_id']);
    }
    /**
     * 课程模型
     * @return ActiveQuery
     */
    public function getCourseModel(){
        return $this->hasOne(CourseModel::className(), ['id'=>'course_model_id']);
    }
    
    /**
     * 创建人
     * @return ActiveQuery
     */
    public function getCreater(){
        return $this->hasOne(User::className(), ['id'=>'create_by']);
    }
    
    /**
     * 发布人
     * @return ActiveQuery
     */
    public function getPublisher(){
        return $this->hasOne(User::className(), ['id'=>'publisher_id']);
    }
    
    /**
     * 模板
     * @return ActiveQuery
     */
    public function getTemplate(){
        return $this->hasOne(CourseTemplate::className(), ['sn'=>'template_sn']);
    }
    
}
