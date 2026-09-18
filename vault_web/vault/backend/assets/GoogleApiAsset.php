<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class GoogleApiAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
    ];
    public $js = [
    		'https://apis.google.com/js/platform.js'
//     		'https://apis.google.com/js/platform.js?onload=appStart'
    ];
    public $jsOptions = [
    		'position' => View::POS_END,
//     		'async'=>true,
    		'defer'=>true
    ];
    public $depends = [
    		'yii\web\JqueryAsset',
    		'yii\jui\JuiAsset'
    ];
}
