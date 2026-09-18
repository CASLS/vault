<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Quest;
use common\models\TaskType;
use kartik\file\FileInput;
use common\models\TaskMeta;
use common\models\ArTarget;

/* @var $this yii\web\View */
/* @var $model common\models\Task */
/* @var $form yii\widgets\ActiveForm */

$uniqueId = str_replace("-","",\Yii::$app->security->generateRandomString(8));

?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
	    <div class="col-xs-12 col-sm-6">
			<?php if(!$model->isNewRecord){ ?>
			<?= Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete', ['/task/delete', 'id' => $model->id], [
				'class' => 'btn btn-danger',
				'data' => [
					'confirm' => 'Are you sure you want to delete this Task? This can NOT be undone. All data associated with this task will be deleted.',
					'method' => 'post',
				],
			]) ?>
			<?php } ?>
	    	<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
	
		    <?= $form->field($model, 'quest_id')->dropDownList(ArrayHelper::map(Quest::find()->all(), "id", "name"),[])->label("Quest") ?>
		
		    <?= $form->field($model, 'task_type_id')->dropDownList(ArrayHelper::map(TaskType::find()->all(), "id", "name"),["id"=>"task_type_id-".$uniqueId])->label("Task Type") ?>
		    <div id="taskTypeText-<?= $uniqueId; ?>">
		    
		    </div>
		
		    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
		    
		    <?= $form->field($model, 'body')->textarea(['rows' => 6, 'id'=>'task-body_'.$uniqueId])->label("Prompt") ?>
	
			<?= $form->field($model, 'answer')->textInput(['maxlength' => true])->hint("Answer must be <b>text</b>.  The user's response will be matched with this text to check if it's correct.  The user's response and the answer text will both be lowercased so capitilization doesn't matter.") ?>
			
			<?= $form->field($model, 'auto_complete')->dropDownList(["0"=>"No", "1"=>"Yes"])->hint("Whether or not to mark task as complete upon task opening. If set to Yes, Answer text is ignored.") ?>
			
			<?= $form->field($model, 'is_active')->dropDownList(["0"=>"No", "1"=>"Yes"])->hint("Whether or not the task should show up in the app.") ?>

			<?= $form->field($model, 'sort_order')->textInput()->hint("This value will be used to order the tasks.") ?>
			    
	    </div>
	    <div class="col-xs-12 col-sm-6">
		    <div id="fileUpload_<?= $uniqueId; ?>" class="well" style="margin-top:20px;">
    			<h4>Media Files
    				<button type="button" class="btn btn-primary btn-sm" onclick="$('#mediaFilesDropzone_<?= $model->id; ?>').toggle();">Add Files</button>
    			</h4>
    			<?php 
			    if($model->isNewRecord == false){
			    	if(count($model->taskMedia) > 0 ){
			    		foreach($model->taskMedia as $tm){
							$media = $tm->media;
							echo "<div id='" . $tm->id . "' class='mediaWrapper'>";
								echo "<button class='removeMediaBtn btn btn-xs btn-danger' style='z-index:1000;' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
				   			if($media->type == "image"){
				   				echo Html::img($media->url,["style"=>"max-width:200px;"]);
				   			}else if($media->type == "video"){
				   				echo "<video controls style='max-width:100%;max-height:310px;'>" .
									"<source src='{$media->url}' type='{$media->mime_type}'>" .
								"</video>";
							}else if($media->type == "audio"){
				    			echo "<audio controls style='max-width:85%;'>" . 
									"<source src='{$media->url}' type='{$media->mime_type}'>" . 
								 "</audio>";
							}
							echo "</div>";	
			    		}
			    	}	
			    } 
			    ?>
			<?php 
				$allowedFileTypes = [
		    					'jpg','png','jpeg','gif', //images
		    					'mp3','mp4','mpeg4','mov','m4v', 'm4a', //video/audio
// 		    					'doc','docx','pdf','odt','rtf','txt', //document
// 		    					'ppt','pptx','key','odp', //presentation
// 		    					'xls','xlsx','ods' //spreadsheets
		    				];
			?>
				<p class="light-text" style="display:block;margin-bottom:10px;">Allowed file types: <b><?= implode(", ", $allowedFileTypes); ?></b></p>
				<p style="" class="light-text">Max size: <b>500MB</b></p>
				<i>Media files uploaded here will be shown <b>above</b> the task prompt.  You can upload up to three media files.  Files can be images, audio, or video in the formats specified above.</i>
				<div id="mediaFilesDropzone_<?= $model->id; ?>" style="display:none;">
					<?= $form->field($model, 'mediaFiles[]',["options"=>["style"=>"background-color: white;"]])->widget(FileInput::classname(), [
						    'options' => [
					// 	    		'accept' => 'image/*, audio/*, video/*, application/*, text/*',
						    		'multiple' => true,
						    		'id'=>'dropZone_' . $uniqueId
						    ],
					    		'pluginOptions'=>[
					    			'required' => false,
					    			'maxFileCount'=>3,
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
			
			<?= $form->field($model, 'correct_response_text')->textInput(['maxlength' => true])->hint("This text will be used when the user's response is correct.  If no text is provided in this field we will use a default response.") ?>
			
			<?= $form->field($model, 'correct_response_image_id')->hiddenInput()->label(false) ?>
		    <div id="correctImagefileUpload_<?= $uniqueId; ?>" class="well" style="margin-top:20px;">
		    		<h4>Correct Response Image
		    		<button type="button" class="btn btn-primary btn-sm" onclick="$('#correctImageFilesDropzone_<?= $model->id; ?>').toggle();">Add Files</button>
		    		</h4>
		    		<?php 
			    if($model->isNewRecord == false){
			    		if($model->correctResponseImage != NULL){
							$media = $model->correctResponseImage;
							echo "<div id='" . $model->correct_response_image_id . "' class='mediaWrapper'>";
							echo "<button class='removeCorrectResponseMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
							echo Html::img($media->url,["style"=>"max-width:200px;"]);
							echo "</div>";	
			    		}
			    } 
			    ?>
			<?php 
				$allowedFileTypes = [
		    					'jpg','png','jpeg','gif', //images
		    				];
			?>
				<p class="light-text" style="display:block;margin-bottom:10px;">Allowed file types: <b><?= implode(", ", $allowedFileTypes); ?></b></p>
				<p style="" class="light-text">Max size: <b>500MB</b></p>
				<i>This image will be used if the user's response is correct. If no image is provided we will use a default image.</i>
				<div id="correctImageFilesDropzone_<?= $model->id; ?>" style="display:none;">
					<?= $form->field($model, 'correctImage',["options"=>["style"=>"background-color: white;"]])->widget(FileInput::classname(), [
						    'options' => [
					// 	    		'accept' => 'image/*, audio/*, video/*, application/*, text/*',
						    		'multiple' => false,
						    		'id'=>'dropZoneCorrectImage_' . $uniqueId
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
			
			<?= $form->field($model, 'incorrect_response_text')->textInput(['maxlength' => true])->hint("This text will be used when the user's response is incorrect.  If no text is provided in this field we will use a default response.") ?>
			
			<?= $form->field($model, 'incorrect_response_image_id')->hiddenInput()->label(false) ?>
		    <div id="incorrectImagefileUpload_<?= $uniqueId; ?>" class="well" style="margin-top:20px;">
		    		<h4>Incorrect Response Image
		    		<button type="button" class="btn btn-primary btn-sm" onclick="$('#incorrectImageFilesDropzone_<?= $model->id; ?>').toggle();">Add Files</button>
		    		</h4>
		    		<?php 
			    if($model->isNewRecord == false){
			    		if($model->incorrectResponseImage != NULL){
			    			$media = $model->incorrectResponseImage;
							echo "<div id='" . $model->correct_response_image_id . "' class='mediaWrapper'>";
							echo "<button class='removeIncorrectResponseMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
							echo Html::img($media->url,["style"=>"max-width:200px;"]);
							echo "</div>";
			    		}
			    } 
			    ?>
			<?php 
				$allowedFileTypes = [
		    					'jpg','png','jpeg','gif', //images
		    				];
			?>
				<p class="light-text" style="display:block;margin-bottom:10px;">Allowed file types: <b><?= implode(", ", $allowedFileTypes); ?></b></p>
				<p style="" class="light-text">Max size: <b>500MB</b></p>
				<i>This image will be used if the user's response is incorrect. If no image is provided we will use a default image.</i>
				<div id="incorrectImageFilesDropzone_<?= $model->id; ?>" style="display:none;">
					<?= $form->field($model, 'incorrectImage',["options"=>["style"=>"background-color: white;"]])->widget(FileInput::classname(), [
						    'options' => [
					// 	    		'accept' => 'image/*, audio/*, video/*, application/*, text/*',
						    		'multiple' => false,
						    		'id'=>'dropZoneIncorrectImage_' . $uniqueId
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
	    </div>
	</div>

    <?//= $form->field($model, 'created_at')->textInput() ?>

    <?//= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton((!$model->isNewRecord)?'Update':'Create', ['class' => 'btn btn-success btn-block','onclick'=>'updateCkEditorContent(ckBody_'.$uniqueId.');']) ?>
    </div>

    <?php ActiveForm::end(); ?>

	<?php if($model->isNewRecord == false){ ?>
	<hr/>
	<div class="row">
		<div class="col-xs-12 col-sm-6">
			<h4>Required Tasks</h4>
			<label>Tasks that must be completed before this task is "unlocked".</label>
			<div class="well">
				<?= $this->render('/task/_required-tasks-form',[
							"model"=>$model,
				]); ?>
			</div>
		
			<hr/>
			
			<h4>Task Metadata</h4>
			<?php foreach($model->taskMetas as $taskMeta){ ?>
			<div class="well">
				<?= $this->render("/task-meta/_form",[
						"model"=>$taskMeta
				])?>
			</div>
			<?php } ?>
			<hr/>
			<h5>NEW</h5>
			<div class="well">
				<?php
				$newTaskMeta = new TaskMeta();
				$newTaskMeta->task_id = $model->id;
				?>
				<?= $this->render("/task-meta/_form",[
						"model"=>$newTaskMeta
				])?>
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-6">
			<h4>AR Targets</h4>
			<?php foreach($model->arTargets as $arTarget){ ?>
			<div class="well">
				<?= $this->render("/ar-target/_form",[
						"model"=>$arTarget,
						"quest"=>$model->quest
				])?>
			</div>
			<?php } ?>
			<hr/>
			<h5>NEW AR Target</h5>
			<div class="well">
				<?php
				$newARTarget = new ArTarget();
				$newARTarget->task_id = $model->id;
				?>
				<?= $this->render("/ar-target/_form",[
						"model"=>$newARTarget,
						"quest"=>$model->quest
				])?>
			</div>
		</div>
	</div>
	<?php } ?>
</div>

<script type="text/javascript">

var ckBody_<?= $uniqueId; ?> = null;
$(document).ready(function(){
	ckBody_<?= $uniqueId; ?> = CKEDITOR.replace( 'task-body_<?= $uniqueId; ?>', globalEditorConfig);

	var typeTextArray = {
		1 : "",
		2 : "Don't forget to add the needed meta data for the AR target media ID.",
		3 : "Don't forget to add the needed meta data for the speech recognition locale."
	};
	
	$("#task_type_id-<?= $uniqueId; ?>").change(function(){
		var val = $(this).val();
		var typeText = $(".field-task_type_id-<?= $uniqueId; ?> .help-block");
		
		if(val == 1){ //if text type
			typeText.html(typeTextArray[1]);
		}else if(val == 2){ //if AR target type
			typeText.html(typeTextArray[2]);
		}else if(val == 3){ //if Speech-to-text target type
			typeText.html(typeTextArray[3]);
		}
	});

	<?php if(!$model->isNewRecord){ ?>
	$(".field-task_type_id-<?= $uniqueId; ?> .help-block").html(typeTextArray[<?= $model->task_type_id; ?>]);
	<?php } ?>
});

</script>