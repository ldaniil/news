<?php

namespace app\controllers;

use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\User;
use app\models\search\NewsSearch;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $newsSearch = new NewsSearch;
        $newsSearch->load(Yii::$app->request->get());

        return $this->render('index', ['newsSearch' => $newsSearch]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegistration()
    {
        $registration = new RegistrationForm();

        if ($registration->load(Yii::$app->request->post())) {
            $registration->register();
        }

        return $this->render('registration', ['registration' => $registration]);
    }

    /**
     * Активация учетной записи
     *
     * @param $token
     *
     * @return \yii\web\Response
     */
    public function actionActivation($token)
    {
        $user = User::findByToken($token);

        if ($user && $user->activate()) {
            Yii::$app->user->login($user, 3600 * 24 * 30);
            return $this->goHome();
        }

        return $this->render('activation');
    }
}
