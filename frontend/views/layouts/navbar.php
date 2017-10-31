<?php

use common\models\WebUser;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $content string */

//$moduleId = Yii::$app->controller->module->id;   //模块ID
//$menus = Menu::getMenusNavItem(Menu::POSITION_FRONTEND);
//$link = Url::to(['index', 'parent_cat_id' => ArrayHelper::getValue(Yii::$app->request->queryParams, 'parent_cat_id')]);

?>

<?php

NavBar::begin([
    //'brandLabel' => 'My Company',
    //'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse',
    ],
]);

if (Yii::$app->user->isGuest) {
    //$menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
    $menuItems[] = ['label' => Yii::t('app', 'Student Login'), 'url' => ['/site/login', 'role' => WebUser::ROLE_STUDENT]];
    $menuItems[] = ['label' => Yii::t('app', 'Teacher Login'), 'url' => ['/site/login', 'role' => WebUser::ROLE_TEACHER]];
} else {
    foreach ($menus as $item) {
        $menuItems[] = $item;
    }
    $menuItems[] = [
        'label' => Html::img([Yii::$app->user->identity->avatar], [
            'width' => 28, 
            'height' => 28, 
            'class' => 'img-circle', 
            'style' => 'margin-right: 5px;'
        ]).Yii::$app->user->identity->username,
        'url' => ['/user/default/index'],
        'items' => [
            ['label' => Html::a(Html::img([Yii::$app->user->identity->avatar], [
                'class' => 'img-circle avatars-circle', 
            ]), Url::to(['/user/default/index'], true))],
            ['label' => Html::a(Yii::$app->user->identity->real_name, Url::to(['/user/default/index'], true)), 
                'options' => [
                    'class' => 'user-name', 
                ]
            ],
            ['label' => (Yii::$app->user->identity->isRoleStudent()?'学习课程数':'观摩课程数').'<em>'.
                    (!empty($studyLogs['cour_num']) ? $studyLogs['cour_num'] : 0).
                '</em>'.'门',
                'options' => [
                    'class' => 'study-course', 
                ]
            ],
            ['label' => '学校'.'<span>'.'广州实验小学'.'</span>',
                'options' => [
                    'class' => 'user-school', 
                ]
            ],
            ['label' => 
                //如果是学生角色显示年级，否则显示职称
                (Yii::$app->user->identity->isRoleStudent() ?
                    Yii::t('app', 'Grade').'<span>'.Yii::$app->user->identity->profile->getGrade().'</span>' :
                    Yii::t('app', 'Job Title').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp'
                ),
                'options' => [
                    'class' => 'user-identity', 
                ]
            ],
            ['label' => "<i class=\"fa fa-clock-o\"></i> ".
                (!empty($studyLogs['cour_name']) ? Html::a("《{$studyLogs['cour_name']}》",
                    Url::to(['/study/college/view', 'id' => $studyLogs['course_id']]),['title' => '最近观看：'.date('Y-m-d H:i',$studyLogs['upd_at'])]).
                Html::a("<span class=\"keep-look\">"."<i class=\"fa fa-play-circle-o\"></i> ".
                    (Yii::$app->user->identity->isRoleStudent()?'继续学习':'继续观摩').
                "</span>",Url::to(['/study/college/view', 'id' => $studyLogs['course_id']])):"暂无观看记录"), 
                'options' => [
                    'class' => 'last-study',
                ]
            ],
            [
                'label' => '<i class="fa fa-sign-out"></i>'.Yii::t('app', 'Logout'), 
                'url' => ['/site/logout'],
                'linkOptions' => [
                    'data-method' => 'post',
                    'class' => 'logout',
                    'style' => 'text-align: right;'
                ]
            ],
        ],
    ];
}

$label = ArrayHelper::getValue($items, 'label');
echo Html::beginTag('div', ['class' => 'pull-left']);
echo $label;
echo Html::endTag('div');

echo Html::beginTag('div', ['class' => 'pull-right']);
echo Nav::widget([      
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => $menuItems,
    'activateParents' => true,
        //'route' => $route,
]);
echo Html::endTag('div');

NavBar::end();
?>

<?php
$js = <<<JS
    $(".navbar-nav .dropdown>a,.navbar-nav .dropdown>.dropdown-menu").hover(function(){
        $(this).parent("li").addClass("open");
    }, function(){
        $(this).parent("li").removeClass("open");
    });
        
    $(".navbar-nav .dropdown>a").click(function(){
        location.href = $(this).attr("href");
    });
JS;
    $this->registerJs($js, View::POS_READY);
?>
