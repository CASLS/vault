<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TaskMeta;

/* @var $this yii\web\View */
/* @var $model common\models\TaskMeta */
/* @var $form yii\widgets\ActiveForm */

$uniqueId = str_replace("-","",\Yii::$app->security->generateRandomString(8));
?>

<div class="task-meta-form">

    <?php $form = ActiveForm::begin(['options'=>['class'=>'form-inline']]); ?>
    
    <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'task_id')->textInput() ?>

	<div class="clearfix"></div>

    <?= $form->field($model, 'key')->dropDownList([
    		TaskMeta::KEY_LOCALE => TaskMeta::KEY_LOCALE,
			TaskMeta::KEY_AR_TARGET_ID => TaskMeta::KEY_AR_TARGET_ID,
			TaskMeta::KEY_URL => TaskMeta::KEY_URL,
    ],[
    		'id'=>'taskmeta-key_'.$uniqueId
    ]) ?>

	<div class="clearfix"></div>
	
	<div class="form-group" id="taskmeta-value_<?= $uniqueId; ?>-AltGroup" style="margin-bottom: 15px;<?= ($model->isNewRecord || $model->key == TaskMeta::KEY_LOCALE) ? '' : 'display:none;' ?>">
		<label>Value</label>
	<?= Html::dropDownList("value", $model->value,
			[
				"ar-SA"=>"Arabic (Saudi Arabia)",
				"ca-ES"=>"Catalan (Spain)",
				"cs-CZ"=>"Czech (Czech Republic)",
				"da-DK"=>"Danish (Denmark)",
				"de-AT"=>"German (Austria)",
				"de-CH"=>"German (Switzerland)",
				"de-DE"=>"German (Germany)",
				"el-GR"=>"Greek (Greece)",
				"en-AE"=>"English (UAE)",
				"en-AU"=>"English (Australia)",
				"en-CA"=>"English (Canada)",
				"en-GB"=>"English (UK)",
				"en-ID"=>"English (Indonesia)",
				"en-IE"=>"English (Ireland)",
				"en-IN"=>"English (India)",
				"en-NZ"=>"English (New Zealiand)",
				"en-PH"=>"English (Philippines)",
				"en-SA"=>"English (Saudi Arabia)",
				"en-SG"=>"English (Singapore)",
				"en-US"=>"English (U.S.)",
				"en-ZA"=>"English (South Africa)",
				"es-419"=>"Spanish (Latin America & Caribbean)",
				"es-CL"=>"Spanish (Chile)",
				"es-CO"=>"Spanish (Columbia)",
				"es-ES"=>"Spanish (Spain)",
				"es-MX"=>"Spanish (Mexico)",
				"es-US"=>"Spanish (U.S.)",
				"fi-FI"=>"Finnish (Finland)",
				"fr-BE"=>"French (Belgium)",
				"fr-CA"=>"French (Canada)",
				"fr-CH"=>"French (Switzerland)",
				"fr-FR"=>"French (France)",
				"he-IL"=>"Hebrew (Israel)",
				"hi-IN"=>"Hindi (India)",
				"hi-IN-translit"=>"Hindi (India) Translit",
				"hi-Latn"=>"Hindi (Latin)",
				"hr-HR"=>"Croatian (Croatia)",
				"hu-HU"=>"Hungarian (Hungary)",
				"id-ID"=>"Indonesian (Indonesia)",
				"it-CH"=>"Italian (Switzerland)",
				"ja-JP"=>"Japanese (Japan)",
				"ko-KR"=>"Korean (Korea)",
				"ms-MY"=>"Malay (Malaysia)",
				"nb-NO"=>"Norwegian (Bokm?l) (Norway)",
				"nl-BE"=>"Dutch (Belgium)",
				"nl-NL"=>"Dutch (Netherlands)",
				"pl-PL"=>"Polish (Poland)",
				"pt-BR"=>"Portuguese (Brazil)",
				"pt-PT"=>"Portuguese (Portugal)",
				"ro-RO"=>"Romanian (Romania)",
				"ru-RU"=>"Russian (Russia)",
				"sk-SK"=>"Slovak (Slovakia)",
				"sv-SE"=>"Swedish (Sweden)",
				"th-TH"=>"Thai (Thailand)",
				"tr-TR"=>"Turkish (Turkey)",
				"uk-UA"=>"Ukrainian (Ukraine)",
				"vi-VN"=>"Vietnamese (Viet Nam)",
				"wuu-CN"=>"Wu Chinese",
				"yue-CN"=>"Yue Chinese",
				"zh-CN"=>"Chinese (S)",
				"zh-HK"=>"Chinese (Hong Kong)",
				"zh-TW"=>"Chinese (T)"
				],
				[
					'class'=>'form-control',
					'name'=>'TaskMeta[value]',
					'id'=>'taskmeta-value_' . $uniqueId . '-Alt',
	]); ?>
	</div>

    <?= $form->field($model, 'value')->textInput(['id'=>'taskmeta-value_' . $uniqueId, "placeholder"=>"https://"]) ?>

    <?//= $form->field($model, 'created_at')->textInput() ?>

    <?//= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group form-inline">
		<?= Html::submitButton( ($model->isNewRecord) ? 'Create' : 'Update', ['class' => 'btn btn-success','style'=>'margin-bottom: 15px;']) ?>
		<?php if($model->isNewRecord == false){ ?>
        <?= Html::a('Delete', ['/task-meta/delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'style'=>'margin-bottom: 15px;',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<script type="text/javascript">
$(function() {
	$("#taskmeta-key_<?= $uniqueId; ?>").change(function(){
		var val = $(this).val();
		if(val == "<?= TaskMeta::KEY_LOCALE; ?>"){
			$("#taskmeta-value_<?= $uniqueId; ?>-AltGroup").show(); //Show the dropdown
			$("#taskmeta-value_<?= $uniqueId; ?>").val(""); //Set the normal input value to be empty
			$(".field-taskmeta-value_<?= $uniqueId; ?>").hide(); //Hide the normal input
			$("#taskmeta-value_<?= $uniqueId; ?>").val($("#taskmeta-value_<?= $uniqueId; ?>-Alt").val());
		}else if(val == "<?= TaskMeta::KEY_AR_TARGET_ID; ?>"){
			$("#taskmeta-value_<?= $uniqueId; ?>-AltGroup").hide(); //Hide the dropdown
			$(".field-taskmeta-value_<?= $uniqueId; ?>").show(); //Show the normal input
			$("#taskmeta-value_<?= $uniqueId; ?>").val(""); //Set the normal input value to be empty
		}else if(val == "<?= TaskMeta::KEY_URL; ?>"){
			$("#taskmeta-value_<?= $uniqueId; ?>-AltGroup").hide(); //Hide the dropdown
			$(".field-taskmeta-value_<?= $uniqueId; ?>").show(); //Show the normal input
			$("#taskmeta-value_<?= $uniqueId; ?>").val(""); //Set the normal input value to be empty
		}
	});

	$("#taskmeta-value_<?= $uniqueId; ?>-Alt").change(function(){
		$("#taskmeta-value_<?= $uniqueId; ?>").val($("#taskmeta-value_<?= $uniqueId; ?>-Alt").val());
	});

	<?php if($model->isNewRecord || $model->key == TaskMeta::KEY_LOCALE){ ?>
	$("#taskmeta-value_<?= $uniqueId; ?>-AltGroup").show(); //Show the dropdown
	$("#taskmeta-value_<?= $uniqueId; ?>").val(""); //Set the normal input value to be empty
	$(".field-taskmeta-value_<?= $uniqueId; ?>").hide(); //Hide the normal input
	$("#taskmeta-value_<?= $uniqueId; ?>").val($("#taskmeta-value_<?= $uniqueId; ?>-Alt").val());
	<?php } ?>
});
</script>