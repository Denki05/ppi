<?php

namespace frontend\modules\setting\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\BaseController;
use frontend\components\LabelComponent;
use frontend\models\UserSearch;
use common\components\ErrorGenerateComponent;
use common\models\User;
use common\models\Employee;
use yii\web\UploadedFile;
use yii\imagine\Image;  
use Imagine\Image\Box;

class ProfileController extends BaseController
{
    
	public function actionMyprofile()
	{
		BaseController::$page_caption = 'Profil Saya';

		$model = Employee::find()->andWhere('user_id=:id', [':id' => Yii::$app->user->id])->one();
		
        if(!$model){
			$model = new Profile();
		}
        else{
            $model->employee_address = strip_tags($model->employee_address);
        }
		

        if($model->load(Yii::$app->request->post())){
            $model->employee_address = nl2br($model->employee_address);
            $model->user_id = Yii::$app->user->id;
            if ($model->save()) {

            	Yii::$app->session->setFlash('success', 'Profile '.LabelComponent::SUCCESS_UPDATE);
            	return $this->redirect(['myprofile']);
            }else{
            	print_r($model->getErrors());
            	exit();
            }
            $model->employee_address = strip_tags($model->employee_address);
        }
        
        return $this->render('myprofile', [
            'model' => $model,
        ]);
	}
}
