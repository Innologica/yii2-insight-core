<?php
/**
 * Created by PhpStorm.
 * User: Nikola nb
 * Date: 16.12.2015
 * Time: 21:41 ÷.
 */

namespace insight\core\web;


use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\di\Instance;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CrudController extends Controller
{

    public $modelClass;

    /**
     * @param $id
     * @return null|ActiveRecord
     * @throws NotFoundHttpException
     */
    public function load($id)
    {
        $model = call_user_func([$this->modelClass, 'findOne'], $id);
        if (!isset($model))
            throw new NotFoundHttpException();
        return $model;
    }

    public function actionCreate()
    {
        $model = Yii::createObject($this->modelClass);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->get('validate')) {
                return ActiveForm::validate($model);
            }
            $model->save();
            return ['url' => '#' . Url::to(['index'])];
        }
        return $this->render('_form', compact('model'));
    }

    public function actionUpdate($id)
    {
        $model = $this->load($id);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if (Yii::$app->request->get('validate')) {
                return ActiveForm::validate($model);
            }
            $model->save();
            return ['url' => '#' . Url::to(['index'])];
        }
        return $this->render('_form', compact('model'));
    }

    public function actionDelete($id)
    {
        $model = $this->load($id);
        $model->delete();
        $this->redirect(Url::to(['index'], true));
    }

    public function actionIndex()
    {
        return $this->render('index', $this->getIndexData());
    }

    protected function getIndexData()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => call_user_func([$this->modelClass, 'find']),
        ]);
        return compact('dataProvider');
    }

    public function actionView($id)
    {
        $model = $this->load($id);
        return $this->render('view', compact('model', 'tokens'));
    }

}