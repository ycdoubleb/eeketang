(function (win, $) {
    var Wskeee = win.Wskeee = win.Wskeee || {};
    //-----------------------------------------------------------------------------
    //
    //XMLLoader
    //
    //-----------------------------------------------------------------------------
    var XMLLoader = function ()
    {
        this.data = null;
        this.xmlhttp = null;
        this.status = '';
        this.statusText = '';
        
        if (window.XMLHttpRequest)
            this.xmlhttp = new XMLHttpRequest();// code for IE7+, Firefox, Chrome, Opera, Safari
        else
            this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");// code for IE6, IE5
    }

    XMLLoader.prototype.load = function (value)
    {
        var xmlhttp = this.xmlhttp;
        xmlhttp.open("GET", value, false);
        xmlhttp.send();
        this.status = xmlhttp.status;
        this.statusText = xmlhttp.statusText;
        this.data = xmlhttp.responseXML;
    }

    XMLLoader.prototype.getNodeValue = function (node, childNodeName)
    {
        var target = node.getElementsByTagName(childNodeName)[0];
        return trim(target["textContent"] != undefined ? target["textContent"] : target["text"]);
    }

    XMLLoader.prototype.getNode = function (node, childNodeName)
    {
        return node.getElementsByTagName(childNodeName);
    }

    //-----------------------------------------------------------------------------
    //
    //Course Node Parse
    //
    //-----------------------------------------------------------------------------
    var CoursePathCheck = function (config) {
        this.config = $.extend({
            proxy_server:'http://tt.eeketangback.com/course/coursenode/proxy-getxml',
            web_server: 'http://course.tutor.eecn.cn',
        }, config);
        //所有课程
        this.courses = [];
        //所有课程环节
        this.course_to_nodes = [];
        //当前处理索引
        this.currentIndex = -1;
        //总共需要处理的数量
        this.maxNum = 0;
        //失败数
        this.failNum = 0;
        //xml
        this.loader = new XMLLoader();
    };
    /**
     * 设置课程数据
     * @param {array} courses = [{course_id,course_path}]
     * @returns {void}
     */
    CoursePathCheck.prototype.setCourse = function (courses) {
        this.courses = courses;
        this.maxNum = this.courses.length;
        this.check();
    }

    /**
     * 检查是否还有数据需要处理
     * @returns {undefined}
     */
    CoursePathCheck.prototype.check = function () {
        if (this.currentIndex >= this.maxNum - 1) {
            //处理完成
            if (this.config["finish"]) {
                this.config["finish"].call(this, '完成！');
            }
        } else {
            //处理下一课
            this.currentIndex ++;
            this.loadCourseData(this.currentIndex);
        }
    }
    /**
     * 加载课程数据
     * @param {int} index
     * @returns {void}
     */
    CoursePathCheck.prototype.loadCourseData = function (index) {
        var course = this.courses[index];
        var url = this.config['web_server'] + course['path']+"manifest.xml?rand="+Math.round(Math.random()*10000000);
        this.updateActStatus("准备加载课程数据！");
        this.loader.load(this.config['proxy_server']+'?url='+url);
        if(this.loader.status == 200){
            this.updateActStatus("加载课程数据成功！");
            try{
                this.parseCourseData(this.loader.data);
            }catch(e){
                this.addActLog(course['id'],course['title'],1,0,e.text);
                this.check();
                //console.log(e);
            }
        }else{
            this.updateActStatus("加载课程数据失败！");
            this.addActLog(course['id'],course['title'],0,0,this.loader.statusText);
            this.check();
        }
    }

    /**
     * 分析课程数据
     * @returns {void}
     */
    CoursePathCheck.prototype.parseCourseData = function (xml) {
        this.updateActStatus("分析课程数据！");
        //提取数据  
        var course = this.courses[this.currentIndex];
        var items = xml.getElementsByTagName('item');  
        var resources = xml.getElementsByTagName('resource');  
        var needCheckResources = {};
        
        var item,resourceRef;
        for(var r=0,len=items.length;r<len;r++){
            item = items[r];
            resourceRef = item.getAttribute('resourceRef');
            if(resourceRef){
                needCheckResources[resourceRef] = true;
            }else{
                
            }
        }
        var resource,href,file;
        var reg = new RegExp("[\\u4E00-\\u9FFF]+","g");
        
        for(var i=0,len=resources.length;i<len;i++){
            resource = resources[i];
            //只检查需要的文件
            if(needCheckResources[resource.getAttribute('identifier')]){
                file = resource.children[0];
                href = file.getAttribute('href');
                if(reg.test(href)){
                    this.addActLog(course['id'],course['title'],1,0,"发现中文路径：【"+course['path']+'】【'+href+" : "+file.getAttribute('descriptions')+'】');
                }  
            }
        }
        
        this.updateActStatus("分析课程数据完成！");
        var _this = this;
        setTimeout(function(){
            _this.check();
        },1);
        
    }
    /**
     * 添加课程环节结果
     * @param {string} code
     * @param {string} message
     * @returns {void}
     */
    CoursePathCheck.prototype.addActLog = function (course_id,title,getxmlresult,postresult,message) {
        if (this.config["addActLog"]) {
            this.config["addActLog"].call(this, course_id,title,getxmlresult,postresult,message);
        }
        if(getxmlresult == 0 || postresult == 0)this.failNum++;
        //console.log(course_id,title,getxmlresult,postresult,message);
    }

    /**
     * 更新当前操作状态
     * @returns {void}
     */
    CoursePathCheck.prototype.updateActStatus = function (message) {
        var course = this.courses[this.currentIndex];
        var mes = this.currentIndex+".【" + course['id'] + "】" + "【" + course['title'] + "】" + course['path'] + "【" + message + "】";
        if (this.config["updateActStatus"]) {
            this.config["updateActStatus"].call(this, mes);
        }
    }

    Wskeee.course = Wskeee.course || {};
    Wskeee.course.CoursePathCheck = CoursePathCheck;

})(window, jQuery);


