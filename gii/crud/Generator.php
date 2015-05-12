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
    public $saveMultiple;

    public $tsColumns = ['created_at', 'updated_at', 'created', 'updated'];
    public $nameColumns = ['name', 'title', 'username', 'firstname', 'lastname'];

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
            [['saveAndReturn', 'saveMultiple'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'saveAndReturn' => 'Implement "Save" / "Save and return"',
            'saveMultiple' => 'Save related models',
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
            'saveMultiple' => 'If checked, will generate skeleton code for validating and saving related models as well as the main model',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['saveAndReturn', 'saveMultiple']);
    }

    public function getGridColumnSpec($column, $format)
    {
        if (in_array($column->name, $this->nameColumns)) {
            return <<<EOT
[
                'attribute' => '{$column->name}',
                'format' => 'raw',
                'value' => function (\$model) {
                    return Html::a(\$model->{$column->name}, ['update', 'id' => \$model->id]);
                },
            ],
EOT;
        }
        if (in_array($column->name, $this->tsColumns)) {
            return <<<EOT
[
                'attribute' => '{$column->name}',
                'format' => 'datetime',
                'headerOptions' => ['class' => 'sort-numerical'],
                'filter' => false,
                'contentOptions' => ['class' => 'text-center'],
            ],
EOT;
        }
        if ($this->isBoolean($column)) {
            return <<<EOT
[
                'attribute' => '{$column->name}',
                'filter' => ['1' => Yii::t('admin', 'Yes'), '0' => Yii::t('admin', 'No')],
                'value' => function (\$model) {
                    return \$model->{$column->name} ? Yii::t('admin', 'Yes') : Yii::t('admin', 'No');
                },
                'contentOptions' => ['class' => 'text-center'],
            ],
EOT;
        }
        return <<<EOT
[
                'attribute' => '{$column->name}',
                'format' => '{$format}',
            ],
EOT;

    }

    public function getDetailColumnSpec($column, $format)
    {

        if (in_array($column->name, $this->tsColumns)) {
            return "'{$column->name}:datetime',";
        }
        if ($this->isBoolean($column)) {
            return "'{$column->name}:boolean',";
        }
        return <<<EOT
[
                'attribute' => '{$column->name}',
                'format' => '{$format}',
            ],
EOT;
    }


    public function isBoolean($column)
    {
        return $column->phpType == 'boolean' || ($column->phpType == 'integer' && $column->size == 1);
    }


    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false || !isset($tableSchema->columns[$attribute])) {
            return parent::generateActiveField($attribute);
        }
        $column = $tableSchema->columns[$attribute];

        if ($this->isBoolean($column)) {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        }

        return parent::generateActiveField($attribute);
    }
}

