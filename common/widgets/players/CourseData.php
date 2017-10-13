<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\widgets\players;

use common\models\course\CoursewaveNode;
use common\models\course\CoursewaveNodeResult;
use common\models\WebUser;
use Yii;
use yii\db\Query;

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
    public static function getCourseData($course_id) {
        /* @var $user WebUser */
        $user = Yii::$app->user->identity;
        $nodes = (new Query())
                ->select(['PNode.id as pid','PNode.sign','GROUP_CONCAT(case when NodeResult.result IS NULL then 1 else 2 end ORDER BY Node.sort_order) AS subState'])
                ->from(['PNode'=>  CoursewaveNode::tableName()])
                ->leftJoin(['Node'=>  CoursewaveNode::tableName()],'PNode.id = Node.parent_id')
                ->leftJoin(['NodeResult' => CoursewaveNodeResult::tableName()],'NodeResult.node_id = Node.id')
                ->where([
                    'PNode.course_id' => $course_id,
                    'PNode.level' => 1,
                    'PNode.is_show' => 1,
                    'Node.is_show' => 1,])
                ->groupBy('PNode.id')
                ->all();
        
        
                
        /**
         * 用户信息
         */
        $studentInfo = [
            'id' => $user->id,
            'name' => $user->real_name,
            'school' => '', //$user->school_id,
        ];

        /* 环节学习状态 */
        $tacheState = [];
        
        foreach($nodes as $node){
            $tacheState[$node['sign']] = [
                'state' => 1,
                'test' => 'unpass',
                'subState' => explode(',', $node['subState']),
            ];
        }
        /* 学习进度 */
        $stateLogInfo = [
            'stateName' => "tbkt",
            'subStateIndex' => -1,
            'subStateTitle' => '',
        ];

        /* 学习时间 */
        $studyTime = [
            'taskTimeLong' => 12,
            'studiedTime' => 0,
            'LastTime' => "",
        ];
        /* 考核记录 */
        $testInfoRecord = [
            'MaxScore' => 0,//最高成绩
            'theScore' => 0,//上次成绩
            'testCount' => 0,//测试次数
        ];
        /* 考核成绩 */
        $ScoreRecord = [
            'studyScore' => 0,
            'joinScore' => 0,
            'testScore' => 0,
        ];

        $coursedata = [
            'studentInfo' => $studentInfo,
            'tacheState' => $tacheState,
            'stateLogInfo' => $stateLogInfo,
            'studyTime' => $studyTime,
            'testInfoRecord' => $testInfoRecord,
            'ScoreRecord' => $ScoreRecord,
        ];
        
        return $coursedata;
    }

}
