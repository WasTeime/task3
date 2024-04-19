<?php

namespace admin\controllers;

use admin\models\LoginForm;
use Yii;
use yii\filters\{AccessControl, VerbFilter};
use yii\web\{Controller, ErrorAction, Response};
use yii\captcha\CaptchaAction;
use zakurdaev\editorjs\actions\UploadImageAction;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup', 'info'],
                'rules' => [
//                    [
//                        'actions' => ['signup'],
//                        'allow' => true,
//                        'roles' => ['?'],
//                    ],
                    [
                        'actions' => ['logout', 'info'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'upload-file' => [
                'class' => UploadImageAction::class,
                'mode' => UploadImageAction::MODE_FILE,
                'url' => '/uploads',
                'path' => '@uploads',
                'validatorOptions' => [
                    'maxWidth' => 19200,
                    'maxHeight' => 10800
                ]
            ],
            'fetch-url' => [
                'class' => UploadImageAction::class,
                'mode' => UploadImageAction::MODE_URL,
                'url' => '/uploads',
                'path' => '@uploads',
            ]
        ];
    }

    /**
     * Displays homepage.
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(Yii::$app->request->referrer);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionInfo(): string
    {
        return $this->render('info');
    }
}
