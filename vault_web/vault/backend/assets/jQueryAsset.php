<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main frontend application asset bundle.
 */
class jQueryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        
    ];
    public $js = [
    ];
    public $jsOptions = array(
    		'position' => View::POS_HEAD
    );
    public $depends = [
    		'yii\web\JqueryAsset',
    		'yii\jui\JuiAsset',
    		'kartik\file\FileInputAsset'
    ];
}
