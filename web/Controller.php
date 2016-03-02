<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 11.12.2015
 * Time: 12:09 ч.
 */

namespace insight\core\web;

class Controller extends \yii\web\Controller
{
    public $access = [];
    /**
     * Checks if an ajax request is made and makes partial render.
     *
     * @param string $view
     * @param array $params
     * @return View
     */
    public function render($view, $params = [])
    {
        $view = new View([
            'viewFile' => $view,
            'params' => $params,
        ]);
        return $view;
    }

    public function runAction($id, $params = [])
    {
        $result = parent::runAction($id, $params);
        if($result instanceof View) {
            if (\Yii::$app->request->isAjax) {
                $result = parent::renderAjax($result->viewFile, $result->params);
            } else {
                $result = parent::render($result->viewFile, $result->params);
            }
        }
        return $result;
    }

    public function behaviors()
    {
        if(!empty($this->access)) {
            $access = [
                'access' => $this->access
            ];
        } else
            $access = [];

        return array_merge(
            $access,
            parent::behaviors()
        );
    }


}