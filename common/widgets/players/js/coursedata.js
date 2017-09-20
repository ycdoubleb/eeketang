/**
 * 学生信息
 *
 **/
var studentInfo = {
	id : 1234, // id号
	name : "wskeee", // 名字
	school : "林屋中学" //学校
};
 /**
 * 环节积分情况集
 * @type  tbkt 为同步课堂，lxzd: 练习指导，khts  课后测试 
 */
var tacheStateInfo ={
	tbkt : {
		state : 2,//完成状态 1 :未完成 2：学习中 3:完成
		test:"pass",//pass or upPass
		subState : [2,2,2,2,2,2,2]//知识点的完成状态数组 [2,1,1,1,1] 0 :未完成 1：完成
	},
	lxzd : {
		state : 2,
		subState : [2,2,2,2,2,2,2]
	},
	khcs : {
		state : 2,
		subState : [2,2,2,2,2,2,2]
	}
};
/**
 * 记录上次学习进度
 * 
 **/
 
var stateLogInfo = {
	stateName:"tbkt",	// 当前学习进度名
	subStateIndex : -1 	// 当前学习进度的子进度
};


var smallFoodData=[
   //{name:名字,icon:图标路径,readTime:阅读时间};

  ];
/**
 * 课件数据集对象
 * @type 
 */
var cosdate = {
	studentInfo : studentInfo,  //学习信息
	tacheState : tacheStateInfo,//学习情况集
	stateLogInfo : stateLogInfo,//当前学生学习进度
	studyTime : {//学习时间集
		taskTimeLong : 12,//学习时长 数字类型
		studiedTime : 0,//已学习时间 数字类型
		LastTime : ""//上次学习时间  字符类型
	},
	testInfoRecord : {//测试情况记录集
		MaxScore : 0,//1、历史最高分数 数字类型
		theScore : 0,//2、本次则试成绩 数字类型
		testCount : 0//3、则试次数 数字类型
	},
	ScoreRecord:{//积分分数集
	    studyScore:0,//勤奋分 数字类型
	    joinScore:0,//参与分 数字类型
	    testScore:0 //测试分 数字类型
	},
	smallFoodData:smallFoodData//小脚印数据
};