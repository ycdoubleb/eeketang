$(function () {
    var $mlNav = $('.ml-nav');
    var url = '';
    var urlIndex = '';
    var anchorIndex = '';
    var moveSliderRightID;
    $('#dowebok').fullpage({
        verticalCentered: false,
        navigation: true,
        easing: 'easingOutExpo',
        anchors: ['page1', 'page2', 'page3', 'page4', 'page5', 'page6', 'page7'],
        navigationTooltips: ['品牌', '广州特色资源库', '央馆名师微课库', '人教社优秀案例库', '服务', '诚招代理', '成功案例'],
        afterRender: function () {

        },
        onLeave: function (index, nextIndex, direction) {
            clearInterval(moveSliderRightID);
            if (index === 1) {
                page1Out();
            } else if (index === 2) {
                //第二页
                page2Out();
                page2_1Out();
                page2_2Out();
                page2_3Out();
            } else if (index === 3) {
                //第三页
                page3Out();
                page3_1Out();
                page3_2Out();
            } else if (index === 4) {
                //第四页
                page4Out();
            } else if (index === 5) {
                //第五页
                page5Out();
                page5_1Out();
                page5_2Out();
            }else if (index === 7) {
                //第七页
                huojianOut();
            }
        },
        afterLoad: function (anchorLink, index) {
            url = window.location.href;
            urlIndex = url.substring(url.lastIndexOf("#"));
            clearInterval(moveSliderRightID);
            if (index === 1) {
                page1In();
            } else if (index === 2) {
                //第二页
                $.fn.fullpage.moveTo(2, 0);
                page2In();
                if (urlIndex === "#" + anchorLink) {
                    page2_1In();
                } else if (urlIndex === "#" + anchorLink + "/" + 1) {
                    page2_2In();
                } else if (urlIndex === "#" + anchorLink + "/" + 2) {
                    page2_3In();
                }
            } else if (index === 3) {
                //第三页
                $.fn.fullpage.moveTo(3, 0);
                page3In();
                if (urlIndex === "#" + anchorLink) {
                    page3_1In();
                } else if (urlIndex === "#" + anchorLink + "/" + 1) {
                    page3_2In();
                }
            } else if (index === 4) {
                //第四页
                page4In();
            } else if (index === 5) {
                //第五页
                $.fn.fullpage.moveTo(5, 0);
                page5In();
                if (urlIndex === "#" + anchorLink) {
                    page5_1In();
                } else if (urlIndex === "#" + anchorLink + "/" + 1) {
                    page5_2In();
                }
            }else if (index === 7) {
                //第七页
                huojianIn();
            }
        },

        onSlideLeave: function (anchorLink, index, slideIndex, direction) {
            clearInterval(moveSliderRightID);
            if (index === 1) {
                page1Out();
            } else if (index === 2) {
                //第二页
                if (slideIndex === 0) {
                    page2_1Out1();
                } else if (slideIndex === 1) {
                    page2_2Out2();
                } else if (slideIndex === 2) {
                    page2_3Out();
                }
            } else if (index === 3) {
                //第三页
                if (slideIndex === 0) {
                    page3_1Out();
                } else if (slideIndex === 1) {
                    page3_2Out();
                }
            } else if (index === 4) {
                //第四页
                if (slideIndex === 0) {
                    page4Out();
                }
            } else if (index === 5) {
                //第五页
                if (slideIndex === 0) {
                    page5_1Out();
                } else if (slideIndex === 1) {
                    page5_2Out();
                }
            }
        },
        afterSlideLoad: function (anchorLink, index, slideIndex, direction) {
            clearInterval(moveSliderRightID);
            moveSliderRightID = setInterval(function () {
                $.fn.fullpage.moveSlideRight();
            }, 5000);
            if (index === 1) {
                page1In();
            } else if (index === 2) {
                //第二页
                if (slideIndex === 0) {
                    page2_1In();
                } else if (slideIndex === 1) {
                    page2_2In();
                } else if (slideIndex === 2) {
                    page2_3In();
                }
            } else if (index === 3) {
                //第三页
                if (slideIndex === 0) {
                    page3_1In();
                } else if (slideIndex === 1) {
                    page3_2In();
                }
            } else if (index === 4) {
                //第四页
                if (slideIndex === 0) {
                    page4In();
                }
            } else if (index === 5) {
                //第五页
                if (slideIndex === 0) {
                    page5_1In();
                } else if (slideIndex === 1) {
                    page5_2In();
                }
            }
        }
    });

    //小火箭
    function huojianIn() {
        $('.top').delay(300).animate({right: "50px", bottom: "53px", opacity: 1});
    }
    function huojianOut() {
        $('.top').animate({right: "0", bottom: "53px", opacity: 0});
    }
    //===================================
    // 第一页
    //===================================
    function page1In() {
        $('.but-now').animate({right: "30%", top: "24%", opacity: 1});
        $('.banner-img1').delay(300).animate({left: "0%", opacity: 1});
        $('.banner-img2').animate({left: "30%", opacity: 1});
        $('.banner-img3').animate({right: "30%", opacity: 1});
        $('.banner-img4').delay(300).animate({right: "0%", opacity: 1});
    }

    function page1Out() {
        $('.but-now').animate({right: "30%", top: "0%", opacity: 0});
        $('.banner-img1').animate({left: "-30%", opacity: 0});
        $('.banner-img2').delay(100).animate({left: "0%", opacity: 0});
        $('.banner-img3').delay(100).animate({right: "0%", opacity: 0});
        $('.banner-img4').animate({right: "-30%", opacity: 0});
    }

    //===================================
    // 第二页
    //===================================
    function page2In() {
        $('.guangzhou-img1').animate({left: "-3%", top: "40px", opacity: 1});
        $('.guangzhou-img2').animate({left: "401px", top: "15%", opacity: 1});
        $('.guangzhou-img3').animate({left: "815px", top: "20%", opacity: 1});
        $('.guangzhou3-img6').animate({left: "83%", top: "87%", opacity: 1});
    }

    function page2Out() {
        $('.guangzhou-img1').animate({left: "-3%", top: "-40px", opacity: 0});
        $('.guangzhou-img2').animate({left: "401px", top: "0%", opacity: 0});
        $('.guangzhou-img3').animate({left: "815px", top: "0%", opacity: 0});
        $('.guangzhou3-img6').animate({left: "83%", top: "100%", opacity: 0});
    }
    //===================================
    // 第二页的第一页
    //===================================
    function page2_1In() {
        $('.guangzhou-img4').animate({left: "805px", top: "26%", opacity: 1});
        $('.guangzhou-img5').animate({left: "0px", top: "30%", opacity: 1});
        $('.guangzhou-img6').delay(100).animate({left: "89px", top: "52%", opacity: 1});
        $('.guangzhou-img7').delay(200).animate({left: "257px", top: "53%", opacity: 1});
        $('.guangzhou-img8').delay(300).animate({left: "417px", top: "75%", opacity: 1});
        $('.guangzhou-img9').delay(400).animate({left: "617px", top: "76%", opacity: 1});
    }
    //左右轮播时候用这个
    function page2_1Out1() {
        $('.guangzhou-img4').animate({left: "0px", top: "26%", opacity: 0});
        $('.guangzhou-img5').delay(400).animate({left: "-90px", top: "32%", opacity: 0});
        $('.guangzhou-img6').delay(300).animate({left: "0px", top: "54%", opacity: 0});
        $('.guangzhou-img7').delay(200).animate({left: "100px", top: "55%", opacity: 0});
        $('.guangzhou-img8').delay(100).animate({left: "250px", top: "77%", opacity: 0});
        $('.guangzhou-img9').animate({left: "450px", top: "78%", opacity: 0});
    }
    function page2_1Out() {
        $('.guangzhou-img4').animate({left: "805px", top: "0%", opacity: 0});
        $('.guangzhou-img5').delay(400).animate({left: "-90px", top: "32%", opacity: 0});
        $('.guangzhou-img6').delay(300).animate({left: "0px", top: "54%", opacity: 0});
        $('.guangzhou-img7').delay(200).animate({left: "100px", top: "55%", opacity: 0});
        $('.guangzhou-img8').delay(100).animate({left: "250px", top: "77%", opacity: 0});
        $('.guangzhou-img9').animate({left: "450px", top: "78%", opacity: 0});
    }

    //===================================
    // 第二页的第二页
    //===================================
    function page2_2In() {
        $('.guangzhou2-img4').animate({right: "805px", top: "26%", opacity: 1});
        $('.guangzhou2-img5').animate({right: "0px", top: "30%", opacity: 1});
        $('.guangzhou2-img6').delay(100).animate({right: "89px", top: "52%", opacity: 1});
        $('.guangzhou2-img7').delay(200).animate({right: "257px", top: "53%", opacity: 1});
        $('.guangzhou2-img8').delay(300).animate({right: "417px", top: "75%", opacity: 1});
        $('.guangzhou2-img9').delay(400).animate({right: "617px", top: "76%", opacity: 1});
    }
    //左右轮播时候用这个
    function page2_2Out2() {
        $('.guangzhou2-img4').animate({left: "0px", top: "26%", opacity: 0});
        $('.guangzhou2-img5').delay(400).animate({right: "-90px", top: "32%", opacity: 0});
        $('.guangzhou2-img6').delay(300).animate({right: "0px", top: "54%", opacity: 0});
        $('.guangzhou2-img7').delay(200).animate({right: "100px", top: "55%", opacity: 0});
        $('.guangzhou2-img8').delay(100).animate({right: "250px", top: "77%", opacity: 0});
        $('.guangzhou2-img9').animate({right: "450px", top: "78%", opacity: 0});
    }

    function page2_2Out() {
        $('.guangzhou2-img4').animate({right: "805px", top: "0%", opacity: 0});
        $('.guangzhou2-img5').delay(400).animate({right: "-90px", top: "32%", opacity: 0});
        $('.guangzhou2-img6').delay(300).animate({right: "0px", top: "54%", opacity: 0});
        $('.guangzhou2-img7').delay(200).animate({right: "100px", top: "55%", opacity: 0});
        $('.guangzhou2-img8').delay(100).animate({right: "250px", top: "77%", opacity: 0});
        $('.guangzhou2-img9').animate({right: "450px", top: "78%", opacity: 0});
    }
    //===================================
    // 第二页的第三页
    //===================================
    function page2_3In() {
        $('.guangzhou3-img1').delay(100).animate({left: "100px", top: "66%", opacity: 1});
        $('.guangzhou3-img2').delay(200).animate({left: "177px", top: "63%", opacity: 1});
        $('.guangzhou3-img3').delay(300).animate({left: "100px", top: "45%", opacity: 1});
        $('.guangzhou3-img4').delay(400).animate({left: "177px", top: "42%", opacity: 1});
        $('.guangzhou3-img5').delay(500).animate({left: "100px", top: "24%", opacity: 1});
        $('.guangzhou3-img7').delay(600).animate({left: "373px", top: "35%", opacity: 1});
    }
    function page2_3Out() {
        $('.guangzhou3-img1').delay(600).animate({left: "100px", top: "99%", opacity: 0});
        $('.guangzhou3-img2').delay(500).animate({left: "177px", top: "99%", opacity: 0});
        $('.guangzhou3-img3').delay(400).animate({left: "100px", top: "99%", opacity: 0});
        $('.guangzhou3-img4').delay(300).animate({left: "177px", top: "99%", opacity: 0});
        $('.guangzhou3-img5').delay(200).animate({left: "100px", top: "99%", opacity: 0});
        $('.guangzhou3-img7').delay(100).animate({left: "1373px", top: "35%", opacity: 0});
    }

    //===================================
    // 第三页
    //===================================
    function page3In() {
        $('.yangguan-img1').animate({left: "-3%", top: "40px", opacity: 1});
        $('.yangguan-img2').animate({left: "413px", top: "14%", opacity: 1});
        $('.yangguan-img3').animate({left: "804px", top: "18%", opacity: 1});
        $('.yangguan2-img5').animate({left: "85%", top: "92%", opacity: 1});
    }
    function page3Out() {
        $('.yangguan-img1').animate({left: "-3%", top: "0px", opacity: 0});
        $('.yangguan-img2').animate({left: "413px", top: "0%", opacity: 0});
        $('.yangguan-img3').animate({left: "804px", top: "0%", opacity: 0});
        $('.yangguan2-img5').animate({left: "85%", top: "99%", opacity: 0});
    }
    //===================================
    // 第三页的第一页
    //===================================
    function page3_1In() {
        $('.yangguan-img4').delay(300).animate({left: "130px", top: "29%", opacity: 1});
        $('.yangguan-img5').delay(500).animate({left: "720px", top: "61%", opacity: 1});
        $('.yangguan-img6').delay(300).animate({left: "145px", top: "61%", opacity: 1});
        $('.yangguan-img7').delay(500).animate({left: "760px", top: "28%", opacity: 1});

    }
    function page3_1Out() {
        $('.yangguan-img4').delay(500).animate({left: "0px", top: "29%", opacity: 0});
        $('.yangguan-img5').delay(100).animate({left: "900px", top: "61%", opacity: 0});
        $('.yangguan-img6').delay(500).animate({left: "0px", top: "61%", opacity: 0});
        $('.yangguan-img7').delay(300).animate({left: "900px", top: "28%", opacity: 0});
    }
    //===================================
    // 第三页的第2页
    //===================================
    function page3_2In() {
        $('.yangguan2-img1').delay(300).animate({left: "110px", top: "25%", opacity: 1});
        $('.yangguan2-img2').delay(500).animate({left: "700px", top: "58%", opacity: 1});
        $('.yangguan2-img3').delay(300).animate({left: "90px", top: "61%", opacity: 1});
        $('.yangguan2-img4').delay(500).animate({left: "690px", top: "30%", opacity: 1});
    }
    function page3_2Out() {
        $('.yangguan2-img1').delay(500).animate({left: "0px", top: "25%", opacity: 0});
        $('.yangguan2-img2').delay(300).animate({left: "900px", top: "58%", opacity: 0});
        $('.yangguan2-img3').delay(500).animate({left: "0px", top: "61%", opacity: 0});
        $('.yangguan2-img4').delay(300).delay(400).animate({left: "900px", top: "30%", opacity: 0});
    }

    //===================================
    // 第四页
    //===================================
    function page4In() {
        $('.ren-img1').delay(100).animate({left: "0px", top: "7%", opacity: 1});
        $('.ren-img2').delay(200).animate({left: "415px", top: "14%", opacity: 1});
        $('.ren-img3').delay(300).animate({left: "875px", top: "18%", opacity: 1});
        $('.ren-img4').delay(400).animate({left: "457px", top: "46%", opacity: 1});
        $('.ren-img4-1').delay(700).animate({left: "692px", top: "55%", opacity: 1});
        $('.ren-img4-2').delay(900).animate({left: "927px", top: "64%", opacity: 1});
        $('.ren-img5').delay(700).animate({left: "468px", top: "43%", opacity: 1});
        $('.ren-img6').delay(900).animate({left: "705px", top: "52%", opacity: 1});
        $('.ren-img7').delay(1200).animate({left: "940px", top: "61%", opacity: 1});
        $('.ren-img8').delay(100).animate({left: "80%", top: "89%", opacity: 1});
        $('.ren-img9').delay(400).animate({left: "30px", top: "40%", opacity: 1});
    }
    function page4Out() {
        $('.ren-img1').delay(900).animate({left: "0px", top: "-9%", opacity: 0});
        $('.ren-img2').delay(800).animate({left: "415px", top: "-14%", opacity: 0});
        $('.ren-img3').delay(700).animate({left: "875px", top: "-19%", opacity: 0});
        $('.ren-img4').delay(600).animate({left: "0px", top: "58%", opacity: 0});
        $('.ren-img4-1').delay(400).animate({left: "0px", top: "67%", opacity: 0});
        $('.ren-img4-2').delay(200).animate({left: "0px", top: "76%", opacity: 0});
        $('.ren-img5').delay(400).animate({left: "463px", top: "99%", opacity: 0});
        $('.ren-img6').delay(200).animate({left: "705px", top: "99%", opacity: 0});
        $('.ren-img7').delay(100).animate({left: "940px", top: "99%", opacity: 0});
        $('.ren-img8').delay(900).animate({left: "80%", top: "99%", opacity: 0});
        $('.ren-img9').delay(600).animate({left: "30px", top: "99%", opacity: 0});
    }

    //===================================
    // 第五页
    //===================================
    function page5In() {
        $('.deploy-img1').delay(200).animate({left: "25%", top: "17%", opacity: 1});
        $('.deploy-img6').delay(100).animate({left: "76%", top: "89%", opacity: 1});
        $('.deploy1-img1').delay(100).animate({left: "-3%", top: "5%", opacity: 1});
    }
    function page5Out() {
        $('.deploy-img1').delay(200).animate({left: "25%", top: "0%", opacity: 0});
        $('.deploy-img6').delay(100).animate({left: "76%", top: "99%", opacity: 0});
        $('.deploy1-img1').delay(100).animate({left: "-3%", top: "0%", opacity: 0});
    }
    //===================================
    // 第五页的第一页
    //===================================
    function page5_1In() {
        $('.deploy-img2').delay(300).animate({left: "8%", top: "30%", opacity: 1});
        $('.deploy-img3').delay(300).animate({left: "43%", top: "28%", opacity: 1});
        $('.deploy-img4').delay(500).animate({left: "17%", top: "64%", opacity: 1});
        $('.deploy-img5').delay(500).animate({left: "65%", top: "64%", opacity: 1});
    }
    function page5_1Out() {
        $('.deploy-img2').delay(100).animate({left: "0%", top: "30%", opacity: 0});
        $('.deploy-img3').delay(100).animate({left: "0%", top: "28%", opacity: 0});
        $('.deploy-img4').delay(100).animate({left: "60%", top: "64%", opacity: 0});
        $('.deploy-img5').delay(100).animate({left: "80%", top: "64%", opacity: 0});
    }
    //===================================
    // 第五页的第2页
    //===================================
    function page5_2In() {
        $('.deploy1-img2').delay(300).animate({left: "14%", top: "25%", opacity: 1});
        $('.deploy1-img3').delay(400).animate({left: "42%", top: "35%", opacity: 1});
        $('.deploy1-img4').delay(600).animate({left: "11%", top: "67%", opacity: 1});
        $('.deploy1-img5').delay(400).animate({left: "62%", top: "52%", opacity: 1});
    }
    function page5_2Out() {
        $('.deploy1-img2').delay(100).animate({left: "99%", top: "25%", opacity: 0});
        $('.deploy1-img3').delay(100).animate({left: "99%", top: "35%", opacity: 0});
        $('.deploy1-img4').delay(100).animate({left: "0%", top: "65%", opacity: 0});
        $('.deploy1-img5').delay(100).animate({left: "0%", top: "52%", opacity: 0});
    }
});