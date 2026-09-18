<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Font Awesome asset bundle.
 */
class FontAwesomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public $css = [
//         "https://use.fontawesome.com/releases/v5.6.1/css/all.css"
    ];
    
    public $cssOptions = [
//     		"integrity"=>"sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP",
//     		"crossorigin"=>"anonymous"
    		
    ];
    
    public $js = [
    		"https://use.fontawesome.com/releases/v5.6.1/js/all.js"
    ];
    
    public $jsOptions = [
    		"integrity"=>"sha384-R5JkiUweZpJjELPWqttAYmYM1P3SNEJRM6ecTQF05pFFtxmCO+Y1CiUhvuDzgSVZ",
    		"crossorigin"=>"anonymous"
    ];
    
    public $depends = [
    		
    ];
}
