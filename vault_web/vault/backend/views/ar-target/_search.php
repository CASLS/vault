<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ArTargetSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ar-target-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'media_id') ?>

    <?= $form->field($model, 'physical_width') ?>

    <?php // echo $form->field($model, 'overlay_media_id') ?>

    <?php // echo $form->field($model, 'overlay_physical_width') ?>

    <?php // echo $form->field($model, 'audio_media_id') ?>

    <?php // echo $form->field($model, 'should_auto_close') ?>

    <?php // echo $form->field($model, 'close_after') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
