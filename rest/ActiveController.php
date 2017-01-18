<?php

namespace insight\core\rest;

use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\rest\ActiveController as BaseActiveController;

/**
 * @author Nikolay Traykov
 */
class ActiveController extends BaseActiveController
{
    public $filterParam = 'filter';

    public function actions()
    {
        $actions = parent::actions();
        if(\Yii::$app->request->get('filter'))
            $actions['index']['prepareDataProvider'] = function ($action) {
                /* @var $modelClass \yii\db\BaseActiveRecord */
                $modelClass = $this->modelClass;

                $query = $modelClass::find();
                $filters = \Yii::$app->request->get('filter');

                foreach($filters as $filter => $params) {
                    if(is_array($params)) {
                        $query = call_user_func_array([$query, $filter], $params);
                    } else {
                        $query = call_user_func([$query, $filter]);
                    }
                }

                return new ActiveDataProvider([
                    'query' => $query,
                ]);
            };
        return $actions;
    }

}