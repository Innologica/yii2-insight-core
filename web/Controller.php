<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 11.12.2015
 * Time: 12:09 ÷.
 */

namespace insight\core\web;


class Controller extends \yii\web\Controller
{
    /**
     * Checks if an ajax request is made and makes partial render.
     *
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        if(\Yii::$app->request->isAjax)
            return parent::renderAjax($view, $params);
        else
            return parent::render($view, $params);
    }

}