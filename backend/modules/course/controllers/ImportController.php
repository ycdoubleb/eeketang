<?php

namespace backend\modules\course\controllers;

use common\models\course\Course;
use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use common\models\course\CourseCategory;
use common\models\course\CourseModel;
use common\models\course\Subject;
use common\models\Teacher;
use wskeee\utils\ExcelUtil;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DefaultController implements the CRUD actions for Course model.
 */
class ImportController extends Controller
{
    private $courses = [];      //所有课程数据
    private $teachers = [];     //所有教师数据
    private $repeats = [];      //所有课程属性
    private $hasExits = [];      //所有课程属性
    private $logs = [];
    private $success = 0;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add-course' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Course models.
     * @return mixed
     */
    public function actionIndex()
    {
        $upload = UploadedFile::getInstanceByName('course-data');
        if($upload != null)
        {
            $string = $upload->name;
            $excelutil = new ExcelUtil();
            $excelutil->load($upload->tempName);
            $columns = $excelutil->getSheetDataForRow()[0]['data'];
            
            //分析数据
            $courses = $this->analyze($columns);
            $maxCount = count($courses);
            //去掉重复数据
            $courses = $this->unique($courses);
            return $this->render('index_result',['maxCount' => $maxCount ,'courses' => $courses, 'repeats' => $this->repeats]);
            //test
            
            //整理课程属性
            $courses = $this->clearUpAttr($courses);
            //创建教师
            $courses = $this->createTeacher($courses);
            //创建课程
            $courses = $this->createCourse($courses);
            //创建课程属性
            $courses = $this->createAttr($courses);
            
        }
        return $this->render('index');
    }
    
    /**
     * 添加课程
     */
    public function actionAddCourse(){
        \Yii::$app->response->format = 'json';
        $courses = json_decode(Yii::$app->getRequest()->getRawBody(),true)['courses'];
        
        try{
            //整理课程属性
            $courses = $this->clearUpAttr($courses);
            //创建教师
            $courses = $this->createTeacher($courses);
            //创建课程
            $courses = $this->createCourse($courses);
            //创建课程属性
            $courses = $this->createAttr($courses);
        } catch (\Exception $ex) {
            return ['result' => 0,'success' => $this->success,'logs' => $this->logs,'msg' => $ex->getMessage().$ex->getTraceAsString()];
        }
        return ['result' => 1,'success' => $this->success,'logs' => $this->logs,'msg' => ''];
    }
    
    
    /**
     * 分析数据
     * @param type $columns
     */
    private function analyze($columns){
        //获取表头对应字段
        $keys = $columns[0];
        $keys = array_filter($keys);
        //删除第一二行，保留数据行
        array_splice($columns, 0 , 2);
        
        $targets = [];
            
        foreach($columns as $column){
            $temp = [[],[],[]];
            if($column[0] == null)continue;
            foreach ($column as $index => $value){
                if(!isset($keys[$index]))continue;
                //获取字段名
                $key = $keys[$index];
                $key_arr = explode(':', $key);
                //往对应数据填数据
                $temp[$key_arr[0]][$key_arr[1]] = $value;
            }
            
            $targets[] = ['course' => $temp[0],'attr' => $temp[1],'teacher' => $temp[2]];
        }
        return $targets;
    }
    
    /**
     * 去掉重复数据
     * @param type $courses
     */
    private function unique($courses){
        $hasExits = [];
        foreach ($courses as $key => &$course){
            isset($course['course']['name']) ? : $course['course']['name'] = '' ;
            //检查是否存在课件名，为空时指向课程名
            if($course['course']['courseware_name'] == null)
                $course['course']['courseware_name'] = $course['course']['name'];
            //subject_id__unit__name__courseware_name__tm_ver__grade__term__attr_name:attr_value
            $keyName = $course['course']['subject_id'].'__'.$course['course']['unit'].'__'.$course['course']['name'].'__'.$course['course']['courseware_name'];
            if(isset($course['course']['tm_ver'])){
                $keyName.='__'.$course['course']['tm_ver'];
            }
            if(isset($course['course']['grade'])){
                $keyName.='__'.$course['course']['grade'];
            }
            if(isset($course['course']['term'])){
                $keyName.='__'.$course['course']['term'];
            }
            
            foreach ($course['attr'] as $attr_name => $attr_value){
                $keyName .= '__'.$attr_name.':'.$attr_value;
            }
            
            if(isset($hasExits[$keyName])){
                $this->repeats []= $course;
                unset($courses[$key]);
            }else{
                $hasExits[$keyName] = true;
            }
        }
        return array_values($courses);
    }
    
