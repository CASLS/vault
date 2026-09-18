<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    		'common/js/ckeditor/ckeditor.js',
            'common/js/common.js',
            // 'common/js/google-api.js', // Disabled: built on the deprecated gapi.auth2 library
            // (retired by Google March 31, 2023). Needs a Google Identity Services rewrite before re-enabling.
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\jQueryAsset',
        // 'backend\assets\GoogleApiAsset', // Disabled alongside google-api.js, see above.
        'backend\assets\FontAwesomeAsset'
    ];
}
