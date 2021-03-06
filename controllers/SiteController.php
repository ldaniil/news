<?php

namespace app\controllers;

use app\models\NewsModel;
use yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use app\models\LoginForm;
use app\models\RegistrationForm;
use app\models\ActivationForm;
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
                'only' => ['view', 'logout'],
                'rules' => [
                    [
                        'actions' => ['view', 'logout'],
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

        $dataProvider = $newsSearch->search();
        $dataProvider->pagination->route = '/';

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $news = NewsModel::findOne($id);

        return $this->render('view', ['news' => $news]);
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
     * Активация
     * учетной записи
     *
     * @param $token
     *
     * @return string
     * @throws HttpException
     */
    public function actionActivation($token)
    {
        $user = User::findByToken($token);

        if ($user && Yii::$app->user->isGuest) {
            if ($user->status == User::STATUS_REGISTRED) {
                $activation = new ActivationForm(['user' => $user]);

                if ($activation->scenario == ActivationForm::SCENARIO_SET_PASSWORD) {
                    $activation->load(Yii::$app->request->post());
                }

                if ($activation->activate()) {
                    return $this->goHome();
                }

                return $this->render('activation', ['activation' => $activation]);
            } else {
                return $this->goHome();
            }
        } else {
            throw new HttpException(404);
        }
    }
}
