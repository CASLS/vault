<?php

use yii\helpers\Html;
use common\models\Task;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Quest */

$this->title = 'Update Quest: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Quests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$users = User::find()->all();
$usersArray = [];
foreach($users as $user){
	$usersArray[] = [
		"label"=>$user->email, 
		"value"=>$user->id
	];
}

?>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<h2><?= Html::encode($this->title) ?></h2>
	</div>
	<div class="col-xs-12 col-sm-12">
		<div class="quest-update well">
			<?php 
			if( ($editorAccess != NULL && $editorAccess->is_owner == 1) 
				|| (\Yii::$app->user->identity->user_type_id == 1 || \Yii::$app->user->identity->user_type_id == 2)){
			?>
			<?php 
				echo Html::a('<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete', ['delete', 'id' => $model->id], [
					'class' => 'btn btn-danger pull-right',
					'style'=>'margin-left:10px;',
					'data' => [
						'confirm' => 'Are you sure you want to delete this Quest? This can NOT be undone. All data associated with this Quest will be deleted.',
						'method' => 'post',
					],
				]);
			?>
			<?= $this->render('_user-access', [
				"model"=>$model,
		        "editor"=>$editor,
				"editorAccess"=>$editorAccess,
				"usersArray"=>$usersArray	
			]) ?>
			<?php } ?>
		    <?= $this->render('_form', [
		        'model' => $model,
			]) ?>
		</div>		
	</div>

	<hr/>
	<div class="col-xs-12 col-sm-12">
		<h2>Tasks</h2>
		<hr/>
		<!-- Nav tabs -->
		<ul id="taskTabs" class="nav nav-tabs" role="tablist">
			<?php foreach($model->tasks as $task){ ?>
			<li role="presentation" class=""><a name="task-<?= $task->id; ?>" href="#task-<?= $task->id; ?>" aria-controls="task-" role="tab" data-toggle="tab"><?= $task->title; ?></a></li>
			<?php } ?>
			<li role="presentation" class="active"><a href="#new" aria-controls="new" role="tab" data-toggle="tab">New Task</a></li>
  		</ul>
  		
  		<!-- Tab panes -->
		<div class="tab-content tasks">
			<div role="tabpanel" class="tab-pane active" id="new">
				<h3>New Task</h3>
				<?php 
				$newTask = new Task();
				$newTask->quest_id = $model->id;
				$sortOrder = 1;
				$taskCount = $model->getTasks()->count();
				if($taskCount > 0){
					$sortOrder = $taskCount + 1;
				}
				$newTask->sort_order = $sortOrder;
				?>
				<?= $this->render('/task/_form', [
			        'model' => $newTask,
			    ]) ?>
			</div>
			
			<?php foreach($model->tasks as $task){ ?>
			<div role="tabpanel" class="tab-pane" id="task-<?= $task->id; ?>">
				<h3>Update: <?= $task->title; ?></h3>
				<h5>Task ID: <?= $task->id; ?></h5>
				<?= $this->render('/task/_form', [
			        'model' => $task,
			    ]) ?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function() {
	<?php if($taskId != NULL){ ?>
	location.href = "#task-<?= $taskId; ?>";
	$('#taskTabs a[href="#task-<?= $taskId; ?>"]').tab('show') // Select tab by name
	<?php } ?>

	$(".removeMediaBtn").click(function(){
		var task_media_id = $(this).parent().attr("id");
		var mediaWrapper = $(this).parent();
		$.ajax({
			method: "POST",
			url: "/task/remove-media",
			data: {task_media_id: task_media_id }
		})
		 .done(function(json){
			if(json.returnCode == 1){
				console.log("error: " + json.returnCodeDescription);
			}else{
				mediaWrapper.slideUp(500, function(){
					mediaWrapper.remove();
				});
			}
		 });
	});

	$(".removeCorrectResponseMediaBtn").click(function(){
		var hiddenInput = $(this).parent().parent().prev().find("#task-correct_response_image_id");
		hiddenInput.val(""); //Empty the value. 
		$(this).parent().slideUp().remove();
	});

	$(".removeIncorrectResponseMediaBtn").click(function(){
		var hiddenInput = $(this).parent().parent().prev().find("#task-incorrect_response_image_id");
		hiddenInput.val(""); //Empty the value. 
		$(this).parent().slideUp().remove();
	});

	$("#searchBtn").click(function(){
		$( "#userSearchAC" ).autocomplete( "search", $( "#userSearchAC" ).val() );
	});

	$( "#userSearchAC" ).on( "autocompleteselect", function( event, ui ) {
		event.preventDefault();
		var item = ui.item;
		console.log(ui);
		giveAccess(<?= $model->id; ?>, item.value, 0, 0);
		$("#userSearchAC").val("");
	});

	$(document).on('change', '.userAccessOwnerCheckbox', function(){
		var is_owner = (this.checked == true) ? 1 : 0;
		var user_id = $(this).val();

		if(this.checked == true){
			//always force the permission to Edit if the is_owner is checked.
			$("#userAccessPermission_"+user_id).val(1);
			$("#userAccessPermission_"+user_id).parent()[0].disabled = true;
		}else{
			$("#userAccessPermission_"+user_id).parent()[0].disabled = false;
		}
		
		var permission = $("#userAccessPermission_"+user_id).val();
		giveAccess(<?= $model->id; ?>, user_id, is_owner, permission);
	});
	$(document).on('change', '.userAccessPermission', function(){
		var user_id = $(this).attr("id").split("_")[1];
		var is_owner = ($("#userOwnerAdmin_"+user_id)[0].checked == true) ? 1 : 0;
		var permission = $(this).val();
		giveAccess(<?= $model->id; ?>, user_id, is_owner, permission);
	});
});

function giveAccess(quest_id, user_id, is_owner, permission){
	$.ajax({
			method: "POST",
			url: "/quest/give-access",
			data: {quest_id: quest_id, user_id: user_id, is_owner: is_owner, permission: permission }
		})
		 .done(function(json){
			if(json.returnCode == 1){
				console.log("error: " + json.returnCodeDescription);
			}else{
				if($("#userAccess_" + user_id).length > 0){
					//replace the existing row	
					$("#userAccess_" + user_id).replaceWith(json.data.html);
				}else{
					//append the row
					$("#userAccessTable > tbody").append(json.data.html);
				}
			}
		 });
}

function removeAccess(quest_id, user_id){
	$.ajax({
			method: "POST",
			url: "/quest/remove-access",
			data: {quest_id: quest_id, user_id: user_id }
		})
		 .done(function(json){
			if(json.returnCode == 1){
				console.log("error: " + json.returnCodeDescription);
			}else{
				$("#userAccess_" + user_id).remove();
			}
		 });
}
</script>