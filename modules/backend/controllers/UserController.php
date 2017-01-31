<?php

namespace app\modules\backend\controllers;

use yii;
use app\modules\backend\components\Controller;
use app\models\RegistrationForm;
use app\models\search\UserSearch;

class UserController extends Controller
{
	public function actionIndex()
	{
		$userSearch = new UserSearch;
		$userSearch->load(Yii::$app->request->get());

		return $this->render('index', ['userSearch' => $userSearch]);
	}

	public function actionRegistration()
	{
		$registration = new RegistrationForm();
		$registration->setScenario(RegistrationForm::SCENARIO_BACKEND);

		if ($registration->load(Yii::$app->request->post())) {
			if ($registration->register()) {
				return $this->redirect('/administration/user');
			}
		}

		return $this->render('registration', ['registration' => $registration]);
	}
}