/**
 * ѧ����Ϣ
 *
 **/
var studentInfo = {
	id : 1234, // id��
	name : "wskeee", // ����
	school : "������ѧ" //ѧУ
};
 /**
 * ���ڻ��������
 * @type  tbkt Ϊͬ�����ã�lxzd: ��ϰָ����khts  �κ���� 
 */
var tacheStateInfo ={
	tbkt : {
		state : 2,//���״̬ 1 :δ��� 2��ѧϰ�� 3:���
		test:"pass",//pass or upPass
		subState : [2,2,2,2,2,2,2]//֪ʶ������״̬���� [2,1,1,1,1] 0 :δ��� 1�����
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
 * ��¼�ϴ�ѧϰ����
 * 
 **/
 
var stateLogInfo = {
	stateName:"tbkt",	// ��ǰѧϰ������
	subStateIndex : -1 	// ��ǰѧϰ���ȵ��ӽ���
};


var smallFoodData=[
   //{name:����,icon:ͼ��·��,readTime:�Ķ�ʱ��};

  ];
/**
 * �μ����ݼ�����
 * @type 
 */
var cosdate = {
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
	},
	smallFoodData:smallFoodData//С��ӡ����
};