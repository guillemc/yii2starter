<?php

namespace app\controllers\admin;

use Yii;
use app\models\User;
use app\models\admin\UserSearch;
use app\controllers\admin\BaseController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    /*
    // Behaviors will usually be defined in the parent class
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    } */

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();

        $pageSize = Yii::$app->session->get('admin.page.size', Yii::$app->params['admin.page.size']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['pageSize' => $pageSize]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageSize' => $pageSize,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('data.saved', $model->getLabel());
            if (Yii::$app->request->post('continue')) {
                return $this->redirect(['update', 'id' => $model->id]);
            }
            return $this->redirect(['index']);
        }

        return $this->render('edit', compact('model'));
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('data.saved', $model->getLabel());
            if (Yii::$app->request->post('continue')) {
                return $this->refresh();
            }
            return $this->redirect(['index']);
        }

        return $this->render('edit', compact('model'));
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}