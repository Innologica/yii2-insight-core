<?php
/**
 * Created by PhpStorm.
 * User: Nikola nb
 * Date: 16.12.2015
 * Time: 21:41 �.
 */

namespace insight\core\web;

use insight\gui\behaviors\FlashMessageBehavior;
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

            if ($this->save($model)) {
                Yii::$app->session->setFlash(FlashMessageBehavior::FLASH_SUCCESS, Yii::t('insight.gui', 'The record was successfuly created!'));
            } else {
                Yii::$app->session->setFlash(FlashMessageBehavior::FLASH_ERROR, Yii::t('insight.gui', 'Error while trying to create the record. Please contact us!'));
            }
            
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

            if ($this->save($model)) {
                Yii::$app->session->setFlash(FlashMessageBehavior::FLASH_SUCCESS, Yii::t('insight.gui', 'The record was successfuly updated!'));
            } else {
                Yii::$app->session->setFlash(FlashMessageBehavior::FLASH_ERROR, Yii::t('insight.gui', 'Error while trying to update the record. Please contact us!'));
            }
            
            return ['url' => '#' . Url::to(['index'])];
        }
        
        $params = array_merge(['model' => $model], $this->getData($model));
        return $this->render('_form', $params);
    }

    public function actionDelete($id)
    {
        if ($this->delete($id)) {
            Yii::$app->session->setFlash(FlashMessageBehavior::FLASH_SUCCESS, Yii::t('insight.gui', 'The record was successfuly deleted!'));
        } else {
            Yii::$app->session->setFlash(FlashMessageBehavior::FLASH_ERROR, Yii::t('insight.gui', 'Error while trying to delete the record. Please contact us!'));
        }

        return $this->redirect('/#' . Url::to(['index']));
    }

    protected function getData($model = null)
    {
        if ($model)
            return [];
        
        return [
            'dataProvider' => new ActiveDataProvider([
                'query' => call_user_func([$this->modelClass, 'find']),
            ]),
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
