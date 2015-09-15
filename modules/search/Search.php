<?php

namespace app\modules\search;

class Search extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\search\controllers';

    public function init()
    {
        parent::init();
		//$this->layout = 'search_main';
		//$this->layout = '..\..\layout\search_layout';
		//$this->layout = 'main';
		//ployer\basic\modules\search\views\layouts\..\..\layout\main.php
        // custom initialization code goes here
    }
}
