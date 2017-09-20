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
	parent.saveGrade(n);
}
function saveLxzd_subState(n){
	parent.saveLxzd_subState(n);
}
function saveTbkt_subState(n){
	parent.saveTbkt_subState(n);
}
function saveKhcs_subState(n){
	parent.saveKhcs_subState(n);
}
function saveTbkt_Test(n){
	parent.saveTbkt_Test(n);
}
function GetTbkt_TotalNum(n){
	parent.GetTbkt_TotalNum(n);
}
function GetLxzd_TotalNum(n){
	parent.GetLxzd_TotalNum(n);
}
function Getkhcs_TotalNum(n){
	parent.Getkhcs_TotalNum(n);
}
//===================================
function getQinfenFen(n)
{
	GetInformationValue();
    parent.getQinfenFen(n);
}
function saveTacheState(obj){
	GetInformationValue();
	parent.saveTacheState(obj);
}
function saveStateLogInfo(obj){
	GetInformationValue();
	parent.saveStateLogInfo(obj);
}

function saveStudyTime(obj){
	GetInformationValue();
	parent.saveStudyTime(obj);
}
function saveTestInfoRecord(obj){
	GetInformationValue();
	parent.saveTestInfoRecord(obj);
}
function saveScoreRecord(obj){
	GetInformationValue();
	parent.saveScoreRecord(obj);
}
//===================================
function studedEnd(){
	parent.studedEnd();
}

function closeF(){
	parent.closeF();
}  