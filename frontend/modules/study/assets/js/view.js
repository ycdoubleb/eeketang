/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function timer(studytime){
    //<<计时器部分
    var ele_timer = document.getElementById("timer");
    var n_sec = studytime; //秒
    //60秒 === 1分
    function timer() {
        return setInterval(function () {
            n_sec++;
            var h = Math.floor(n_sec / 3600);
            var m = Math.floor(n_sec % 3600 / 60);
            var s = Math.floor(n_sec % 60);

            var time = zeor(h) + ":" + zeor(m) + ":" + zeor(s);
            ele_timer.value = time;
        }, 1000);
    }
    var n_timer = timer();
    /**
     * 小于9自动在数字前添加0
     * @param int $value
     */
    function zeor(value){
        return value > 9 ? value + "" : "0" + value;
    }
}
//计时器部分>>

//<<收藏部分    
$("#favorite").click(function () {
    var isAdd = $(this).attr("data-add");
    if (isAdd == "false") {
        $.post("/study/api/favorites", $("#favorites-form").serialize(), function (data) {
            if (data['code'] == 200) {
                $("#favorite").attr("data-add", "true");
                $("#favorite").children("i").removeClass("fa-star-o");
                $("#favorite").children("i").addClass("fa-star");
            }
        });
    } else {
        $.post("/study/api/cancel-favorites", $("#favorites-form").serialize(), function (data) {
            if (data['code'] == 200) {
                $("#favorite").attr("data-add", "false");
                $("#favorite").children("i").removeClass("fa-star");
                $("#favorite").children("i").addClass("fa-star-o");
            }
        });
    }
    return false;
});
//收藏部分>>

//<<点赞部分    
$("#thumbs-up").click(function () {
    var isAdd = $(this).attr("data-add");
    if (isAdd == "false") {
        $.post("/study/api/course-appraise", $("#thumbs-up-form").serialize(), function (r) {
            if (r['code'] == 200) {
                $("#thumbs-up").attr("data-add", "true");
                $("#thumbs-up").children("i").removeClass("fa-thumbs-o-up");
                $("#thumbs-up").children("i").addClass("fa-thumbs-up");
                $("#Course-zan_count").val(r['data']['number']);
                $(".thumbs-up>font").text(r['data']['number']);
            }
        });
    } else {
        $.post("/study/api/cancel-course-appraise", $("#thumbs-up-form").serialize(), function (r) {
            if (r['code'] == 200) {
                $("#thumbs-up").attr("data-add", "false");
                $("#thumbs-up").children("i").removeClass("fa-thumbs-up");
                $("#thumbs-up").children("i").addClass("fa-thumbs-o-up");
                $("#Course-zan_count").val(r['data']['number']);
                $(".thumbs-up>font").text(r['data']['number']);
            }
        });
    }
    return false;
});
//点赞部分>>

//<<分享模块
window._bd_share_config = {"common": {"bdSnsKey": {},
        "bdText": "", "bdMini": "2", "bdMiniList": false, "bdPic": "",
        "bdStyle": "0", "bdSize": "24"}, "share": {"bdSize": 16}};
with (document)
    0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
//分享模块>>


    