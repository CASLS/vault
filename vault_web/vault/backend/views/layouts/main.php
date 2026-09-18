<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Google Sign-In disabled: built on the deprecated gapi.auth2 library (retired March 31, 2023).
    <meta name="google-signin-client_id" content="YOUR_GOOGLE_OAUTH_CLIENT_ID.apps.googleusercontent.com">
    -->

    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Sign Up', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
    	if(Yii::$app->user->identity->user_type_id == 1 || Yii::$app->user->identity->user_type_id == 2){
    		//Users dropdown
			$menuItems[] = ["label"=>"Users","url"=>['#'], 'items'=>[
							['label'=>'Users', 'url'=>['/user/index']],
							['label'=>'User Events', 'url'=>['/user-event/index']],
							['label'=>'User Media', 'url'=>['/user-media/index']],
    						['label'=>'User Prefs', 'url'=>['/user-pref/index']],
							['label'=>'User Task', 'url'=>['/user-task/index']],
    						['label'=>'User Types', 'url'=>['/user-type/index']],
    						['label'=>'Oauths', 'url'=>['/oauth/index']],
    						"<li role='separator' class='divider'></li>",
    						['label'=>'Media', 'url'=>['/media/index']],
						]];
			//Quests dropdown
			$menuItems[] = ["label"=>"Quests","url"=>['#'], 'items'=>[
                            ['label'=>'Quests', 'url'=>['/quest/index']],
                            ['label'=>'Quest User Access', 'url'=>['/quest-user-access/index']],
							['label'=>'Task', 'url'=>['/task/index']],
							['label'=>'Task Media', 'url'=>['/task-media/index']],
							['label'=>'Task Meta', 'url'=>['/task-meta/index']],
                            ['label'=>'Task Type', 'url'=>['/task-type/index']],
                            ['label'=>'Required Tasks', 'url'=>['/required-task/index']],
                            "<li role='separator' class='divider'></li>",
                            ['label'=>'AR Targets', 'url'=>['/ar-target/index']],
						]];
    	}else if(Yii::$app->user->identity->user_type_id == 3){
            //Tasks dropdown
            $menuItems[] = ['label'=>'Quests', 'url'=>['/quest/index']];
        }
    	
    	//Super Admin dropdown (Super Admin's ONLY)
		if(Yii::$app->user->identity->user_type_id == 1){
			$menuItems[] = ["label"=>"Super Admin","url"=>['#'], 'items'=>[
							['label'=>'Gii', 'url'=>['/gii']],
    						['label'=>'Double Model Generator', 'url'=>['/gii/doubleModel']],
    						['label'=>'CRUD Generator', 'url'=>['/gii/crud']],
    						['label'=>'CKEditor Configuration', 'url'=>['/common/js/ckeditor/samples/toolbarconfigurator/index.html#basic']],
    				]];
		}
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>

<script type="text/javascript">
$(document).ready(function(){
	// startApp() came from the now-disabled google-api.js (deprecated gapi.auth2 library).
	// startApp();
});
</script>

</body>
</html>
<?php $this->endPage() ?>
