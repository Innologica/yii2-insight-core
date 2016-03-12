<?php

namespace insight\core\db;

use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;

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
}
