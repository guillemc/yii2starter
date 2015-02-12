<?php

namespace app\controllers\admin;

use Yii;
use app\models\admin\LoginForm;

class SiteController extends BaseController
{

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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $this->layout = 'bare';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionProfile()
    {
        $model = new \app\models\admin\ProfileForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->saveUser()) {
            Yii::$app->session->setFlash('profile.success', true);
            return $this->refresh();
        }
        return $this->render('profile', compact('model'));
    }

    public function actionRequestPasswordReset()
    {
        $this->layout = 'bare';

        $model = new \app\models\admin\PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            Yii::$app->getSession()->setFlash('request.success', $model->sendEmail() ? $model->email : false);
            return $this->refresh();
        }
        return $this->render('request_password_reset', [
            'model' => $model,
        ]);
    }

    public function actionPasswordReset($token)
    {
        $this->layout = 'bare';

        $tokenError = false;
        try {
            $model = new \app\models\admin\PasswordResetForm($token);
        } catch (\yii\base\InvalidParamException $e) {
            $tokenError = $e->getMessage();
        }

        if (!$tokenError && $model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->resetPassword()) {
                Yii::$app->user->login($model->getUser());
                Yii::$app->session->setFlash('password.reset', true);
                $this->goHome();
            }
            $model->addError('general', 'Database error');
        }

        return $this->render('password_reset', compact('model', 'tokenError'));
    }
}
