<?php

namespace frontend\modules\rekening\controllers;

use Yii;
use common\models\Bank;
use frontend\models\BankSearch;
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
        BaseController::$page_caption = 'Rekening';
        
        $searchModel = new BankSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Karyawan';

        $model = new Bank();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = 1;
            if($model->save()){
                Yii::$app->session->setFlash('success', 'Brand '.LabelComponent::SUCCESS_SAVE);
                if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
                    return $this->redirect(['create']);
                else
                    return $this->redirect(['index']);
            }
			
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
        ]);
    }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Rekening';
		
		$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', 'Rekening '.LabelComponent::SUCCESS_SAVE);
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
        $model->delete();

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
        if (($model = Bank::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Rekening '.LabelComponent::NOT_FOUND);
    }
}
