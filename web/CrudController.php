<?php
/**
 * Created by PhpStorm.
 * User: Nikola nb
 * Date: 16.12.2015
 * Time: 21:41 �.
 */

namespace insight\core\web;

use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CrudController extends Controller
{
    public $modelClass;

    public function actionIndex()
    {
        return $this->render('index', $this->getData());
    }

    public function actionView($id)
    {
        $model = $this->load($id);
        return $this->render('view', compact('model', 'tokens'));
    }

    public function actionCreate()
    {
        $model = Yii::createObject($this->modelClass);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->get('validate')) {
                return ActiveForm::validate($model);
            }
            $this->save($model);
            return ['url' => '#' . Url::to(['index'])];
        }
        
        $params = array_merge(['model' => $model], $this->getData($model));
        return $this->render('_form', $params);
    }

    public function actionUpdate($id)
    {
        $model = $this->load($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->get('validate')) {
                return ActiveForm::validate($model);
            }
            $this->save($model);
            return ['url' => '#' . Url::to(['index'])];
        }
        
        $params = array_merge(['model' => $model], $this->getData($model));
        return $this->render('_form', $params);
    }

    public function actionDelete($id)
    {
        $this->delete($id);
        return ['url' => '#' . Url::to(['index'])];
    }

    protected function getData($model = null)
    {
        if ($model) {
            return [];
        }
        return [
            'dataProvider' => new ActiveDataProvider([
                'query' => call_user_func([$this->modelClass, 'find']),
            ])
        ];
    }

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function load($id)
    {
        $model = call_user_func([$this->modelClass, 'findOne'], $id);
        if (!isset($model))
            throw new NotFoundHttpException();
        return $model;
    }
    
    protected function save($model)
    {
        return $model->save();
    }

    protected function delete($id)
    {
        $model = $this->load($id);
        return $model->delete();
    }
}
