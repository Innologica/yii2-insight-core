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

    /**
     * Allow filtering by get parameter filter. Each item of the array is a method in the Finder object. If the
     * element is array then the values of the array are passed as parameters.
     *
     * Example:
     * {
     *   "filter": [ "active", { "forId": { "id": 11 } }]
     * }
     *
     * This will execute
     * $model->find()->active()->forId(11)
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        if(\Yii::$app->request->get('filter'))
            $actions['index']['prepareDataProvider'] = function ($action) {
                /* @var $modelClass \yii\db\BaseActiveRecord */
                $modelClass = $this->modelClass;

                $query = $modelClass::find();
                $filters = \Yii::$app->request->get('filter');

                foreach($filters as $filter) {
                    if(is_array($filter)) {
                        $function = key($filter);
                        $query = call_user_func_array([$query, $function], $filter[$function]);
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