    /**
     * 整理课程属性数据
     * @param array $courses = array([course,attr,teacher],...);
     */
    private function clearUpAttr($courses){
        //查找对应模型
        $attr_keys = CourseAttribute::find()
                ->select(['id','name','sort_order'])
                ->where(['course_model_id' => $courses[0]['course']['course_model_id']])
                ->asArray()->all();
        $this->addLog('使用的模型：',  CourseModel::findOne(['id' => $courses[0]['course']['course_model_id']])->name);   
        //[name => [id,name,order],...]
        $attr_keys = ArrayHelper::index($attr_keys, 'name');
        
        
        //整理成表结构 name 变 id
        //
        //course.attr = [
        //  'attr_id,
        //  'value',
        //  'sort_order'
        //]
        $new_attrs = [];
        foreach ($courses as &$course){
            $new_attrs = [];
            foreach($course['attr'] as $key => $value){
                $new_attrs[]= [
                    'attr_id' => $attr_keys[$key]['id'],
                    'value' => $value,
                    'sort_order' => $attr_keys[$key]['sort_order'],
                ];
            }
            unset($course['attr']);
            $course['attr'] = $new_attrs;
        }
        $this->addLog('整理成表结构 name 变 id');
        return $courses;
    }
    
    /**
     * 创建教师
     * @param array $courses = array([course,attr,teacher],...);
     */
    private function createTeacher($courses){
        //查寻已经存在教师
        $hasExits = Teacher::find()
                ->select(['id','CONCAT(name,\'_\',school) as name'])
                ->where(['name' => array_unique(ArrayHelper::getColumn($courses, 'teacher.name'))])
                ->asArray()
                ->all();
        $hasExit_name = ArrayHelper::map($hasExits, 'name','id');
        $this->addLog('查寻已经存在教师 ',count($hasExits).' 个存在！');   
        //整理出需要创建教师
        $rows = [];
        $now = time();
        foreach ($courses as $course){
            if($course['teacher']['name']!=null && !isset($hasExit_name[$course['teacher']['name'].'_'.$course['teacher']['school']]))
            {
                $hasExit_name[$course['teacher']['name'].'_'.$course['teacher']['school']] = true;
                $rows []= [$course['teacher']['name'],$course['teacher']['school'],$course['teacher']['job_title'],$now,$now];
            }
        }
        //插入教师数据
        Yii::$app->db->createCommand()->batchInsert(Teacher::tableName(), ['name','school','job_title','created_at','updated_at'] , $rows)->execute();
        $this->addLog('创建教师',"创建 ".count($rows)." 个!");   
        //获取所有教师 name => id 数据
        //查寻所有教师
        $teachers = Teacher::find()
                ->select(['id','name'])
                ->where(['name' => array_unique(ArrayHelper::getColumn($courses, 'teacher.name'))])
                ->asArray()
                ->all();
        $teachers_name = ArrayHelper::map($teachers, 'name','id');
        
        $eeteacher = Teacher::findOne(['name' => 'EE教师']);
        //更新课程教师id
        foreach ($courses as &$course){
            $course['course']['teacher_id'] = isset($teachers_name[$course['teacher']['name']]) ? $teachers_name[$course['teacher']['name']] : $eeteacher->id;
        }
        return $courses;
    }
    
