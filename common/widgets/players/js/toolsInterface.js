//=============================== ѧϰ��Ϣ ==========================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: ��ȡ ѧ��ѧϰ��Ϣ 
 **/
function GetInformationValue()
{
	/*
	ע�⣬1������ǰ�ɰ汾��ȣ�û��С��ӡ����,���ɰ汾�ӿ��ǲ����
	      2����Ҫʵʱ��cosdate_b���ݸ���ͬ����
	*/
	parent.GetInformationValue();
}

/**
 * @ param			: obj(Object)
 					  ��ʽ:
					  {
						  	studentInfo : studentInfo,  //ѧϰ��Ϣ
							tacheState : tacheStateInfo,//ѧϰ�����
							stateLogInfo : stateLogInfo,//��ǰѧ��ѧϰ����
							studyTime : {//ѧϰʱ�伯
								taskTimeLong : 12,//ѧϰʱ�� ��������
								studiedTime : 0,//��ѧϰʱ�� ��������
								LastTime : ""//�ϴ�ѧϰʱ��  �ַ�����
							},
							testInfoRecord : {//���������¼��
								MaxScore : 0,//1����ʷ��߷��� ��������
								theScore : 0,//2���������Գɼ� ��������
								testCount : 0//3�����Դ��� ��������
							},
							ScoreRecord:{//���ַ�����
								studyScore:0,//�ڷܷ� ��������
								joinScore:0,//����� ��������
								testScore:0 //���Է� ��������
							}
					  }
 * @ return			: void
 * @ description	: �� ��̨���� ������ ��ʾ����
 **/
function updateInformation(obj)
{
	getMovie("main").updateInformation(obj);
}

//=============================== end ===============================

//=============================== ����ʼ� ==========================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: ֪ͨ��̨��ȡ����ʼ�����
 **/
function getReadNote()
{
	parent.getReadNote();
}
/**
 * @ param			: arr(Array);
 					  ��ʽ:
					  [
					   	{readNotes:"111111111111111111[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53"},
				  		{readNotes:"222222222222222222[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53"},
				  		{readNotes:"333333333333333333[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53"}
					  ]
 * @ return			: void
 * @ description	: ��̨�� ������ ���� ����ʼ� ����
 **/
function getReadingNote(arr)
{
	getMovie("main").getReadingNote(arr);
}

/**
 * @ param			: obj(Object)
 						��ʽ: {readNotes:"555555555555555555[/000][/001][/002]",color:"",font:"",fontSize:"",readTime:"2009-08-07 15:53 PM"}		
 * @ return			: void
 * @ description	: ���̨�ύ����ʼ� ��Ҫ׷�ӵ����ݿ�
 **/
function sendReadNote(obj)
{
	parent.sendReadNote(obj);
}
//=============================== end ===================================

//=============================== ��ǩ�ղؼ� ===================================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: ֪ͨ��̨��ȡ ��ǩ�ղؼ� ����
 **/
function getFavoriteInfo()
{
	parent.getFavoriteInfo();
}
/**
 * @ param			: arr(Array)
 						��ʽ:
							[
							 	{id:"2009081711031234",subjectName:"�۳�",stateName:"ѧϰָ��",time:"2009-08-17 11:05",remark:"���Ǻ���Ȥ��һ�Σ�",subStateIndex:-1},
						 		{id:"2009081711031235",subjectName:"Χ��",stateName:"ѧϰָ��",time:"2009-09-17 9:05",remark:"���Ǻ���Ȥ��һ�Σ�",subStateIndex:-1}
							]
 * @ return			: void
 * @ description	: ��̨���� ��ǩ�ղؼ� ����
 **/
function getFavorite(arr)
{
	getMovie("main").getFavorite(arr);
}
/**
 * @ param			: null
 * @ return			: void
 * @ description	: ֪ͨ��̨��ȡ ��ǰ�γ̽���λ��
 **/
function getSubjectInfo()
{	
	parent.getSubjectInfo();
}
/**
 * @ param			: obj(Object)
 						��ʽ: {subStateName:"ѧϰָ��",subStateIndex:-1};
 * @ return			: void
 * @ description	: ��̨�� ������ ���� ��ǰ�γ̽���λ�� ����
 **/
function getSubject(obj)
{
	getMovie("main").getSubject(obj);
}
//<!--        -->
/**
 * @ param			: obj(Object)
 						��ʽ:{subStateName:"ѧϰָ��",subStateIndex:-1,time:"2009-09-17 9��05 P.M",remark:"���Ǻ���Ȥ��һ�Σ�"}
 * @ return			: void
 * @ description	: ����µ���ǩ
 **/
function addFavorite(obj)
{
	parent.addFavorite(obj);
}
/**
 * @ param			: id(String)
 * @ return			: void
 * @ description	: ��̨ �ɹ����� ����µ���ǩ ���� id ������
 **/
function getNewFavoriteID(id)
{
	getMovie("main").getNewFavoriteID(id);
}
//<!--  -->
/**
 * @ param			: obj(Object)
 						��ʽ:{subStateName:"ѧϰָ��",subStateIndex:-1}
 * @ return			: void
 * @ description	: ������ģ�������ת(֪ͨ������ ������ת)
 **/
function controlSubject(obj)
{
	alert(obj.subStateName+"  "+obj.subStateIndex)
	getMovie("main").setTbktProgress(obj.subStateName,obj.subStateIndex);
}
/**
 * @ param			: id(String)
 * @ return			: void
 * @ description	: ɾ��������ǩ ���ݽ��յ���ID�ţ�֪ͨ��̨���ݿ�ɾ����ID�ŵ���Ϣ
 **/
