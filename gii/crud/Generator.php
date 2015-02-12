<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\gii\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\crud\Generator
{
    public $saveAndReturn;

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CUSTOM CRUD Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates a controller and views that implement CRUD (Create, Read, Update, Delete)
            operations for the specified data model.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['saveAndReturn'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'saveAndReturn' => 'Implement "Save" / "Save and return"',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'saveAndReturn' => 'If checked, will place two buttons on the create/update forms: '
            . '"Save" (saves and continue editing) and "Save and return" (returns to list after saving)',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['saveAndReturn']);
    }

    public function getGridColumnSpec($column)
    {

        $nameColumns = ['name', 'title', 'username', 'firstname', 'lastname'];
        if (in_array($column->name, $nameColumns)) {
            return <<<EOT
['attribute' => '{$column->name}', 'format' => 'raw', 'value' => function (\$model) { return Html::a(\$model->{$column->name}, ['update', 'id' => \$model->id]); }],
EOT;
        }


        return false;
    }

    public function getDetailColumnSpec($column)
    {

        $tsColumns = ['created_at', 'updated_at', 'created', 'updated'];
        if (in_array($column->name, $tsColumns)) {
            return "'{$column->name}:datetime',";
        }

        return false;
    }
}
