<?php

use common\models\course\CourseAttr;
use common\models\course\CourseAttribute;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $course_attr CourseAttr */
/* @var $attribute CourseAttribute */
/* @var $form ActiveForm */
?>

<div class="course-attribute-container">
    <?php foreach ($attributes as $attribute): ?>
        <?php if ($attribute->is_delete == 0): ?>
            <?php
            $input_name = "CourseAtts[$attribute->id][value]";
            $input_id = "course-attribute-$attribute->id";
            $input_value = isset($course_attrs[$attribute->id]) ? $course_attrs[$attribute->id] : null;
            $input_items = $attribute->values !='' ?  explode("\r\n", $attribute->values) : [];
            foreach ($input_items as $key => $value){
                $input_items[$value] = $value;
                unset($input_items[$key]);
            }
            ?>
            <div class="form-group course-attribute-<?= $attribute->id ?> required">

                <?= Html::label($attribute->name, $input_id, ['class' => 'control-label',]) ?>

                <div>
                    <!-- 输入类型：手工录入 -->
                    <?php if ($attribute->input_type == CourseAttribute::INPUT_TYPE_SINGLE): ?>

                        <!-- 类型：唯一 -->
                        <?=
                        Html::textInput($input_name, $input_value, [
                            'id' => $input_id,
                            'class' => 'form-control',
                            'maxlength' => 255,
                            'placeholder' => '请输入值...',
                        ])
                        ?>
                        <!-- 输入类型：多行文本 -->
                    <?php elseif ($attribute->input_type == CourseAttribute::INPUT_TYPE_MULTILINE): ?>

                        <?= Html::textarea($input_name, $input_value, ['rows' => 6,]) ?>
                        <!-- 输入类型：列表选择 -->
                    <?php elseif ($attribute->input_type == CourseAttribute::INPUT_TYPE_LIST): ?>

                        <?php if ($attribute->type == CourseAttribute::TYPE_SINGLE): ?>
                            <!-- 类型：单选 -->
                            <?=
                            Html::dropDownList($input_name, $input_value, $input_items, [
                                'id' => $input_id,
                                'class' => 'form-control',
                                'prompt' => Yii::t('app', 'Select Placeholder'),
                            ])
                            ?>

                        <?php elseif ($attribute->type == CourseAttribute::TYPE_MULTILINE): ?>
                            <!-- 类型：复选 -->
                            <?=
                            Html::checkboxList($input_name, $input_value, $input_items, [
                                'id' => $input_id,
                                'itemOptions' => [
                                    'labelOptions' => [
                                        'style' => [
                                            'margin-right' => '30px',
                                            'margin-top' => '5px'
                                        ]
                                    ]
                                ],
                            ])
                            ?>

                        <?php endif; ?>
                    <?php endif; ?>

                    <?= Html::hiddenInput("CourseAtts[$attribute->id][sort_order]", $attribute->sort_order) ?>

                </div>

            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<script type="text/javascript">

</script>
