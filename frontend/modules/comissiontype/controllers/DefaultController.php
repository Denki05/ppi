<?php

namespace frontend\modules\comissiontype\controllers;

use Yii;
use common\models\ComissionType;
use frontend\models\ComissionTypeSearch;
// use yii\web\Controller;
use common\components\ErrorGenerateComponent;
use frontend\components\LabelComponent;
use app\components\BaseController;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DefaultController implements the CRUD actions for DiscMaster model.
 */
class DefaultController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Tipe Komisi';
        
        $searchModel = new ComissionTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Tipe Komisi';

        $model = new ComissionType();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			     Yii::$app->session->setFlash('success', 'Brand '.LabelComponent::SUCCESS_SAVE);
            if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
                return $this->redirect(['create']);
            else
                return $this->redirect(['index']);
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
        ]);
    }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Tipe Komisi';
		
		$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			       Yii::$app->session->setFlash('success', 'Merek '.LabelComponent::SUCCESS_SAVE);
              return $this->redirect(['index']);
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'update',
        ]);
    }

    public function actionDelete($id)
    {
        $model  = $this->findModel($id);
        $model->is_deleted = "1";
        $model->update();

        return $this->redirect(['index']);
    }

  //   /**
  //    * Finds the DiscMaster model based on its primary key value.
  //    * If the model is not found, a 404 HTTP exception will be thrown.
  //    * @param integer $id
  //    * @return DiscMaster the loaded model
  //    * @throws NotFoundHttpException if the model cannot be found
  //    */
    protected function findModel($id)
    {
        if (($model = ComissionType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Tipe Komisi '.LabelComponent::NOT_FOUND);
    }
}
