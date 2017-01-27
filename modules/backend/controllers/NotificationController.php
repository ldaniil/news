<?php

namespace app\modules\backend\controllers;

use yii;
use yii\data\ArrayDataProvider;
use app\entities\Notification;
use app\entities\notification\sender\Administrator as SenderAdministrator;
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

		$routeDataProvider = $this->getRouteDataProvider($setting, $roles);

		return $this->render('save', [
			'setting' 	 		=> $setting,
			'transports' 		=> $transports,
			'routeDataProvider' => $routeDataProvider,
		]);
	}

	/**
	 * Отправка уведомления
	 *
	 * @return string
	 */
	public function actionSend()
	{
		$success = false;

		$setting = new NotificationSettingModel;

		if ($setting->load(Yii::$app->request->post()))
		{
			$sender = new SenderAdministrator();

			$success = Yii::$app->notification->send($sender, $setting);

			if ($success) {
				$setting = new NotificationSettingModel;
			}
		}

		$roles = Yii::$app->authManager->getRoles();

		$transports = Notification::getTransportTypes();

		$routeDataProvider = $this->getRouteDataProvider($setting, $roles);

		return $this->render('send', [
			'success'			=> $success,
			'setting' 	 		=> $setting,
			'transports' 		=> $transports,
			'routeDataProvider' => $routeDataProvider,
		]);
	}

	/**
	 * Возвращает dataProvider маршрутов уведомления
	 *
	 * @param NotificationSettingModel $setting
	 * @param yii\rbac\Role[] $roles
	 *
	 * @return ArrayDataProvider
	 */
	protected function getRouteDataProvider($setting, $roles)
	{
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

		return new ArrayDataProvider([
			'allModels' => $data,
		]);
	}
}