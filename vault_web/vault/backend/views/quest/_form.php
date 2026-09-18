<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use Endroid\QrCode\QrCode;

/* @var $this yii\web\View */
/* @var $model common\models\Quest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="quest-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_active')->dropDownList(["0"=>"No", "1"=>"Yes"])->hint("Whether or not the task list (quest) should show up in the app.") ?>

    <?//= $form->field($model, 'created_at')->textInput() ?>

    <?//= $form->field($model, 'updated_at')->textInput() ?>

    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <label>Quest Media</label>
            <br/>

            <?= $form->field($model, 'media_id')->hiddenInput()->label(false) ?>
            <div class="form-inline">
                <!-- <label>OR</label> -->
                <?= $form->field($model, 'questMedia')->fileInput()->label(false) ?>
            </div>
            <div id="quest-media">
            <?php 
            if($model->isNewRecord == false){
                if($model->media != NULL){
                    $media = $model->media;
                    echo "<div id='" . $media->id . "' class='mediaWrapper' style='margin-bottom:20px;'>";
                        echo "<button class='removeQuestMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
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
            ?>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6">
            <label>Paging Image</label>
            <br/>
            <?= $form->field($model, 'paging_image_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'pagingImageMedia')->fileInput()->label(false) ?>
            <?php 
            if($model->isNewRecord == false){
                if($model->pagingImage != NULL){
                    $media = $model->pagingImage;
                    echo "<div id='" . $media->id . "' class='mediaWrapper' style='margin-bottom:20px;'>";
                        echo "<button class='removePagingImageMediaBtn btn btn-xs btn-danger' type='button'><span class='glyphicon glyphicon-trash'></span></button>";
                    if($media->type == "image"){
                        echo Html::img($media->url,["style"=>"max-width:200px;"]);
                    }
                    echo "</div>";	
                }	
            } 
            ?>
        </div>
    </div>

    <hr/>
    <?= $form->field($model, 'code')->textInput(['maxlength' => true])->label("Basic Start Code")->hint("This must be unique. Changing this field will change the QR Start Code below.") ?> 
    <?php 
    $qrCode = new QrCode($model->code);
    ?>
    <label>QR Start Code</label><br/>
    <img src="<?php echo $qrCode->writeDataUri(); ?>" style="width:200px;"/>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-block btn-success','onclick'=>'updateCkEditorContent(ckDescription);']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">

var ckDescription = null;
$(document).ready(function(){
	ckDescription = CKEDITOR.replace( 'quest-description', globalEditorConfig);

    $(".removeQuestMediaBtn").click(function (e) {
        var media_id = $(this).parent().attr("id");
		var mediaWrapper = $(this).parent();
		$.ajax({
			method: "POST",
			url: "/quest/remove-media",
			data: {media_id: media_id, quest_id: <?= $model->id; ?> }
		})
		 .done(function(json){
			if(json.returnCode == 1){
				console.log("error: " + json.returnCodeDescription);
			}else{
                $("#quest-media_id").val(""); //empty the media_id in the form.
				mediaWrapper.slideUp(500, function(){
					mediaWrapper.remove();
				});
			}
		 });
    });

    $(".removePagingImageMediaBtn").click(function (e) { 
        var media_id = $(this).parent().attr("id");
		var mediaWrapper = $(this).parent();
		$.ajax({
			method: "POST",
			url: "/quest/remove-media",
			data: {media_id: media_id, quest_id: <?= $model->id; ?> }
		})
		 .done(function(json){
			if(json.returnCode == 1){
				console.log("error: " + json.returnCodeDescription);
			}else{
                $("#quest-paging_image_id").val(""); //empty the media_id in the form.
				mediaWrapper.slideUp(500, function(){
					mediaWrapper.remove();
				});
			}
		 });
    });
});

</script>
