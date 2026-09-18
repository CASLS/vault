<?php
use yii\bootstrap\Html;
use common\models\QuestUserAccess;

?>

<tr id="userAccess_<?= $user->id; ?>">
    <td><a href="mailto:<?= $user->email; ?>"><?= $user->email; ?></a></td>
    <td><?= date("m/d/y",strtotime($qua->created_at)); ?></td>
    <td><input type="checkbox" class="userAccessOwnerCheckbox" id="userOwnerAdmin_<?= $user->id; ?>" value="<?= $user->id; ?>" <?= ($qua->is_owner == 1) ? "checked" : ""; ?> /></td>
    <td>
        <fieldset <?= ($qua->is_owner == 1) ? "disabled" : ""; ?>>
        <?= Html::dropDownList(
                    'permission', 
                    $qua->permission,
                    [
                        QuestUserAccess::PERMISSION_VIEW=>"View",
                        QuestUserAccess::PERMISSION_EDIT=>"Edit"
                    ],
                    [
                        'id'=>'userAccessPermission_'.$user->id,
                        'class'=>'form-control input-sm userAccessPermission',
                    ]) ?>
        </fieldset>
    </td>
    <td>
        <button type="button" onclick="removeAccess(<?= $quest->id; ?>, <?= $user->id; ?>);" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove"></span> Remove</button>
    </td>
</tr>