<?php

namespace insight\core\db;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\web\NotFoundHttpException;

/**
 * @author Nikolay Traykov
 */
class BaseQuery extends ActiveQuery
{
    /**
     * @return ActiveDataProvider
     */
    public function asDataProvider()
    {
        return new ActiveDataProvider([
            'query' => $this,
        ]);
    }

    /*
     * return ArrayDataProvider
     */
    public function asArrayDataProvider()
    {
        return new ArrayDataProvider([
            'models' => $this->all(),
        ]);
    }

    /**
     * @param Connection $db the DB connection used to create the DB command.
     * @return ActiveRecord
     * @throws NotFoundHttpException
     */
    public function oneOrThrow($db = null)
    {
        $model = parent::one($db);
        if (!$model) {
            throw new NotFoundHttpException();
        }

        return $model;
    }
}
