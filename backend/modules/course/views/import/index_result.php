<?php

use backend\modules\course\assets\CourseImportAssets;
use common\models\course\Course;
use common\models\course\CourseCategory;
use common\models\course\searchs\CourseSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel CourseSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model Course */

$this->title = Yii::t('app', '{Course}{Import}{Result}',[
    'Course' => Yii::t('app', 'Course'),
    'Import' => Yii::t('app', 'Import'),
    'Result' => Yii::t('app', 'Result'),
]);
$validCount = count($courses);
?>
<div class="course-import-index">
    <div>
        <p><?= Html::a(Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-default btn-sm']) ?></p>
        <p>分析总共有<?= $maxCount ?> 条数据，需导入<?= $validCount ?> 条数据，<?= count($repeats) ?> 条重复！</p>
        <?php if(count($repeats)>0): ?>
        <table border="1" class="table table-striped table-bordered">
            <tr>
                <th></th>
                <th>分类</th>
                <th>教材</th>
                <th>学科</th>
                <th>年级</th>
                <th>册数</th>
                <th>单元</th>
                <th>课程名</th>
                <th>课件名</th>
                <?php foreach ($repeats[0]['attr'] as $attr_name => $attr_value): ?>
                <th><?= $attr_name ?></th>
                <?php endforeach; ?>
            </tr>
            
            <?php foreach ($repeats as $index => $courseData): ?>
            <tr>
                <td><?= $index ?></td>
                <td><?= $courseData['course']['cat_id'];?></td>
                <td><?= $courseData['course']['tm_ver'];?></td>
                <td><?= $courseData['course']['subject_id'];?></td>
                <td><?= $courseData['course']['grade'];?></td>
                <td><?= $courseData['course']['term'];?></td>
                <td><?= $courseData['course']['unit'] ?></td>
                <td><?= isset($courseData['course']['name']) ? $courseData['course']['name'] : ''; ?></td>
                <td><?= $courseData['course']['courseware_name'] ?></td>
                <?php foreach ($repeats[0]['attr'] as $attr_name => $attr_value): ?>
                <td><?= $attr_value ?></td>
                <?php endforeach; ?>
             </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div id="import-log-container"></div>
</div>
<?php 
    $courses = Json::encode($courses);
    $pushURL = Url::toRoute('add-course',true);
    $js = <<<JS
            var _import = new Wskeee.course.Import({
                'pushURL':"$pushURL",
                'maxPost':100
            },$courses);
            _import.push();
JS;
    $this->registerJs($js);
    CourseImportAssets::register($this);
?>
