<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 11.12.2015
 * Time: 12:09 ч.
 */

namespace insight\core\web;

use Yii;
use yii\filters\ContentNegotiator;
use yii\web\Response;

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
            if ($this->isAjax()) {
                $result = parent::renderAjax($result->viewFile, $result->params);
            } else {
                $result = parent::render($result->viewFile, $result->params);
            }
        }
        return $result;
    }

    public function isAjax()
    {
        return Yii::$app->request->isAjax;
    }

    public function setJsonResponseFormat()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
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
            [
                'contentNegotiator' => [
                    'class' => ContentNegotiator::className(),
                    'formats' => [
                        'text/html' => Response::FORMAT_HTML,
                        'application/json' => Response::FORMAT_JSON,
                        'application/xml' => Response::FORMAT_XML,
                    ],
                ],
            ],
            parent::behaviors()
        );
    }


}