<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 11.09.2015
 * Time: 18:51 ч.
 */

namespace insight\core\rest;


class Controller extends \yii\rest\Controller {
    public function init()
    {
        \Yii::$app->user->enableSession = false; //Disable session for REST requests
        parent::init();
    }

}