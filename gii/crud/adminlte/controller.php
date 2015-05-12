<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator app\gii\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}

/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
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
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();

        $pageSize = Yii::$app->session->get('admin.page.size', Yii::$app->params['admin.page.size']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, ['pageSize' => $pageSize]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pageSize' => $pageSize,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }

    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }

    <?php if ($generator->saveMultiple): ?>/**
     * Creates a new <?= $modelClass ?> model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        $related = $model->getRelatedModels();

        $params = Yii::$app->request->post();
        if ($model->load($params)) {
            Model::loadMultiple($related, $params);
            $isValid = $model->validate();
            $isValid = Model::validateMultiple($related) && $isValid;
            if ($isValid) {
                $model->save(false);
                foreach ($related as $rel) {
                    $rel->link('mainModel', $model);
                }
                Yii::$app->session->setFlash('data.saved', $model->getLabel());
                <?php if ($generator->saveAndReturn): ?>if (Yii::$app->request->post('continue')) {
                    return $this->redirect(['update', 'id' => $model->id]);
                }<?php endif ?>

                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'related'));
    }
<?php else: ?>/**
     * Creates a new <?= $modelClass ?> model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('data.saved', $model->getLabel());
            <?php if ($generator->saveAndReturn): ?>if (Yii::$app->request->post('continue')) {
                return $this->redirect(['update', 'id' => $model->id]);
            }<?php endif ?>

            return $this->redirect(['index']);
        }

        return $this->render('edit', compact('model'));
    }
<?php endif ?>

    <?php if ($generator->saveMultiple): ?>/**
     * Updates an existing <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
        $related = $model->getRelatedModels();

        $params = Yii::$app->request->post();
        if ($model->load($params)) {
            Model::loadMultiple($related, $params);
            $isValid = $model->validate();
            $isValid = Model::validateMultiple($related) && $isValid;
            if ($isValid) {
                $model->save(false);
                foreach ($related as $rel) {
                    $rel->link('mainModel', $model);
                }
                Yii::$app->session->setFlash('data.saved', $model->getLabel());
                <?php if ($generator->saveAndReturn): ?>if (Yii::$app->request->post('continue')) {
                    return $this->refresh();
                }<?php endif ?>

                return $this->redirect(['index']);
            }
        }

        return $this->render('edit', compact('model', 'related'));
    }
<?php else: ?>/**
     * Updates an existing <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('data.saved', $model->getLabel());
            <?php if ($generator->saveAndReturn): ?>if (Yii::$app->request->post('continue')) {
                return $this->refresh();
            }<?php endif ?>

            return $this->redirect(['index']);
        }

        return $this->render('edit', compact('model'));
    }
<?php endif ?>

    /**
     * Deletes an existing <?= $modelClass ?> model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        $this->findModel(<?= $actionParams ?>)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

