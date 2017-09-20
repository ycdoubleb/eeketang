//=============================== 学习信息 ==========================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: 获取 学生学习信息 
 **/
function GetInformationValue()
{
	/*
	注意，1、跟以前旧版本相比，没有小脚印数据,但旧版本接口是不变的
	      2、需要实时（cosdate_b数据更改同步）
	*/
	parent.GetInformationValue();
}

/**
 * @ param			: obj(Object)
 					  格式:
					  {
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
							}
					  }
 * @ return			: void
 * @ description	: 由 后台调用 工具箱 显示数据
 **/
function updateInformation(obj)
{
	getMovie("main").updateInformation(obj);
}

//=============================== end ===============================

//=============================== 读书笔记 ==========================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: 通知后台获取读书笔记数据
 **/
function getReadNote()
{
	parent.getReadNote();
}
/**
 * @ param			: arr(Array);
 					  格式:
					  [
					   	{readNotes:"111111111111111111[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53"},
				  		{readNotes:"222222222222222222[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53"},
				  		{readNotes:"333333333333333333[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53"}
					  ]
 * @ return			: void
 * @ description	: 后台向 工具箱 传回 读书笔记 数据
 **/
function getReadingNote(arr)
{
	getMovie("main").getReadingNote(arr);
}

/**
 * @ param			: obj(Object)
 						格式: {readNotes:"555555555555555555[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53 PM"}		
 * @ return			: void
 * @ description	: 向后台提交读书笔记 需要追加到数据库
 **/
function sendReadNote(obj)
{
	parent.sendReadNote(obj);
}
//=============================== end ===================================

//=============================== 书签收藏夹 ===================================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: 通知后台获取 书签收藏夹 数据
 **/
function getFavoriteInfo()
{
	parent.getFavoriteInfo();
}
/**
 * @ param			: arr(Array)
 						格式:
							[
							 	{id:"2009081711031234",subjectName:"观潮",stateName:"学习指导",time:"2009-08-17 11:05",remark:"这是很有趣的一课！",subStateIndex:-1},
						 		{id:"2009081711031235",subjectName:"围城",stateName:"学习指导",time:"2009-09-17 9:05",remark:"这是很有趣的一课！",subStateIndex:-1}
							]
 * @ return			: void
 * @ description	: 后台传入 书签收藏夹 数据
 **/
function getFavorite(arr)
{
	getMovie("main").getFavorite(arr);
}
/**
 * @ param			: null
 * @ return			: void
 * @ description	: 通知后台获取 当前课程进度位置
 **/
function getSubjectInfo()
{	
	parent.getSubjectInfo();
}
/**
 * @ param			: obj(Object)
 						格式: {subStateName:"学习指导",subStateIndex:-1};
 * @ return			: void
 * @ description	: 后台向 工具箱 传入 当前课程进度位置 数据
 **/
function getSubject(obj)
{
	getMovie("main").getSubject(obj);
}
//<!--        -->
/**
 * @ param			: obj(Object)
 						格式:{subStateName:"学习指导",subStateIndex:-1,time:"2009-09-17 9：05 P.M",remark:"这是很有趣的一课！"}
 * @ return			: void
 * @ description	: 添加新的书签
 **/
function addFavorite(obj)
{
	parent.addFavorite(obj);
}
/**
 * @ param			: id(String)
 * @ return			: void
 * @ description	: 后台 由工具箱 添加新的书签 生成 id 并返回
 **/
function getNewFavoriteID(id)
{
	getMovie("main").getNewFavoriteID(id);
}
//<!--  -->
/**
 * @ param			: obj(Object)
 						格式:{subStateName:"学习指导",subStateIndex:-1}
 * @ return			: void
 * @ description	: 控制主模板课堂跳转(通知主程序 控制跳转)
 **/
function controlSubject(obj)
{
	alert(obj.subStateName+"  "+obj.subStateIndex)
	getMovie("main").setTbktProgress(obj.subStateName,obj.subStateIndex);
}
/**
 * @ param			: id(String)
 * @ return			: void
 * @ description	: 删除单个书签 根据接收到的ID号，通知后台数据库删除该ID号的信息
 **/
function deleteFavorite(id)
{
	parent.deleteFavorite(id);
}
//=============================== end ===================================

//=============================== 排行榜 ===================================
//
/**
 * @ param			: null
 * @ return			: void
 * @ description	: 通知后台获取数据(max 10条数据)
 **/
