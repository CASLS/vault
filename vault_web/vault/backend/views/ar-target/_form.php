<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use common\models\Task;

/* @var $this yii\web\View */
/* @var $model common\models\ArTarget */
/* @var $form yii\widgets\ActiveForm */

$uniqueId = str_replace("-","",\Yii::$app->security->generateRandomString(8));

?>

<div class="ar-target-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->hiddenInput()->label(false); ?>
    
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'task_id')->dropDownList(
                                            ArrayHelper::map(
                                                (isset($quest)) ? Task::find()->where(['quest_id'=>$quest->id])->orderBy('quest_id')->all() : Task::find()->orderBy('quest_id')->all(),
                                                "id", 
                                                function($m){
                                                     return $m->quest_id . ": " . $m->title;                   
                                                }
                                            )
                                        ) ?>

    <?//= $form->field($model, 'media_id')->textInput() ?>
    <?= $form->field($model, 'media_id')->hiddenInput()->label(false) ?>
    <div id="mediaUpload_<?= $uniqueId; ?>" class="well" style="margin-top:20px;">
        <h4>Media
        <button type="button" class="btn btn-primary btn-sm" onclick="$('#mediaDropzone_<?= $uniqueId; ?>').toggle();">Add Files</button>
        </h4>
        <?php 
        if($model->isNewRecord == false){
            if($model->media != NULL){
                $media = $model->media;
                echo "<div id='" . $model->media_id . "' class='mediaWrapper'>";
                echo "<button class='removeARMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
                echo Html::img($media->url,["style"=>"max-width:200px;"]);
                echo "</div>";	
            }
    } 
    ?>
    <?php 
        $allowedFileTypes = [
                        'jpg','png','jpeg', //images
                    ];
    ?>
        <p class="light-text" style="display:block;margin-bottom:10px;">Allowed file types: <b><?= implode(", ", $allowedFileTypes); ?></b></p>
        <p style="" class="light-text">Max size: <b>500MB</b></p>
        <i>This image will be used as the image to be recognized during the AR Session.  If you upload a new image it will replace the current one.</i>
        <div id="mediaDropzone_<?= $uniqueId; ?>" style="display:none;">
            <?= $form->field($model, 'mediaFile',["options"=>["style"=>"background-color: white;"]])->widget(FileInput::classname(), [
                    'options' => [
            // 	    		'accept' => 'image/*, audio/*, video/*, application/*, text/*',
                            'multiple' => false,
                            'id'=>'dropZoneMedia_' . $uniqueId
                    ],
                        'pluginOptions'=>[
                            'required' => false,
                            'maxFileCount'=>1,
                            'maxFileSize'=>500000,
                            'overwriteInitial'=>false,
                            'allowedFileExtensions'=>$allowedFileTypes,
                            'showClose'=>false,
                            'showUpload'=>false,
                            'previewFileType' => 'any'
                        ]
                ])->label(false);?>
        </div>
    </div>

    <?= $form->field($model, 'physical_width')->textInput()->hint('The real-world width, in <b>meters</b>, of the image.') ?>

    <?//= $form->field($model, 'overlay_media_id')->textInput() ?>
    <?= $form->field($model, 'overlay_media_id')->hiddenInput()->label(false) ?>
    <div id="overlayMediaUpload_<?= $uniqueId; ?>" class="well" style="margin-top:20px;">
        <h4>Overlay Media
        <button type="button" class="btn btn-primary btn-sm" onclick="$('#overlayMediaDropzone_<?= $uniqueId; ?>').toggle();">Add Files</button>
        </h4>
        <?php 
        if($model->isNewRecord == false){
            if($model->overlayMedia != NULL){
                $media = $model->overlayMedia;
                echo "<div id='" . $model->overlay_media_id . "' class='mediaWrapper'>";
                echo "<button class='removeOverlayMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
                echo Html::img($media->url,["style"=>"max-width:200px;"]);
                echo "</div>";	
            }
    } 
    ?>
    <?php 
        $allowedFileTypes = [
                        'jpg','png','jpeg','gif' //images
                    ];
    ?>
        <p class="light-text" style="display:block;margin-bottom:10px;">Allowed file types: <b><?= implode(", ", $allowedFileTypes); ?></b></p>
        <p style="" class="light-text">Max size: <b>500MB</b></p>
        <i>This image will be used as the image to be overlayed on top of the AR target image once it is recognized.  If you upload a new image it will replace the current one.</i>
        <div id="overlayMediaDropzone_<?= $uniqueId; ?>" style="display:none;">
            <?= $form->field($model, 'overlayMediaFile',["options"=>["style"=>"background-color: white;"]])->widget(FileInput::classname(), [
                    'options' => [
            // 	    		'accept' => 'image/*, audio/*, video/*, application/*, text/*',
                            'multiple' => false,
                            'id'=>'dropZoneOverlayMedia_' . $uniqueId
                    ],
                        'pluginOptions'=>[
                            'required' => false,
                            'maxFileCount'=>1,
                            'maxFileSize'=>500000,
                            'overwriteInitial'=>false,
                            'allowedFileExtensions'=>$allowedFileTypes,
                            'showClose'=>false,
                            'showUpload'=>false,
                            'previewFileType' => 'any'
                        ]
                ])->label(false);?>
        </div>
    </div>

    <?= $form->field($model, 'audio_media_id')->hiddenInput()->label(false) ?>
    <div id="audioMediaUpload_<?= $uniqueId; ?>" class="well" style="margin-top:20px;">
        <h4>Audio Media
        <button type="button" class="btn btn-primary btn-sm" onclick="$('#audioMediaDropzone_<?= $uniqueId; ?>').toggle();">Add Files</button>
        </h4>
        <?php 
        if($model->isNewRecord == false){
            if($model->audioMedia != NULL){
                $media = $model->audioMedia;
                echo "<div id='" . $model->audio_media_id . "' class='mediaWrapper'>";
                echo "<button class='removeAudioMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
                echo "<audio controls><source src='" . $media->url . "' type='" . $media->mime_type . "'></audio>";
                echo "</div>";	
            }
    } 
    ?>
    <?php 
        $allowedFileTypes = [
                        'mp3','mp4','m4a', //audio
                    ];
    ?>
        <p class="light-text" style="display:block;margin-bottom:10px;">Allowed file types: <b><?= implode(", ", $allowedFileTypes); ?></b></p>
        <p style="" class="light-text">Max size: <b>500MB</b></p>
        <i>This audio file will be played when the AR target image is recognized.  If you upload a new audio file it will replace the current one.  Please be aware that the entire audio file will be played, so audio clips should not exceed 30 seconds.</i>
        <div id="audioMediaDropzone_<?= $uniqueId; ?>" style="display:none;">
            <?= $form->field($model, 'audioMediaFile',["options"=>["style"=>"background-color: white;"]])->widget(FileInput::classname(), [
                    'options' => [
            // 	    		'accept' => 'image/*, audio/*, video/*, application/*, text/*',
                            'multiple' => false,
                            'id'=>'dropZoneaudioMedia_' . $uniqueId
                    ],
                        'pluginOptions'=>[
                            'required' => false,
                            'maxFileCount'=>1,
                            'maxFileSize'=>500000,
                            'overwriteInitial'=>false,
                            'allowedFileExtensions'=>$allowedFileTypes,
                            'showClose'=>false,
                            'showUpload'=>false,
                            'previewFileType' => 'any'
                        ]
                ])->label(false);?>
        </div>
    </div>

    <?= $form->field($model, 'overlay_physical_width')->textInput()->hint('The real-world width, in <b>meters</b>, of the image.') ?>

    <?= $form->field($model, 'should_auto_close')->dropDownList(["0"=>"No","1"=>"Yes"])->hint("Whether the AR session should be ended automatically when this AR target is recognized.") ?>

    <?= $form->field($model, 'close_after')->textInput()->label("Close After")->hint("The amount of time, in <b>seconds</b>, to wait before auto-closing. The default is <b>3 seconds</b> if no value is provided.") ?>

    <?//= $form->field($model, 'created_at')->textInput() ?>

    <?//= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(($model->isNewRecord) ? 'Create' : 'Update', ['class' => 'btn btn-success']) ?>
        <?php if($model->isNewRecord == false){ ?>
        <?= Html::a('Delete', ['/ar-target/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

