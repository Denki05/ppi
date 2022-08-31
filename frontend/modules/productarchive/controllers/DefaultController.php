<?php

namespace frontend\modules\productarchive\controllers;

use Yii;
use common\models\Product;
use common\models\Category;
use frontend\models\ProductArchiveSearch;
use frontend\models\ImportProductForm;
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
use yii\helpers\Json;

/**
 * DefaultController implements the CRUD actions for DiscMaster model.
 */
class DefaultController extends BaseController
{
   
    public function actionIndex()
    {
        BaseController::$page_caption = 'Barang Arsip';
        
        $searchModel = new ProductArchiveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {
        BaseController::$page_caption = 'Import Data Barang';
        
        $model = new ImportProductForm;

        if (isset($_POST['ImportProductForm'])) {
            $path = realpath(dirname(__FILE__).'/../../../../').'/uploads/products';

            $model->excelFile = UploadedFile::getInstance($model, 'excelFile');

            if ($model->excelFile && $model->validate()) {
                $model->excelFile->saveAs($path.'/' . $model->excelFile->name);

                try {
                    $trans = Yii::$app->db->beginTransaction();
                    
                    //$num = (new Attendance)->import($path.'/' . $model->excelFile->name);
                    (new Product)->import($path.'/' . $model->excelFile->name);
                    
                    // if ($num['success'] > 0) {
                    //  $message = "Import ".$num['success']." absensi telah berhasil";
                    //  if ($num['failed'] > 0) {
                    //      $message .= ", ".$num['failed']." absensi gagal diimport dengan error berikut:<br/>";
                    //      $message .= $num['errorMessage'];
                    //  }
                    //  Yii::$app->session->setFlash('success', $message);
                    // }
                    // else
                    //  Yii::$app->session->setFlash('danger', $num['failed'].' absensi gagal diimport karena sudah ada di daftar');
                    
                    $trans->commit();
                    
                    unlink($path.'/' . $model->excelFile->name);
                    
                    return $this->redirect(array('/product/default/index'));
                }
                catch(Exception $e) {
                    Yii::$app->session->setFlash('danger', 'Gagal import dengan pesan: '.$e->getMessage());
                }
            }
        }

        return $this->render('import', [
            'model' => $model
        ]);
    }

    public function actionView($id)
    {
        BaseController::$page_caption = 'Lihat Barang Arsip';
        
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model
        ]);
    }

    public function actionGetitem($id)
    {
        $items = Category::find()->where('brand_id=:id',[':id'=>$id])->orderBy(['category_name' => SORT_ASC])->all();

        $hasil = array();
        foreach ($items as $item) {
            $hasil[] = [
                'id' => $item->id,
                'category_name' => $item->category_name,
            ];
        }
        return Json::encode($hasil);
        die();
    }
    
    // public function actionCreate()
    // {
    //     BaseController::$page_caption = 'Tambah Barang';

    //     $model = new Product();
    //     $model->product_is_new = 1;

    //     if ($model->load(Yii::$app->request->post())) {
    //         $noProblem = true;
    //         $trans = Yii::$app->db->beginTransaction();

    //         $factory = $model->setNewFactory($model->factory_id);
    //         if ($factory['success'] == false) {
    //             Yii::$app->session->setFlash('danger', $factory['message']);
    //             $noProblem = false;
    //         }
    //         $model->factory_id = isset($factory['id']) ? $factory['id'] : 1;
            
    //         $searah = $model->setNewSearah($model->searah_id);
    //         if ($searah['success'] == false) {
    //             Yii::$app->session->setFlash('danger', $searah['message']);
    //             $noProblem = false;
    //         }
    //         $model->searah_id = isset($searah['id']) ? $searah['id'] : 1;
           
            
    //         $brand = $model->setNewBrand($model->brand_id);
    //         if ($brand['success'] == false) {
    //             Yii::$app->session->setFlash('danger', $brand['message']);
    //             $noProblem = false;
    //         } 
    //         $model->brand_id = isset($brand['id']) ? $brand['id'] : 1;
            
    //         if(!empty($model->category_id)){
    //             $category = $model->setNewCategory($model->category_id, $model->brand_id);
    //             if ($category['success'] == false) {
    //                 Yii::$app->session->setFlash('danger', $category['message']);
    //                 $noProblem = false;
    //             }
    //             $model->category_id = isset($category['id']) ? $category['id'] : 1;
    //         }

    //         $originalBrand = $model->setNewOriginalBrand($model->original_brand_id);
    //         if ($originalBrand['success'] == false) {
    //             Yii::$app->session->setFlash('danger', $originalBrand['message']);
    //             $noProblem = false;
    //         }
    //         $model->original_brand_id = isset($originalBrand['id']) ? $originalBrand['id'] : 1;

    //         $model->mode = 'save';

    //         if($model->save()){
                
    //         }
    //         else{

    //             $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
    //             Yii::$app->session->setFlash('danger', 'Gagal menyimpan barang karena kesalahan berikut: '.$errorString);
    //             $noProblem = false;
    //         }

    //         if($noProblem){

    //             $trans->commit();
    //             Yii::$app->session->setFlash('success', 'Barang '.LabelComponent::SUCCESS_SAVE);
    //             if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
    //                 return $this->redirect(['create']);
    //             else
    //                 return $this->redirect(['index']);

    //         }            

    //         $trans->rollBack();
    //     }

    //     return $this->render('form', [
    //         'model' => $model,
    //         'mode' => 'create',
    //     ]);
    // }

    public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update Barang';
		
		$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $noProblem = true;
            $trans = Yii::$app->db->beginTransaction();

            $factory = $model->setNewFactory($model->factory_id);
            if ($factory['success'] == false) {
                Yii::$app->session->setFlash('danger', $factory['message']);
                $noProblem = false;
            }
            $model->factory_id = isset($factory['id']) ? $factory['id'] : $model->factory_id;
            
            $searah = $model->setNewSearah($model->searah_id);
            if ($searah['success'] == false) {
                Yii::$app->session->setFlash('danger', $searah['message']);
                $noProblem = false;
            }
            $model->searah_id = isset($searah['id']) ? $searah['id'] : $model->searah_id;
           
            
            $brand = $model->setNewBrand($model->brand_id);
            if ($brand['success'] == false) {
                Yii::$app->session->setFlash('danger', $brand['message']);
                $noProblem = false;
            } 
            $model->brand_id = isset($brand['id']) ? $brand['id'] : $model->brand_id;
            
            $category = $model->setNewCategory($model->category_id, $model->brand_id);
            if ($category['success'] == false) {
                Yii::$app->session->setFlash('danger', $category['message']);
                $noProblem = false;
            }
            $model->category_id = isset($category['id']) ? $category['id'] : $model->category_id;
            
            $originalBrand = $model->setNewOriginalBrand($model->original_brand_id);
            if ($originalBrand['success'] == false) {
                Yii::$app->session->setFlash('danger', $originalBrand['message']);
                $noProblem = false;
            }
            $model->original_brand_id = isset($originalBrand['id']) ? $originalBrand['id'] : $model->original_brand_id;

            $model->mode = 'update';

            if($model->save()){
                
            }
            else{

                $errorString = ErrorGenerateComponent::generateErrorLabels($model->getErrors());
                Yii::$app->session->setFlash('danger', 'Gagal menyimpan barang karena kesalahan berikut: '.$errorString);
                $noProblem = false;
            }

            if($noProblem){

                $trans->commit();
                Yii::$app->session->setFlash('success', 'Barang '.LabelComponent::SUCCESS_SAVE);
                if (isset($_POST['saveandnew']) && $_POST['saveandnew'] == "1")
                    return $this->redirect(['create']);
                else
                    return $this->redirect(['index']);

            }            

            $trans->rollBack();
        }

        return $this->render('form', [
            'model' => $model,
            'mode' => 'update',
        ]);
    }

    public function actionDelete($id)
    {  

        $model  = $this->findModel($id);
        $model->mode = 'update';
        $model->is_deleted = 1;
        if(!$model->update()){
            print_r($model->getErrors());
            exit();
        }

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
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Barang '.LabelComponent::NOT_FOUND);
    }
}
