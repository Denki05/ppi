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
use yii\web\UploadedFile;
use yii\imagine\Image;  
use Imagine\Image\Box;

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
        BaseController::$page_caption = 'Tambah Rekening';

        $model = new Bank();
        $model->bank_type = Bank::BANK_TYPE_NON_PPN;


        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;
            $trans = Yii::$app->db->beginTransaction();

            //$imageCardname = uniqid(rand());
            $imageBankname = !empty($model->bank_acc_name) ? $model->bank_acc_number : "";
            //$model->imageCard = NULL;
            $model->imageBank = NULL;
            //$model->imageCard  = UploadedFile::getInstanceByName('Customer[customer_identity_card_image]');
            $model->imageBank  = UploadedFile::getInstanceByName('Bank[bank_image]');
            //$model->customer_identity_card_image = !empty($model->imageCard) ? $imageCardname.'.'.$model->imageCard->extension : "";
            $model->bank_image = !empty($model->imageBank) ? $imageBankname.'.'.$model->imageBank->extension : "";

            //$model->customer_birthday = !empty($model->customer_birthday) ? date("Y-m-d", strtotime($model->customer_birthday)) : "";
            //$model->customer_store_code = $model->getStoreCode('S', 'customer_store_code');

            if($model->save()){
                
                $path = realpath(dirname(__FILE__).'/../../../../').'/uploads/rekening';
                if ($model->imageBank) {
                    $model->imageBank->saveAs($path."/".$model->bank_image);
                }

            }else{
                $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                Yii::$app->session->setFlash('danger', 'Gagal menyimpan Data karena kesalahan berikut: '.$errorString);
                $noProblem = false;
            }

            if($noProblem){
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Rekening '.LabelComponent::SUCCESS_SAVE);

                if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
                    return $this->redirect(['create']);
                else
                    return $this->redirect(['index']);
                
            }

            //$model->customer_birthday = !empty($model->customer_birthday) ? date("d-m-Y", strtotime($model->customer_birthday)) : "";
            $trans->rollBack();
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
