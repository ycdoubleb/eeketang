
function GetInformationValue()
{
    updateInformation(cosdate);
}

function updateInformation(obj)
{
	getMovie("main").updateInformation(obj);
}

//=============================== end ===============================

//=========================================================
/**
 * @ param			: null
 * @ return			: void
 * @ description	:�
 **/
function getReadNote()
{
	//parent.getReadNote();
}

function getReadingNote(arr)
{
	//getMovie("main").getReadingNote(arr);
}

function sendReadNote(obj)
{
	//parent.sendReadNote(obj);
}
//=============================== end ===================================

//==================================================================

function getFavoriteInfo()
{
	//parent.getFavoriteInfo();
}

function getFavorite(arr)
{
	//getMovie("main").getFavorite(arr);
}

function getSubjectInfo()
{	
	//parent.getSubjectInfo();
}

function getSubject(obj)
{
	//getMovie("main").getSubject(obj);
}

function addFavorite(obj)
{
	//parent.addFavorite(obj);
}

function getNewFavoriteID(id)
{
	//getMovie("main").getNewFavoriteID(id);
}

function controlSubject(obj)
{
	//alert(obj.subStateName+"  "+obj.subStateIndex)
	//getMovie("main").setTbktProgress(obj.subStateName,obj.subStateIndex);
}

function deleteFavorite(id)
{
	parent.deleteFavorite(id);
}
//=============================== end ===================================

//==================================================================
//

function getQueueData()
{
	parent.getQueueData();
}

function getData(obj)
{
	getMovie("main").getData(obj);
}
//=============================== end ===================================

//==================================================================

function getCommunication()
{
	parent.getCommunication();
}

function getInfo(arr)
{
	getMovie("main").getInfo(arr);
}


function setCommunication(str)
{
   	//
	parent.setCommunication(str)
}

function getNewInfo(arr)
{
	getMovie("main").getNewInfo(arr);
}
//=============================== end ===================================

//==================================================================
//

function getFoodPrintItem(obj)
{
	parent.getFoodPrintItem(obj);
}

function setFoodPrintItem(obj)
{
	getMovie("main").setFoodPrintItem(obj);
}

//=============================== end ===================================

//=================================================

function onToolsVisible(bo)
{
	getMovie("main").onToolsVisible(bo);
}

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

function getMovie(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName]
	}else{
		return document[movieName]
	}
} 
//========================== end ===========================