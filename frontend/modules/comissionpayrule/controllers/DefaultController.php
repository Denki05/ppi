<?php

namespace frontend\modules\comissionpayrule\controllers;

use Yii;
use common\models\ComissionPayRule;
use frontend\models\ComissionPayRuleSearch;
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
        BaseController::$page_caption = 'Aturan Pencairan Komisi';
        
        $searchModel = new ComissionPayRuleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Aturan Pencairan Komisi';

        $model = new ComissionPayRule();

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
		BaseController::$page_caption = 'Update Aturan Pencairan Komisi';
		
		$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			       Yii::$app->session->setFlash('success', 'Aturan Pencairan Komisi '.LabelComponent::SUCCESS_SAVE);
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
        if (($model = ComissionPayRule::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Aturan Pencairan Komisi '.LabelComponent::NOT_FOUND);
    }
}