function getQueueData()
{
	parent.getQueueData();
}
/**
 * @ param			: obj(Object)
 						格式:
						[
						 	qf:[
									{name:"余泽天1",icon:"main/icon/boy1.png",school:"广雅中学",classRoom:"高二1班",honor:"书童",mark:18},
									{name:"陈艳",icon:"main/icon/gril1.png",school:"广雅中学",classRoom:"高二1班",honor:"书童",mark:16},
									{name:"麦兜",icon:"main/icon/boy1.png",school:"林屋中学",classRoom:"高二2班",honor:"书童",mark:15},
									{name:"余克彬",icon:"main/icon/boy2.png",school:"陆丰中学",classRoom:"高二5班",honor:"书童",mark:12},
									{name:"何阳超",icon:"main/icon/boy1.png",school:"林屋中学",classRoom:"高二1班",honor:"书童",mark:11},
									{name:"魏彬",icon:"main/icon/boy1.png",school:"广雅中学",classRoom:"高二3班",honor:"书童",mark:10}
								   ],
								cy:[
									{name:"余克彬2",icon:"main/icon/boy2.png",school:"陆丰中学",classRoom:"高二5班",honor:"书童",mark:20},
									{name:"余泽天",icon:"main/icon/boy1.png",school:"广雅中学",classRoom:"高二1班",honor:"书童",mark:18},
									{name:"何阳超",icon:"main/icon/boy1.png",school:"林屋中学",classRoom:"高二1班",honor:"书童",mark:14},
									{name:"麦兜",icon:"main/icon/boy1.png",school:"林屋中学",classRoom:"高二2班",honor:"书童",mark:11},
									{name:"陈艳",icon:"main/icon/gril1.png",school:"广雅中学",classRoom:"高二1班",honor:"书童",mark:10},
									{name:"魏彬",icon:"main/icon/boy1.png",school:"广雅中学",classRoom:"高二3班",honor:"书童",mark:9}
								   ],
								js:[
									{name:"余泽天3",icon:"main/icon/boy1.png",school:"广雅中学",classRoom:"高二1班",honor:"书童",mark:14},
									{name:"陈艳",icon:"main/icon/gril1.png",school:"广雅中学",classRoom:"高二1班",honor:"书童",mark:13},
									{name:"麦兜",icon:"main/icon/boy1.png",school:"林屋中学",classRoom:"高二2班",honor:"书童",mark:11},
									{name:"余克彬",icon:"main/icon/boy2.png",school:"陆丰中学",classRoom:"高二5班",honor:"书童",mark:10},
									{name:"何阳超",icon:"main/icon/boy1.png",school:"林屋中学",classRoom:"高二1班",honor:"书童",mark:6},
									{name:"魏彬",icon:"main/icon/boy1.png",school:"广雅中学",classRoom:"高二3班",honor:"书童",mark:3}
								   ]
						]
 * @ return			: void
 * @ description	: 后台传入 排行榜 数据
 **/
function getData(obj)
{
	getMovie("main").getData(obj);
}
//=============================== end ===================================

//=============================== 留言交流区 ===================================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: 通知后台获取 留言交流区 数据
 **/
function getCommunication()
{
	parent.getCommunication();
}
/**
 * @ param			: arr(Array)
 						格式:
							[
							   {name:"小明明明",icon:"main/icon/boy2.png",school:"广言中学高二2班",readTime:"2009-03-26 12:30",message:"111111111111111111[/000][/001][/002]"},
							   {name:"陈艳",icon:"main/icon/gril1.png",school:"广超中学高二1班",readTime:"2009-02-20 8:30",message:"2222222222222222[/000][/001][/002]"},
							   {name:"麦兜",icon:"main/icon/boy1.png",school:"大人中学高二2班",readTime:"2009-05-25 17:00",message:"3333333333333333333[/000][/001][/002]"}
							]
 * @ return			: void
 * @ description	: 后台传入 留言交流区 数据
 **/
function getInfo(arr)
{
	getMovie("main").getInfo(arr);
}

/**
 * @ param			: str(String) 学生留言
 * @ return			: void
 * @ description	: 向后台提交 用户留言信息
 **/
function setCommunication(str)
{
   	//
	parent.setCommunication(str)
}
/**
 * @ param			: arr(Array)
 						格式:[{name:"魏彬",icon:"main/icon/boy2.png",school:"肥龙中学高二4班",readTime:"2009-02-04 9:30",message:str}];
 * @ return			: void
 * @ description	: 该数据由后台跟据用户提交的留言生成的对象，传入flash
 **/
function getNewInfo(arr)
{
	getMovie("main").getNewInfo(arr);
}
//=============================== end ===================================

//=============================== 小脚印 ===================================
//
/*
	obj = 
*/
/**
 * @ param			: obj(Object)
 						格式:
							{
								startIndex:startIndex, 		分页开始序号
								itemNum:itemNum				个数
							}
 * @ return			: void
 * @ description	: 侦听工具箱获取 数据,通知后台获取分页数据
 **/
function getFoodPrintItem(obj)
{
	parent.getFoodPrintItem(obj);
}
/**
 * @ param			: obj(Object)
 						格式:
							{
								maxItem:14,				一共小脚印数
								data:					该分页数据
									[
									 	{name:"余泽天",icon:"main/icon/boy1.png",school:"广雅中学高二2班",readTime:"2009-02-20 9:30"},
				        				{name:"小何",icon:"main/icon/boy2.png",school:"广言中学高二2班",readTime:"2009-03-26 12:30"}
									]
							}
 * @ return			: void
 * @ description	: 后台跟据 flash 的分页请求返回分页数据,传入flash
 **/
function setFoodPrintItem(obj)
{
	getMovie("main").setFoodPrintItem(obj);
}

//=============================== end ===================================

//========================== 工具箱 visible 控制 =======================
/**
 * @ param			: bo(Boolean)
 * @ return			: void
 * @ description	: 控制 工具箱显示与隐藏
 **/
function onToolsVisible(bo)
{
	getMovie("main").onToolsVisible(bo);
}
/**
 * @ param			: bgURL(String)
 * @ return			: void
 * @ description	: 更改背景图片
 **/
function changeAppBG(bgURL)
{
	getMovie("main").changeAppBG(bgURL);
}

function onFullScreen(bo)
{
	getMovie("main").onFullScreen(bo);
}
//=============================== end ===================================

//=============================== utils =================================
/**
 * @ param			: movieName(要找对象名)
 * @ return			: void
 * @ description	: 搭建js与flash互通的环境
 **/
function getMovie(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName]
	}else{
		return document[movieName]
	}
} 
//========================== end ===========================