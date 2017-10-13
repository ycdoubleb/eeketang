//============================================
function getTempletNetPath()
{
	//------------------
	//eg: 
	//------------------
	//var xmlPath = "playConfig.xml";
	//return xmlPath;
	return decodeURIComponent(templetNetPath);
}
/**
 *
 *
 **/
function getStudyConfig()
{
	//------------------
	//eg: 
	//------------------
	//xmlPath = "http://course.tutor.eecn.cn/Demo/mainceshi/manifest.xml";
	//var xmlPath = "manifest.xml";
	return getMovie("main").getStudyConfig();
}
/**
 * 
 */
function getNetPath()
{
	//------------------
	//
	//------------------
	//return "http://course.tutor.eecn.cn/Demo/mainceshi";
	//return "http://course.tutor.eecn.cn/Demo/mainceshi";
	return decodeURIComponent(netpath);
}

//===================================
function GetValue()
{
	return cosdate;
}

function getJY(){
	return parent.getJY();
}

function saveGrade(n){
    //alert("课后测试成绩："+n);
    cosdate.testInfoRecord.theScore=n;
    cosdate.testInfoRecord.MaxScore = n > cosdate.testInfoRecord.MaxScore ? n :"";
    cosdate.ScoreRecord.testScore=n;

    //检查 参与分
    if(cosdate.testInfoRecord.testCount==0){
            if(cosdate.ScoreRecord.studyScore!=0){
                    //当勤奋分大于1并且测试次数不等于0 获取 参与分 100
                    updateJoinScore(100);
            }else{
                    //当勤奋分大于1但没有做个测试的 获取 参与分 50
                    updateJoinScore(50);
            }
    }

    cosdate.testInfoRecord.testCount+=1;
}
function saveLxzd_subState(n){
	
}
function saveTbkt_subState(n){
	
}
function saveKhcs_subState(n){
	
}
function saveTbkt_Test(n){
	
}
function GetTbkt_TotalNum(n){
	
}
function GetLxzd_TotalNum(n){
	
}
function Getkhcs_TotalNum(n){
	
}
//===================================
function getQinfenFen(n)
{   
    /*
    cosdate.ScoreRecord.studyScore=n;
    cosdate.studyTime.studiedTime = Math.round(n/100*cosdate.studyTime.taskTimeLong);
    //检查 参与分
    if(n==1){
            if(cosdate.testInfoRecord.testCount!=0){
                    //当勤奋分大于1并且测试次数不等于0 获取 参与分 100
                    updateJoinScore(100);
            }else{
                    //当勤奋分大于1但没有做个测试的 获取 参与分 50
                    updateJoinScore(50);
            }
    }
    GetInformationValue();
    */
}
function saveTacheState(obj){
    console.log(obj);
    //学习状态 obj 为 tacheState 对象
    try{
        var sourceObj=cosdate.tacheState[obj.tacheName];
        switch(obj.tacheName)
        {
                case "tbkt":	
                        sourceObj.state=obj.state;
                        sourceObj.test=obj.test;
                        sourceObj.subState=obj.subState;
                        break;
                case "lxzd":
                        sourceObj.state=obj.state;
                        sourceObj.subState=obj.subState;
                        break;
                case "pyp":
                        sourceObj.state=obj.state;
                        sourceObj.subState=obj.subState;
                        break;
                case "khcs":
                        sourceObj.state=obj.state;
                        break;
                default:
                        break;
        }
        if(obj.id != ""){
            $.post('/study/api/update-node',{id:obj.id,result:2},function(result){
                if(result['code']!=200){
                    if(console && console.error){
                        console.error('更新环节数据失败：'+result['message']);
                    }
                }
            })
        }
        
        GetInformationValue();
    }catch(e){
        console.error(e);
    }
    
}
function saveStateLogInfo(obj){
    //更新学习记录信息 obj 为 stateLogInfo 对象	
    //alert("saveStateLogInfo: "+obj.stateName + "  "+obj.subStateIndex);
    try
    {
            cosdate.stateLogInfo=obj;
            //console.log(obj);
    }catch(err)
    {
            alert("saveStateLogInfo: "+err);
    }
}

function saveStudyTime(obj){
	GetInformationValue();
}
function saveTestInfoRecord(obj){
	GetInformationValue();
}
function saveScoreRecord(obj){
	GetInformationValue();
}
//===================================
function studedEnd(){
	//parent.studedEnd();
}

function closeF(){
	//parent.closeF();
}  