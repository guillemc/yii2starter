<?php
namespace app\controllers\admin;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

class BaseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'controllers' => ['site'],
                        'actions' => ['login', 'error', 'request-password-reset', 'password-reset'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'delete' => ['post', 'delete'],
                ],
            ],
        ];
    }


    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $request = Yii::$app->getRequest();
            $response = Yii::$app->getResponse();

            //is this a set-language post request?
            if ($request->isPost && null !== ($language = $request->get('set-language'))) {

                $languages = Yii::$app->params['languages'];
                if (isset($languages[$language])) {
                    $response->cookies->add(new \yii\web\Cookie([
                        'name' => 'language',
                        'value' => $language,
                    ]));
                }
                $response->refresh();
                return false;
            }

            //else, read language cookie
            $cookies = $request->cookies;
            if (!empty($cookies['language'])) {
                Yii::$app->language = $cookies['language']->value;
            }

            //page size request?
            $pageSize = filter_input(INPUT_GET, 'page_size', FILTER_VALIDATE_INT);
            if ($pageSize && in_array($pageSize, Yii::$app->params['admin.page.sizes'])) {
                Yii::$app->session->set('admin.page.size', $pageSize);
                unset($_GET['page_size']);
            }

            return true;
        }
        return false;
    }
}