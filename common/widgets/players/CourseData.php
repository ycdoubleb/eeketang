<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\widgets\players;

use common\models\course\CoursewaveNode;
use common\models\course\CoursewaveNodeResult;
use common\models\ExamineResult;
use common\models\StudyLog;
use common\models\WebUser;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of CourseData
 *
 * @author Administrator
 */
class CourseData {

    /**
     * 获取课程数据
     * @param int $course_id
     */
    public static function getCourseData($course_id,$user_id=null) {
        /* @var $user WebUser */
        $user = $user_id ? WebUser::findOne($user_id) : Yii::$app->user->identity;

        /**
         * 用户信息
         */
        $studentInfo = [
            'id' => $user->id,
            'name' => $user->real_name,
            'school' => '', //$user->school_id,
        ];

        /* 学习进度 */
        $stateLogInfo = [
            'stateName' => "",
            'subStateIndex' => -1,
            'subStateTitle' => '',
        ];

        /* 学习时间 */
        $studyTime = [
            'taskTimeLong' => 12,
            'studiedTime' => 0,
            'LastTime' => "",
        ];
        
        $testInfoRecord = self::getTestInfoRecord($course_id,$user->id);
        
        /* 考核成绩 */
        $ScoreRecord = [
            'studyScore' => 0,
            'joinScore' => 0,
            'testScore' => $testInfoRecord['theScore'],
        ];

        $coursedata = [
            'studentInfo' => $studentInfo,
            'tacheState' => self::getTacheState($course_id,$user->id),
            'stateLogInfo' => $stateLogInfo,
            'studyTime' => $studyTime,
            'testInfoRecord' => $testInfoRecord,
            'ScoreRecord' => $ScoreRecord,
        ];

        return $coursedata;
    }
    
    /**
     * 获取环节状态
     * @param type $course_id
     */
    private static function getTacheState($course_id,$user_id){
        /* @var $user WebUser */
        $user = Yii::$app->user->identity;
        $tacheState = [];
        $nodes = (new Query())
                ->select(['PNode.id as pid', 'PNode.sign', 'GROUP_CONCAT(IFNULL(NodeResult.result,1) ORDER BY Node.sort_order) AS subState'])
                ->from(['PNode' => CoursewaveNode::tableName()])
                ->leftJoin(['Node' => CoursewaveNode::tableName()], 'PNode.id = Node.parent_id')
                ->leftJoin(['NodeResult' => CoursewaveNodeResult::tableName()], "NodeResult.node_id = Node.id AND NodeResult.user_id = '$user_id'")
                ->where([
                    'PNode.course_id' => $course_id,
                    'PNode.level' => 1,
                    'PNode.is_show' => 1,
                    'Node.is_show' => 1,])
                ->groupBy('PNode.id')
                ->all();
        
        foreach ($nodes as $node) {
            $tacheState[$node['sign']] = [
                'state' => strstr($node['subState'],'1') ? 1 : 3,
                'test' => 'unpass',
                'subState' => explode(',', $node['subState']),
            ];
        }
        return $tacheState;
    }
    
    /**
     * 查询考核信息
     * @param integer $course_id    课程ID
     * @param string $user_id       用户ID
     * @return array [MaxScore,theScore,testCount]
     */
    public static function getTestInfoRecord($course_id,$user_id=null){
        $user_id = $user_id ? $user_id : Yii::$app->user->id;
        //找出课后测试的环节，拿环节ID
        $node = (new Query())
                ->select('Node.id')
                ->from(['Node' => CoursewaveNode::tableName()])
                ->leftJoin(['PNode' => CoursewaveNode::tableName()], 'Node.parent_id = PNode.id')
                ->where([
                    'PNode.course_id' => $course_id,
                    'PNode.sign' => 'khcs',])
                ->one();
         //查询所有考核记录
        $examines = [];
        if ($node) {
            $examines = ExamineResult::find()
                    ->where(['user_id' => $user_id, 'node_id' => $node['id']])
                    ->orderBy('created_at desc')
                    ->all();
            $examines = ArrayHelper::getColumn($examines, 'score');
        }
        
        
        /* 考核记录 */
        $testInfoRecord = [
            'MaxScore' => count($examines) > 0 ? max($examines) : 0, //最高成绩
            'theScore' => count($examines) > 0 ? $examines[0] : 0, //上次成绩
            'testCount' => count($examines), //测试次数
        ];
        
        return $testInfoRecord;
    }
    
    /**
     * 获取学习信息
     * @param integer $course_id    课程ID
     * @param string $user_id       用户ID
     * @return array [last_time,study_time,max_scroe]
     */
    public static function getStudyInfo($course_id,$user_id=null){
        $studyinfo = [
            'last_time' => '无',
            'study_time' => '0',
            'max_scroe' => '0',
        ];
        //查询学习记录
        $studylog = StudyLog::find()
                ->where(['course_id' => $course_id,'user_id' => $user_id])
                ->orderBy('updated_at desc')
                ->asArray()
                ->all();
        
        if(count($studylog)>0){
            $studytimes = ArrayHelper::getColumn($studylog, 'studytime');
            $studyinfo['last_time'] = date('Y-m-d H:i', $studylog[0]['updated_at']);
            $studyinfo['study_time'] = (array_sum($studytimes)/60).'分钟';
            $studyinfo['max_scroe'] = self::getTestInfoRecord($course_id, $user_id)['MaxScore'].'分';
        }
        return $studyinfo;
    }

}
