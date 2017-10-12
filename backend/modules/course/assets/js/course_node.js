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
    var CourseParse = function (config) {
        this.config = $.extend({
            proxy_server:'http://tt.eeketangback.com/course/coursenode/proxy-getxml',
            save_path:"http://tt.eeketangback.com/course/coursenode/save-coursenode",
            web_server: 'http://course.tutor.eecn.cn',
            max_post_num: 10, //一次上专几课数据
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
    CourseParse.prototype.setCourse = function (courses) {
        this.courses = courses;
        this.maxNum = this.courses.length;
        this.check();
    }

    /**
     * 检查是否还有数据需要处理
     * @returns {undefined}
     */
    CourseParse.prototype.check = function () {
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
    CourseParse.prototype.loadCourseData = function (index) {
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
    CourseParse.prototype.parseCourseData = function (xml) {
        this.updateActStatus("分析课程数据！");
        //提取数据  
        var course = this.courses[this.currentIndex];
        var types = {xqzd:1,tbkt:2,lxzd:3,pyp:4,khcs:5};
        var orgs = xml.getElementsByTagName('organizations')[0];  
        var org,sign;
        var node,snode;
        this.course_to_nodes = [];
        for(var i=0,len=orgs.children.length;i<len;i++){
            org = orgs.children[i];
            sign = org.getAttribute('sign');
            node = this.parseNode(org,1,course['id'],null,types[sign]);
            if(node['id'].lenght<10){
                throw new Error('ID太短！');
                return;
            }
            if(sign == 'tbkt' || sign == 'lxzd' || sign == 'khcs' || sign == 'pyp' || sign == 'xqzd'){
                this.course_to_nodes.push(node);
                for(var j=0,jlen=org.children.length;j<jlen;j++){
                    snode = this.parseNode(org.children[j],2,course['id'],node.id,0);
                    this.course_to_nodes.push(snode);
                    if(sign == 'khcs' && j==0){
                        snode['title'] = '标准测试';
                        break;
                    }
                }
            }
        }
        
        this.updateActStatus("分析课程数据完成！");
        this.postCourseNode(this.course_to_nodes);
    }
    
    /**
     * 生成对象数据
     * @param {Dom} node
     * @param {int} level
     * @param {int} course_id
     * @param {string} parent_id
     * @param {int} type
     * @returns {array}
     */
    CourseParse.prototype.parseNode = function(node,level,course_id,parent_id,type){
        return {
            title:node.getAttribute('title'),
            sign:node.getAttribute('sign'),
            id:node.getAttribute('identifier'),
            level:level,
            course_id:course_id,
            parent_id:parent_id,
            type:type,
        };
    }

    /**
     * 上传课程数据
     * @param {array} nodes 课程环节数据
     * @returns {void}
     */
    CourseParse.prototype.postCourseNode = function (nodes) {
        this.updateActStatus("开始上传课程数据！");
        var self = this;
        $.post(this.config['save_path'],JSON.stringify({nodes:nodes}),function(result){
            self.updateActStatus("上传课程数据完成！");
            var course = self.courses[self.currentIndex];
            self.addActLog(course["id"],course['title'],1,result['code'] == 200 ? 1 : 0,result['message']);
            self.check();
        });
    }
    /**
     * 添加课程环节结果
     * @param {string} code
     * @param {string} message
     * @returns {void}
     */
    CourseParse.prototype.addActLog = function (course_id,title,getxmlresult,postresult,message) {
        if (this.config["addActLog"]) {
            this.config["addActLog"].call(this, course_id,title,getxmlresult,postresult,message);
        }
        if(getxmlresult == 0 || postresult == 0)this.failNum++;
        console.log(course_id,title,getxmlresult,postresult,message);
    }

    /**
     * 更新当前操作状态
     * @returns {void}
     */
    CourseParse.prototype.updateActStatus = function (message) {
        var course = this.courses[this.currentIndex];
        var mes = this.currentIndex+".【" + course['id'] + "】" + "【" + course['title'] + "】" + course['path'] + "【" + message + "】";
        if (this.config["updateActStatus"]) {
            this.config["updateActStatus"].call(this, mes);
        }
    }

    Wskeee.course = Wskeee.course || {};
    Wskeee.course.CourseParse = CourseParse;

})(window, jQuery);


