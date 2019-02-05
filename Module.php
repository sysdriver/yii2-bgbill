<?php

namespace frontend\modules\bgbill;

/**
 * bgbill module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\bgbill\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        //$this->layout = 'main';
    }
}
