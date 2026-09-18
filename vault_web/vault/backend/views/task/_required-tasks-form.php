<?php
use common\models\RequiredTask;

$uniqueId = str_replace("-","",\Yii::$app->security->generateRandomString(8));
?>
<form id="requiredTasksForm_<?= $uniqueId; ?>" action="/task/update-required-tasks" method="POST">
<?php foreach($model->quest->tasks as $task){ ?>
	<?php $requiredTask = RequiredTask::findOne(["parent_task_id"=>$model->id, "child_task_id"=>$task->id]); ?>
	<div class="checkbox <?= ($task->id == $model->id) ? 'disabled' : ''; ?>">
		<label>
			<input name="requiredTasks[]" type="checkbox" value="<?= $task->id; ?>" <?= ($requiredTask != NULL) ? "checked" : ""; ?> <?= ($task->id == $model->id) ? 'disabled' : ''; ?>/>
			<?= $task->title; ?> <?= ($task->id == $model->id) ? '(this task)' : ''; ?>
		</label>
	</div>
<?php } ?>
	<button type="button" id="requiredTasksBtn_<?= $uniqueId; ?>" class="btn btn-success btn-block">Update</button>
	<input type="hidden" name="parent_task_id" value="<?= $model->id; ?>" />
</form>
<script type="text/javascript">
$(function(){
	$("#requiredTasksBtn_<?= $uniqueId; ?>").click(function(){
		var data = $("#requiredTasksForm_<?= $uniqueId; ?>").serialize();
		$.ajax({
			method: "POST",
			url: "/task/update-required-tasks",
			data: data
		})
		 .done(function(json){
			if(json.returnCode == 1){
				console.log("error: " . json.returnCodeDescription);
				alert(json.returnCodeDescription);
			}else{
				alert(json.returnCodeDescription);
			}
		 });
	});
});
</script>