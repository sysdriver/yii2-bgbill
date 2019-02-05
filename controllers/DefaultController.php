<?php

namespace frontend\modules\bgbill\controllers;

use yii\web\Controller;

/**
 * Default controller for the `bgbill` module
 */
class DefaultController extends Controller
{
    //public $layout = 'post';
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
