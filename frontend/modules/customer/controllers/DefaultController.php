<?php

namespace frontend\modules\customer\controllers;

use Yii;
use common\models\Customer;
use frontend\models\CustomerSearch;
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
        BaseController::$page_caption = 'Customer';
        
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Customer';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }
    
    public function actionCreate()
    {
        BaseController::$page_caption = 'Tambah Customer';

        $model = new Customer();
        $model->customer_type = Customer::CUSTOMER_TYPE_GENERAL;

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;
            $trans = Yii::$app->db->beginTransaction();

            $imageCardname = uniqid(rand());
            $imageNpwpname = uniqid(rand());
            $model->imageCard = NULL;
            $model->imageNpwp = NULL;
            $model->imageCard  = UploadedFile::getInstanceByName('Customer[customer_identity_card_image]');
            $model->imageNpwp  = UploadedFile::getInstanceByName('Customer[customer_npwp_image]');
            $model->customer_identity_card_image = !empty($model->imageCard) ? $imageCardname.'.'.$model->imageCard->extension : "";
            $model->customer_npwp_image = !empty($model->imageNpwp) ? $imageNpwpname.'.'.$model->imageNpwp->extension : "";

            $model->customer_birthday = !empty($model->customer_birthday) ? date("Y-m-d", strtotime($model->customer_birthday)) : "";
            $model->customer_store_code = $model->getStoreCode('S', 'customer_store_code');

            if($model->save()){
                
                $path = realpath(dirname(__FILE__).'/../../../../').'/uploads/customers';
                if ($model->imageCard) {
                    $model->imageCard->saveAs($path."/".$model->customer_identity_card_image);
                }

                if ($model->imageNpwp) {
                    $model->imageNpwp->saveAs($path."/".$model->customer_npwp_image);
                }

            }else{
                $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                Yii::$app->session->setFlash('danger', 'Gagal menyimpan Data karena kesalahan berikut: '.$errorString);
                $noProblem = false;
            }

            if($noProblem){
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Merek '.LabelComponent::SUCCESS_SAVE);

                if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
                    return $this->redirect(['create']);
                else
                    return $this->redirect(['index']);
                
            }

            $model->customer_birthday = !empty($model->customer_birthday) ? date("d-m-Y", strtotime($model->customer_birthday)) : "";
            $trans->rollBack();
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'create',
        ]);
    }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Customer';
		
		$model = $this->findModel($id);
        $tempImageCard = $model->customer_identity_card_image;
        $tempImageNpwp = $model->customer_npwp_image;

        $model->customer_birthday = !empty($model->customer_birthday) ? date("d-m-Y", strtotime($model->customer_birthday)) : "";

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;
            $trans = Yii::$app->db->beginTransaction();

            $imageCardname = uniqid(rand());
            $imageNpwpname = uniqid(rand());
            $model->imageCard = NULL;
            $model->imageNpwp = NULL;
            $model->imageCard  = UploadedFile::getInstanceByName('Customer[customer_identity_card_image]');
            $model->imageNpwp  = UploadedFile::getInstanceByName('Customer[customer_npwp_image]');
            $model->customer_identity_card_image = !empty($model->imageCard) ? $imageCardname.'.'.$model->imageCard->extension : $tempImageCard;
            $model->customer_npwp_image = !empty($model->imageNpwp) ? $imageNpwpname.'.'.$model->imageNpwp->extension : $tempImageNpwp;

            $model->customer_birthday = !empty($model->customer_birthday) ? date("Y-m-d", strtotime($model->customer_birthday)) : "";

            if($model->save()){
                
                $path = realpath(dirname(__FILE__).'/../../../../').'/uploads/customers';
                if ($model->imageCard) {
                    $model->imageCard->saveAs($path."/".$model->customer_identity_card_image);
                }

                if ($model->imageNpwp) {
                    $model->imageNpwp->saveAs($path."/".$model->customer_npwp_image);
                }

            }else{
                $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                Yii::$app->session->setFlash('danger', 'Gagal menyimpan barang karena kesalahan berikut: '.$errorString);
                $noProblem = false;
            }

            if($noProblem){
                $trans->commit();
                Yii::$app->session->setFlash('success', 'Brand '.LabelComponent::SUCCESS_SAVE);

                if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
                    return $this->redirect(['create']);
                else
                    return $this->redirect(['index']);
                
            }

            $model->customer_birthday = !empty($model->customer_birthday) ? date("d-m-Y", strtotime($model->customer_birthday)) : "";
            // $trans->rollBack();
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
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Customer '.LabelComponent::NOT_FOUND);
    }
}