function deleteFavorite(id)
{
	parent.deleteFavorite(id);
}
//=============================== end ===================================

//=============================== ���а� ===================================
//
/**
 * @ param			: null
 * @ return			: void
 * @ description	: ֪ͨ��̨��ȡ����(max 10������)
 **/
function getQueueData()
{
	parent.getQueueData();
}
/**
 * @ param			: obj(Object)
 						��ʽ:
						[
						 	qf:[
									{name:"������1",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:18},
									{name:"����",icon:"main/icon/gril1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:16},
									{name:"��",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�2��",honor:"��ͯ",mark:15},
									{name:"��˱�",icon:"main/icon/boy2.png",school:"½����ѧ",classRoom:"�߶�5��",honor:"��ͯ",mark:12},
									{name:"������",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:11},
									{name:"κ��",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�3��",honor:"��ͯ",mark:10}
								   ],
								cy:[
									{name:"��˱�2",icon:"main/icon/boy2.png",school:"½����ѧ",classRoom:"�߶�5��",honor:"��ͯ",mark:20},
									{name:"������",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:18},
									{name:"������",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:14},
									{name:"��",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�2��",honor:"��ͯ",mark:11},
									{name:"����",icon:"main/icon/gril1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:10},
									{name:"κ��",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�3��",honor:"��ͯ",mark:9}
								   ],
								js:[
									{name:"������3",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:14},
									{name:"����",icon:"main/icon/gril1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:13},
									{name:"��",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�2��",honor:"��ͯ",mark:11},
									{name:"��˱�",icon:"main/icon/boy2.png",school:"½����ѧ",classRoom:"�߶�5��",honor:"��ͯ",mark:10},
									{name:"������",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�1��",honor:"��ͯ",mark:6},
									{name:"κ��",icon:"main/icon/boy1.png",school:"������ѧ",classRoom:"�߶�3��",honor:"��ͯ",mark:3}
								   ]
						]
 * @ return			: void
 * @ description	: ��̨���� ���а� ����
 **/
function getData(obj)
{
	getMovie("main").getData(obj);
}
//=============================== end ===================================

//=============================== ���Խ����� ===================================
/**
 * @ param			: null
 * @ return			: void
 * @ description	: ֪ͨ��̨��ȡ ���Խ����� ����
 **/
function getCommunication()
{
	parent.getCommunication();
}
/**
 * @ param			: arr(Array)
 						��ʽ:
							[
							   {name:"С������",icon:"main/icon/boy2.png",school:"������ѧ�߶�2��",readTime:"2009-03-26 12:30",message:"111111111111111111[/000][/001][/002]"},
							   {name:"����",icon:"main/icon/gril1.png",school:"�㳬��ѧ�߶�1��",readTime:"2009-02-20 8:30",message:"2222222222222222[/000][/001][/002]"},
							   {name:"��",icon:"main/icon/boy1.png",school:"������ѧ�߶�2��",readTime:"2009-05-25 17:00",message:"3333333333333333333[/000][/001][/002]"}
							]
 * @ return			: void
 * @ description	: ��̨���� ���Խ����� ����
 **/
function getInfo(arr)
{
	getMovie("main").getInfo(arr);
}

/**
 * @ param			: str(String) ѧ������
 * @ return			: void
 * @ description	: ���̨�ύ �û�������Ϣ
 **/
function setCommunication(str)
{
   	//
	parent.setCommunication(str)
}
/**
 * @ param			: arr(Array)
 						��ʽ:[{name:"κ��",icon:"main/icon/boy2.png",school:"������ѧ�߶�4��",readTime:"2009-02-04 9:30",message:str}];
 * @ return			: void
 * @ description	: �������ɺ�̨�����û��ύ���������ɵĶ��󣬴���flash
 **/
function getNewInfo(arr)
{
	getMovie("main").getNewInfo(arr);
}
//=============================== end ===================================

//=============================== С��ӡ ===================================
//
/*
	obj = 
*/
/**
 * @ param			: obj(Object)
 						��ʽ:
							{
								startIndex:startIndex, 		��ҳ��ʼ���
								itemNum:itemNum				����
							}
 * @ return			: void
 * @ description	: �����������ȡ ����,֪ͨ��̨��ȡ��ҳ����
 **/
function getFoodPrintItem(obj)
{
	parent.getFoodPrintItem(obj);
}
/**
 * @ param			: obj(Object)
 						��ʽ:
							{
								maxItem:14,				һ��С��ӡ��
								data:					�÷�ҳ����
									[
									 	{name:"������",icon:"main/icon/boy1.png",school:"������ѧ�߶�2��",readTime:"2009-02-20 9:30"},
				        				{name:"С��",icon:"main/icon/boy2.png",school:"������ѧ�߶�2��",readTime:"2009-03-26 12:30"}
									]
							}
 * @ return			: void
 * @ description	: ��̨���� flash �ķ�ҳ���󷵻ط�ҳ����,����flash
 **/
function setFoodPrintItem(obj)
{
	getMovie("main").setFoodPrintItem(obj);
}

//=============================== end ===================================

//========================== ������ visible ���� =======================
/**
 * @ param			: bo(Boolean)
 * @ return			: void
 * @ description	: ���� ��������ʾ������
 **/
function onToolsVisible(bo)
{
	getMovie("main").onToolsVisible(bo);
}
/**
 * @ param			: bgURL(String)
 * @ return			: void
 * @ description	: ���ı���ͼƬ
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
 * @ param			: movieName(Ҫ�Ҷ�����)
 * @ return			: void
 * @ description	: �js��flash��ͨ�Ļ���
 **/
function getMovie(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName]
	}else{
		return document[movieName]
	}
} 
//========================== end ===========================