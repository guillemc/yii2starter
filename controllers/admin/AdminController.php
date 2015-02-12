<?php

namespace app\controllers\admin;

use Yii;
use app\models\admin\Admin;
use app\models\admin\AdminSearch;
use app\controllers\admin\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * AdminController implements the CRUD actions for Admin model.
 */
class AdminController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        //Only can be accessed by root
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {
                        return Yii::$app->user->identity->isRoot();
                    }
                ],
            ],
        ];
        return $behaviors;
    }

    /**
     * Lists all Admin models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminSearch();

        $pageSize = Yii::$app->session->get('admin.page.size', Yii::$app->params['admin.page.size']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['pageSize' => $pageSize]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Displays a single Admin model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Admin model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Admin();
        $m = new \app\models\admin\ProfileForm($model);
        $m->scenario = 'create';

        if ($m->load(Yii::$app->request->post()) && $m->validate() && $m->saveUser()) {
            return $this->redirect(['index']);
        }

        return $this->render('edit', compact('model', 'm'));
    }

    /**
     * Updates an existing Admin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $m = new \app\models\admin\ProfileForm($model);

        if ($m->load(Yii::$app->request->post()) && $m->validate() && $m->saveUser()) {
            return $this->redirect(['index']);
        }

        return $this->render('edit', compact('model', 'm'));
    }

    /**
     * Deletes an existing Admin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Admin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Admin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}