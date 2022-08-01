<?php

namespace frontend\modules\setting\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use app\components\BaseController;
use frontend\components\LabelComponent;
use frontend\components\AccessComponent;
use frontend\models\AuthItemSearch;
use common\models\AuthItem;
use common\models\User;

class RolesController extends BaseController
{
    public function actionIndex()
    {
		BaseController::$page_caption = 'Peran';
		
		$searchModel = new AuthItemSearch();
		$searchModel->type = AuthItem::TYPE_ROLE;
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
    }
	
	public function actionCreate()
    {
		BaseController::$page_caption = 'Tambah Peran';
		
		$model = new AuthItem;
		$model->type = AuthItem::TYPE_ROLE;
		
		if (isset($_POST['AuthItem'])) {
			$model->attributes = $_POST['AuthItem'];
			
			if ($model->validate()) {
				$auth = Yii::$app->authManager;
				
				$newRole = $auth->createRole($model->name);
				$newRole->description = $model->description;
				$auth->add($newRole);
				
				foreach($model->access as $access) {
					$permission = $auth->getPermission($access);
					$auth->addChild($newRole, $permission);
				}
				
				Yii::$app->session->setFlash('success', 'Peran '.LabelComponent::SUCCESS_SAVE);
				return $this->redirect(array('index'));
			}
		}
		
		$accesses = AccessComponent::getArrOfAccess();
		
        return $this->render('form', [
			'model' => $model,
			'accesses' => $accesses,
		]);
    }
	
	public function actionUpdate($id)
	{
		BaseController::$page_caption = 'Update Peran';
		
		$model = $this->findModel($id);
		$model->access = $model->loadArrayOfChildren();
		$oldName = $model->name;
		
		if (isset($_POST['AuthItem'])) {
			$model->attributes = $_POST['AuthItem'];
			
			if ($model->validate()) {
				$transaction = Yii::$app->db->beginTransaction();
				
				$auth = Yii::$app->authManager;
				
				$role = $auth->getRole($oldName);
				
				$auth->removeChildren($role);
				
				$role->name = $model->name;
				$role->description = $model->description;
				$auth->update($oldName, $role);
				
				foreach($model->access as $access) {
					$permission = $auth->getPermission($access);
					$auth->addChild($role, $permission);
				}
				
				$transaction->commit();
				
				Yii::$app->session->setFlash('success', 'Peran '.LabelComponent::SUCCESS_UPDATE);
				return $this->redirect(array('index'));
			}
		}
		
		$accesses = AccessComponent::getArrOfAccess();
		
		return $this->render('form', [
			'model' => $model,
			'accesses' => $accesses,
		]);
	}
	
	public function actionDelete($id) {
		$model = $this->findModel($id);
		if ($model) {
			$noProblem = true;
			$transaction = Yii::$app->db->beginTransaction();
			
			$auth = Yii::$app->authManager;
			
			$role = $auth->getRole($model->name);
			
			$users = User::find()->all();
			foreach($users as $user) {
				$auth->revoke($role, $user->id);
			}
			
			if (!$auth->removeChildren($role)) {
				$noProblem = false;
				Yii::$app->session->setFlash('danger', 'Gagal menghapus child');
			}
			if (!$auth->remove($role)) {
				$noProblem = false;
				Yii::$app->session->setFlash('danger', 'Gagal menghapus role');
			}
			
			if ($noProblem) {
				$transaction->commit();
				Yii::$app->session->setFlash('success', 'Peran '.LabelComponent::SUCCESS_DELETE);
				return $this->redirect(array('index'));
			}
		}
	}

    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Peran '.LabelComponent::NOT_FOUND);
        }
    }
}
