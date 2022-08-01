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
use common\models\ChangePassword;
use frontend\models\SignupForm;
use yii\web\UploadedFile;
use yii\imagine\Image;  
use Imagine\Image\Box;

class UserController extends BaseController
{
    public function actionIndex()
    {
		BaseController::$page_caption = 'User';
		
		$searchModel = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
    }
	
	public function actionCreate()
    {
		BaseController::$page_caption = 'Tambah User';
		
		$model = new SignupForm;
		$modelEmployee = new Employee;
		
        if ($model->load(Yii::$app->request->post())) {
			$transaction = Yii::$app->db->beginTransaction();
			
            if ($user = $model->signup()) {
				$auth = Yii::$app->authManager;
				
				foreach($model->roles as $roles) {
					$role = $auth->getRole($roles);
					$auth->assign($role, $user->id);
				}

				$modelEmployee->attributes = $_POST['Employee'];
				$modelEmployee->employee_address = !empty($modelEmployee->employee_address) ? nl2br($modelEmployee->employee_address) : NULL;
				$modelEmployee->employee_note = !empty($modelEmployee->employee_note) ? nl2br($modelEmployee->employee_note) : NULL;
				$modelEmployee->user_id = $user->id;
				if ($modelEmployee->save()) {
					$transaction->commit();
					Yii::$app->session->setFlash('success', 'User '.LabelComponent::SUCCESS_SAVE);
					return $this->redirect(['index']);
				}
				else {
					$errorString = ErrorGenerateComponent::generateErrorLabels($modelEmployee->getErrors());
					Yii::$app->session->setFlash('danger', 'Gagal membuat user karena kesalahan berikut: '.$errorString);
				}

				$modelEmployee->employee_address = !empty($modelEmployee->employee_address) ? strip_tags($modelEmployee->employee_address) : NULL;
				$modelEmployee->employee_note = !empty($modelEmployee->employee_note) ? strip_tags($modelEmployee->employee_note) : NULL;
            }
			
			$transaction->rollback();
        }
		
        return $this->render('form', [
			'model' => $model,
			'modelEmployee' => $modelEmployee,
		]);
    }
	
	public function actionUpdate($id)
    {
		BaseController::$page_caption = 'Update User';
		
		$user = $this->findModel($id);
		$model = new SignupForm();
		$model->scenario = SignupForm::SCENARIO_UPDATE_USER;
		$model->username = $user->username;
		$auth = Yii::$app->authManager;
		$roles = $auth->getRolesByUser($id);	
		foreach($roles as $role) {
			$model->roles[] = $role->name;
		}

		$modelEmployee = isset($user->employees[0]) ? Employee::find()->andWhere('id=:id', [':id' => $user->employees[0]->id])->one() : new Employee;
		$modelEmployee->employee_address = !empty($modelEmployee->employee_address) ? strip_tags($modelEmployee->employee_address) : NULL;
		$modelEmployee->employee_note = !empty($modelEmployee->employee_note) ? strip_tags($modelEmployee->employee_note) : NULL;
		
        if ($model->load(Yii::$app->request->post())) {
			$transaction = Yii::$app->db->beginTransaction();
			
			$user->username = $model->username;
			if (!empty($model->password)) {
				$user->setPassword($model->password);
			}
			
            if ($user->save()) {
				$auth->revokeAll($id);
				foreach($model->roles as $roles) {
					$role = $auth->getRole($roles);
					$auth->assign($role, $user->id);
				}
				
				$modelEmployee->attributes = $_POST['Employee'];
				$modelEmployee->employee_address = !empty($modelEmployee->employee_address) ? nl2br($modelEmployee->employee_address) : NULL;
				$modelEmployee->employee_note = !empty($modelEmployee->employee_note) ? nl2br($modelEmployee->employee_note) : NULL;
				$modelEmployee->user_id = $user->id;
				if ($modelEmployee->save()) {
					$transaction->commit();
					Yii::$app->session->setFlash('success', 'User '.LabelComponent::SUCCESS_UPDATE);
					return $this->redirect(['index']);
				}
				else {
					$errorString = ErrorGenerateComponent::generateErrorLabels($modelEmployee->getErrors());
					Yii::$app->session->setFlash('danger', 'Gagal membuat user karena kesalahan berikut: '.$errorString);
				}

				$modelEmployee->employee_address = !empty($modelEmployee->employee_address) ? strip_tags($modelEmployee->employee_address) : NULL;
				$modelEmployee->employee_note = !empty($modelEmployee->employee_note) ? strip_tags($modelEmployee->employee_note) : NULL;
            }
			
			$transaction->rollback();
        }
		
        return $this->render('form', [
			'model' => $model,
			'user' => $user,
			'modelEmployee' => $modelEmployee,
		]);
    }
	
	public function actionDelete($id) 
	{
		$model = $this->findModel($id);
		if ($model) {
			$model->status = User::STATUS_DELETED;
			$model->update();
			
			Yii::$app->session->setFlash('success', 'User '.LabelComponent::SUCCESS_DELETE);
			return $this->redirect(['index']);
		}
	}

	
	public function actionChangepassword()
	{
		BaseController::$page_caption = 'Ganti Password';

        $modelUser = new ChangePassword();
        if ($modelUser->load(Yii::$app->getRequest()->post()) && $modelUser->change()) {
            //Yii::$app->user->logout();
            //return $this->goHome();
            Yii::$app->session->setFlash('success', 'Password '.LabelComponent::SUCCESS_UPDATE);
            return $this->redirect(['changepassword']);
        }
        return $this->render('changepassword', [
			'modelUser' => $modelUser,
        ]);
	}

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('User '.LabelComponent::NOT_FOUND);
        }
    }
}
