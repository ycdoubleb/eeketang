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
        navigationTooltips: ['品牌', '学科培优', '素质提升', '学科辅导', '服务', '诚招代理', '成功案例'],
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
            } else if (index === 3) {
                //第三页
                page3Out();
                page3_1Out();
            } else if (index === 4) {
                //第四页
                page4Out();
            } else if (index === 5) {
                //第五页
                page5Out();
                page5_1Out();
                page5_2Out();
            } else if (index === 7) {
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
                }
            } else if (index === 3) {
                //第三页
                $.fn.fullpage.moveTo(3, 0);
                page3In();
                if (urlIndex === "#" + anchorLink) {
                    page3_1In();
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
            } else if (index === 7) {
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
                    page2_1Out();
                }
            } else if (index === 3) {
                //第三页
                if (slideIndex === 0) {
                    page3_1Out();
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
                }
            } else if (index === 3) {
                //第三页
                if (slideIndex === 0) {
                    page3_1In();
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
        $('.guangzhou-img2').animate({left: "401px", top: "85px", opacity: 1});
        $('.guangzhou-img3').animate({left: "880px", top: "120px", opacity: 1});
        $('.guangzhou3-img6').animate({left: "83%", top: "87%", opacity: 1});
    }

    function page2Out() {
        $('.guangzhou-img1').animate({left: "-3%", top: "-40px", opacity: 0});
        $('.guangzhou-img2').animate({left: "401px", top: "0%", opacity: 0});
        $('.guangzhou-img3').animate({left: "880px", top: "0%", opacity: 0});
        $('.guangzhou3-img6').animate({left: "83%", top: "100%", opacity: 0});
    }
    //===================================
    // 第二页的内容区域
    //===================================
    function page2_1In() {
        $('.guangzhou-img4').delay(100).animate({left: "560px", top: "167px", opacity: 1});
        $('.guangzhou-font1').delay(700).animate({left: "740px", top: "215px", opacity: 1});
        $('.guangzhou-img6_1').delay(400).animate({left: "690px", top: "237px", opacity: 1});
        $('.guangzhou-img5').delay(1000).animate({left: "720px", top: "262px", opacity: 1});
        $('.guangzhou2-img4').delay(100).animate({left: "528px", top: "314px", opacity: 1});
        $('.guangzhou-font2').delay(700).animate({left: "420px", top: "368px", opacity: 1});
        $('.guangzhou2-img6').delay(400).animate({left: "345px", top: "390px", opacity: 1});
        $('.guangzhou-card2').delay(1000).animate({left: "155px", top: "420px", opacity: 1});
        $('.guangzhou3-img4').delay(100).animate({left: "560px", top: "461px", opacity: 1});
        $('.guangzhou-font3').delay(700).animate({left: "725px", top: "514px", opacity: 1});
        $('.guangzhou-img6').delay(400).animate({left: "695px", top: "535px", opacity: 1});
        $('.guangzhou-img9').delay(1000).animate({left: "710px", top: "560px", opacity: 1});
    }
    function page2_1Out() {
        $('.guangzhou-img4').delay(400).animate({left: "560px", top: "0%", opacity: 0});
        $('.guangzhou-font1').delay(400).animate({left: "730px", top: "0%", opacity: 0});
        $('.guangzhou-img6_1').delay(400).animate({left: "690px", top: "0%", opacity: 0});
        $('.guangzhou-img5').delay(400).animate({left: "720px", top: "0%", opacity: 0});
        $('.guangzhou2-img4').delay(400).animate({left: "0px", top: "51.5%", opacity: 0});
        $('.guangzhou-font2').delay(400).animate({left: "0px", top: "57%", opacity: 0});
        $('.guangzhou2-img6').delay(400).animate({left: "0px", top: "60%", opacity: 0});
        $('.guangzhou-card2').delay(400).animate({left: "0px", top: "65%", opacity: 0});
        $('.guangzhou3-img4').delay(100).animate({left: "990px", top: "77%", opacity: 0});
        $('.guangzhou-font3').delay(100).animate({left: "990px", top: "82%", opacity: 0});
        $('.guangzhou-img6').delay(100).animate({left: "990px", top: "85%", opacity: 0});
        $('.guangzhou-img9').delay(100).animate({left: "990px", top: "90%", opacity: 0});
    }

    //===================================
    // 第三页
    //===================================
    function page3In() {
        $('.yangguan-img1').animate({left: "-3%", top: "40px", opacity: 1});
        $('.yangguan-img2').animate({left: "401px", top: "95px", opacity: 1});
        $('.yangguan-img3').animate({left: "880px", top: "130px", opacity: 1});
        $('.yangguan2-img5').animate({left: "85%", top: "92%", opacity: 1});
    }
    function page3Out() {
        $('.yangguan-img1').animate({left: "-3%", top: "0px", opacity: 0});
        $('.yangguan-img2').animate({left: "413px", top: "0%", opacity: 0});
        $('.yangguan-img3').animate({left: "880px", top: "0%", opacity: 0});
        $('.yangguan2-img5').animate({left: "85%", top: "99%", opacity: 0});
    }
    //===================================
    // 第三页的内容区域
    //===================================
    function page3_1In() {
        $('.yangguan-img4').delay(300).animate({left: "150px", top: "180px", opacity: 1});
        $('.yangguan-img5').delay(500).animate({left: "610px", top: "250px", opacity: 1});
        $('.yangguan-img6').delay(300).animate({left: "80px", top: "390px", opacity: 1});
        $('.yangguan-img7').delay(500).animate({left: "885px", top: "540px", opacity: 1});

    }
    function page3_1Out() {
        $('.yangguan-img4').delay(500).animate({left: "0px", top: "180px", opacity: 0});
        $('.yangguan-img5').delay(100).animate({left: "990px", top: "250px", opacity: 0});
        $('.yangguan-img6').delay(500).animate({left: "0px", top: "370px", opacity: 0});
        $('.yangguan-img7').delay(300).animate({left: "1024px", top: "540px", opacity: 0});
    }

    //===================================
    // 第四页
    //===================================
    function page4In() {
        $('.ren-img1').delay(100).animate({left: "0px", top: "5%", opacity: 1});
        $('.ren-img2').delay(200).animate({left: "415px", top: "14%", opacity: 1});
        $('.ren-img3').delay(300).animate({left: "930px", top: "20%", opacity: 1});
        $('.ren-img8').delay(100).animate({left: "80%", top: "89%", opacity: 1});
        $('.guangzhou3-img1').delay(100).animate({left: "100px", top: "66%", opacity: 1});
        $('.guangzhou3-img2').delay(200).animate({left: "177px", top: "63%", opacity: 1});
        $('.guangzhou3-img3').delay(300).animate({left: "100px", top: "46%", opacity: 1});
        $('.guangzhou3-img4').delay(400).animate({left: "177px", top: "43%", opacity: 1});
        $('.guangzhou3-img5').delay(500).animate({left: "100px", top: "26%", opacity: 1});
        $('.guangzhou3-img7').delay(600).animate({left: "373px", top: "35%", opacity: 1});
    }
    function page4Out() {
        $('.ren-img1').delay(900).animate({left: "0px", top: "-9%", opacity: 0});
        $('.ren-img2').delay(800).animate({left: "415px", top: "-14%", opacity: 0});
        $('.ren-img3').delay(700).animate({left: "930px", top: "-20%", opacity: 0});
        $('.ren-img8').delay(900).animate({left: "80%", top: "99%", opacity: 0});
        $('.guangzhou3-img1').delay(600).animate({left: "100px", top: "99%", opacity: 0});
        $('.guangzhou3-img2').delay(500).animate({left: "177px", top: "99%", opacity: 0});
        $('.guangzhou3-img3').delay(400).animate({left: "100px", top: "99%", opacity: 0});
        $('.guangzhou3-img4').delay(300).animate({left: "177px", top: "99%", opacity: 0});
        $('.guangzhou3-img5').delay(200).animate({left: "100px", top: "99%", opacity: 0});
        $('.guangzhou3-img7').delay(100).animate({left: "1373px", top: "35%", opacity: 0});
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