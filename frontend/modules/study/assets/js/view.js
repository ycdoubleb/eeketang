/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//<<计时器部分
var ele_timer = document.getElementById("timer");
var n_sec = 0; //秒
var n_min = 0; //分

//60秒 === 1分
function timer() {
    return setInterval(function () {

        var str_sec = n_sec;
        var str_min = n_min;
        if (n_sec < 10) {
            str_sec = "0" + n_sec;
        }
        if (n_min < 10) {
            str_min = "0" + n_min;
        }

        var time = str_min + ":" + str_sec;
        ele_timer.value = time;
        n_sec++;
        if (n_sec > 59) {
            n_sec = 0;
            n_min++;
        }
    }, 1000);
}

var n_timer = timer();
//计时器部分>>

//<<分享模块
window._bd_share_config = {"common": {"bdSnsKey": {},
        "bdText": "", "bdMini": "2", "bdMiniList": false, "bdPic": "",
        "bdStyle": "0", "bdSize": "24"}, "share": {"bdSize": 16}};
with (document)
    0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
//分享模块>>
    
    
    