    /**
     * 创建课程数据
     * 1、替换分类id
     * 2、替换学科id
     * 3、插入新课程数据
     * @param array $courses = array([course,attr,teacher],...);
     */
    private function createCourse($courses){
        //学科数据
        $subject = ArrayHelper::map((new Query())
                ->select(['id','name'])
                ->from(Subject::tableName())
                ->all(),'name','id');
        //查寻以格式"parent_cat_name,cat_name"显示
        $category = (new Query())
                ->select(['C.id','CONCAT(P.name,",",C.name) as name'])
                ->from(['C'=>  CourseCategory::tableName()])
                ->leftJoin(['P'=>  CourseCategory::tableName()], 'C.parent_id = P.id')
                ->where('C.level>1')
                ->all();
        //查寻学科以(name => id)键子对，方便下面替换学科
        $categorys = ArrayHelper::map($category,'name','id');
        $terms = array_flip(Course::$term_keys);
        $grades = array_flip(Course::$grade_keys);
        //替换分类和学科、年级、学期
        foreach ($courses as &$courseData){
            //学科名称 换 学科id
            $courseData['course']['cat_id'] = $categorys[$courseData['course']['cat_id']];
            $courseData['course']['subject_id'] = $subject[$courseData['course']['subject_id']];
            if(isset($courseData['course']['term'])){
                $courseData['course']['term'] = $terms[$courseData['course']['term']];
            }
            if(isset($courseData['course']['grade'])){
                $courseData['course']['grade'] = $grades[$courseData['course']['grade']];
            }
        }
        //查寻已经存的课程
        // ['id','cat_id','name','courseware_name','attrs'=>'attr_id:attr_value,...']
        //
        $hasExits = (new Query())
                ->select(['Course.id','Course.cat_id','Course.unit','Course.name','Course.courseware_name','GROUP_CONCAT(CourseAttr.attr_id,\':\',CourseAttr.value SEPARATOR \',\') as attrs'])
                ->from(['Course' => Course::tableName()])
                ->leftJoin(['CourseAttr' => CourseAttr::tableName()], 'CourseAttr.course_id = Course.id')
                ->where(['Course.courseware_name' => array_unique(ArrayHelper::getColumn($courses, 'course.courseware_name')),'cat_id' => array_unique(ArrayHelper::getColumn($courses, 'course.cat_id'))])
                ->groupBy('Course.id')
                ->all();
        
        //整理出需要创建的课程
        $rows = [];
        $now = time();
        $this->hasExits = [];
        $cmd = Yii::$app->db->createCommand("SHOW TABLE STATUS LIKE 'eekt_course'");
        $result = $cmd->queryAll();
        $start_id = (integer)$result[0]['Auto_increment'];
        foreach ($courses as $index => &$course){
            if(!$this->hasExit($hasExits, $course))
            {
                //手动添加以下字段
                $course['course']['id'] = $start_id++;
                $course['course']['created_at'] = $now;
                $course['course']['updated_at'] = $now;
                $course['course']['create_by'] = Yii::$app->user->id;
                $course['course']['is_publish'] = 1;
                $course['course']['is_recommend'] = (isset($course['course']['is_recommend']) && !empty($course['course']['is_recommend'])) ? $course['course']['is_recommend'] : 0;
                $course['course']['publish_time'] = $now;
                $course['course']['publisher_id'] = Yii::$app->user->id;;
                $course['course']['course_order'] = !isset($course['course']['course_order']) || $course['course']['course_order'] == '' ? 0 : $course['course']['course_order'];
                $course['course']['sort_order'] = $course['course']['sort_order'] == '' ? 0 : $course['course']['sort_order'];
                
                //修改课程路径
                $coursePlath = trim($course['course']['path']);
                substr($coursePlath, 0, 1) == '/' ? : $coursePlath = '/'.$coursePlath;
                substr($coursePlath, -1, 1) == '/' ? : $coursePlath = $coursePlath.'/';
                $course['course']['path'] = $coursePlath;
                
                $rows [] = $course['course'];
            }else{
                $this->hasExits [] = $course;
                unset($courses[$index]);
            }
        }
        if(count($rows)>0){
            $columns = array_keys($rows[0]);
            //插入课程数据
            Yii::$app->db->createCommand()->batchInsert(Course::tableName(), $columns , $rows)->execute();
        }
        $this->addLog('插入课程数据', '成功插入：'.count($rows)); 
        $this->success = count($rows);
        return $courses;
    }
    
    /**
     * 创建课程属性数据
     * @param array $courses = array([course,attr,teacher],...);
     */
    private function createAttr($courses){
        //删除已经存在的课程属性
        $courseIds = ArrayHelper::getColumn($courses, 'course.id');
        if(count($courseIds)>0)
            \Yii::$app->db->createCommand()->delete(CourseAttr::tableName(), ['course_id' => $courseIds])->execute();
        //组装插入数据
        $rows = [];
        foreach ($courses as &$course){
            foreach($course['attr'] as $attr){
                $attr['course_id'] = $course['course']['id'];
                $rows [] = $attr;
            }
        }
        if(count($rows)>0){
            $columns = array_keys($rows[0]);
            //插入课程属性数据
            Yii::$app->db->createCommand()->batchInsert(CourseAttr::tableName(), $columns , $rows)->execute();
        }
        $this->addLog('插入课程属性数据！'); 
    }
    
    /**
     * 检查课程是否已经录入
     * @param type $dbCourses            [['id','cat_id','unit','name','courseware_name','attrs' => 'attr_id:attr_value,...']]
     * @param type $importCourse        ['course'=>['cat_id','unit,'name','courseware_name'],attrs =>['attr_id','value']
     */
    private function hasExit($dbCourses,$importCourse){
        foreach($dbCourses as $dbCourse){
            //1、学科、单元、课程名、课件名相同
            if($dbCourse['cat_id'] == $importCourse['course']['cat_id'] 
                    && $dbCourse['unit'] == $importCourse['course']['unit']
                    && (!isset($importCourse['course']['name']) || $dbCourse['name'] == $importCourse['course']['name'])
                    && ($dbCourse['courseware_name'] == $importCourse['course']['courseware_name'])){
                if($dbCourse['attrs'] != ''){
                    //2、检查所有属性，看所有属性是否相同,每个属性格式：attr_id:attr_value
                    $importCourse_Attrs = ArrayHelper::index($importCourse['attr'], 'attr_id');
                    $mark = true;
                    foreach(explode(',', $dbCourse['attrs']) as $dbAttr){
                        $dbAttr_arr = explode(':', $dbAttr);
                        if(!isset($importCourse_Attrs[$dbAttr_arr[0]]) || $importCourse_Attrs[$dbAttr_arr[0]]['value'] != $dbAttr_arr[1]){
                            $mark = false;
                            break;
                        }
                    }
                    //所有属性都相同，说明课程相同
                    if($mark){
                        return true;
                    }
                }else{
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * 添加记录
     * @param type $stepName    步骤名
     * @param type $content     内容
     */
    private function addLog($stepName,$content=''){
        $this -> logs [] = ['stepName' => $stepName,'content' => $content];
    }

    /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
