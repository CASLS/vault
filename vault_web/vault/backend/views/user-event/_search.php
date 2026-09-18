<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\UserEventSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-event-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'event_type') ?>

    <?= $form->field($model, 'event_detail') ?>

    <?= $form->field($model, 'ip') ?>

    <?php // echo $form->field($model, 'browser') ?>

    <?php // echo $form->field($model, 'url') ?>

    <?php // echo $form->field($model, 'referring_url') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
