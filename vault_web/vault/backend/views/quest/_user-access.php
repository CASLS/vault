<?php
use yii\bootstrap\Modal;
use yii\jui\AutoComplete;
use common\models\UserType;

//The current editor must either be an Admin or Super Admin OR their editorAccess object shows they are the 
//owner of the quest. Only the owner of the quest can give and remove other users access. 
//(With the exception of the Admin or SuperAdmin)
if($editor->user_type_id == UserType::SUPER_ADMIN || $editor->user_type_id == UserType::ADMIN || $editorAccess->is_owner == 1){ ?>
<?php Modal::begin([
        "header"=>"<h3>Who has access to this quest?</h3>",
        "toggleButton"=> ['label' => 'User Access','class'=>'btn btn-primary pull-right'],
]); ?>
    <div class="">
        <div class="input-group">
            <?php 
            echo AutoComplete::widget([
                'name' => 'userSearchAC',
                'options' => [
                    'id'=>'userSearchAC',
                    'class'=>'form-control',
                    'placeholder'=>'Search and add users...'
                ],
                'clientOptions' => [
                    'source' => $usersArray,
                    'classes'=>[
                        'ui-autocomplete' => 'searchAC'		
                    ]
                ],
            ]);
            ?>
            <span class="input-group-btn">
                <button id="searchBtn" class="btn btn-primary" type="button"><span class="glyphicon glyphicon-search"></span></button>
            </span>
        </div>
        <table id="userAccessTable" class="table table-striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Access Date</th>
                    <th>Is Owner</th>
                    <th>Permission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($model->questUserAccesses as $qua){ ?>
                <?php $user = $qua->user; ?>
                <?= $this->render("/quest-user-access/_row",[
                        "user"=>$user,
                        "quest"=>$model,
                        "qua"=>$qua
                ]);?>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php Modal::end(); ?>
<div class="clearfix"></div>
<?php } ?>