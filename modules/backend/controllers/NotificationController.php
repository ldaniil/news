<?php

namespace app\modules\backend\controllers;

use yii;
use yii\data\ArrayDataProvider;
use app\entities\Notification;
use app\modules\backend\components\Controller;
use app\models\NotificationSettingModel;
use app\models\search\NotificationSettingSearch;

class NotificationController extends Controller
{
	public function actionIndex()
	{
		$settingSearch = new NotificationSettingSearch;
		$settingSearch->load(Yii::$app->request->get());

		return $this->render('index', ['settingSearch' => $settingSearch]);
	}

	public function actionSave()
	{
		$id = Yii::$app->request->get('id');

		if ($id) {
			$setting = NotificationSettingModel::findOne($id);
		}

		if (empty($setting)) {
			$setting = new NotificationSettingModel;
		}

		if ($setting->load(Yii::$app->request->post()) && $setting->save())
		{
			$this->redirect('index');
		}
		
		$roles = Yii::$app->authManager->getRoles();
		
		$transports = Notification::getTransportTypes();

		// Формируем данные для отображания маршрутов получения уведомлений
		$data = [];

		foreach ($roles as $role) {
			$route = !empty($setting->routes[$role->name]) ? $setting->routes[$role->name] : null;

			$data[] = [
				'enable'     => !empty($route) ? $route['enable'] : 0,
				'role' 		 => $role->name,
				'exclude'    => !empty($route) ? $route['exclude'] : [],
				'transports' => !empty($route) ? $route['transports'] : [],
			];
		}

		$routeDataProvider = new ArrayDataProvider([
			'allModels' => $data,
		]);

		return $this->render('save', [
			'setting' 	 		=> $setting,
			'transports' 		=> $transports,
			'routeDataProvider' => $routeDataProvider,
		]);
	}
}