<?php
/**
 * @author Nikola Kostadinov<nikolakk@gmail.com>
 * Date: 16.08.2016
 * Time: 11:12 ч.
 */

namespace insight\core\rest;


use yii\base\Action;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Json;

class ComponentAction extends Action
{
    public $component;
    /**
     * @var \ReflectionMethod
     */
    public $method;
    public $checkAccess;

    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $params = $this->method->getParameters();
        //foreach($params as $param)
        $response = $this->method->invoke($this->component);

        if($response instanceof ActiveQuery) {
            $filter = \Yii::$app->request->getQueryParam('filter');
            $filter = Json::decode($filter);
            if(isset($filter)) {
                $response->andWhere($filter);
            }
            $response = new ActiveDataProvider([
                'query' => $response
            ]);
        }

        return $response;

    }

}