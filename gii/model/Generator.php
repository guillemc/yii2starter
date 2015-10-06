<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\gii\model;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\base\NotSupportedException;

/**
 * This generator will generate one or multiple ActiveRecord classes for the specified database table.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yii\gii\generators\model\Generator
{
    public $labelField = 'name, title';
    public $timestampFields = 'created_at, updated_at';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'CUSTOM Model Generator';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return 'This generator generates an ActiveRecord class for the specified database table.';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['labelField', 'timestampFields'], 'trim'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'labelField' => 'Label Field',
            'timestampFields' => 'Timestamp Fields',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'labelField' => 'Field to serve as the model label. E.g. title, name, username, etc. '
            . 'Multiple fields are allowed, separated by commas. In this case the first matching field will be the one to be used.',
            'timestampFields' => 'Fields that act as timestamps. E.g. created_at, updated_at. '
            . 'No rules will be generated for these fields.',
        ]);
    }


    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['labelField', 'timestampFields']);
    }

    public function generate()
    {
        $files = [];
        $relations = $this->generateRelations();
        $db = $this->getDbConnection();
        foreach ($this->getTableNames() as $tableName) {
            // model :
            $modelClassName = $this->generateClassName($tableName);
            $queryClassName = ($this->generateQuery) ? $this->generateQueryClassName($modelClassName) : false;
            $tableSchema = $db->getTableSchema($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
                'queryClassName' => $queryClassName,
                'tableSchema' => $tableSchema,
                'labels' => $this->generateLabels($tableSchema),
                'rules' => $this->generateRules($tableSchema),
                'relations' => isset($relations[$tableName]) ? $relations[$tableName] : [],

                //begin custom code
                'labelField' => $this->getLabelField($tableSchema),
                //end custom code
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->ns)) . '/' . $modelClassName . '.php',
                $this->render('model.php', $params)
            );
        }

        return $files;
    }

    public function getLabelField($table)
    {
        if (!$this->labelField) return null;
        $a = $this->explode($this->labelField);
        $columns = $table->columns;
        foreach ($a as $field) {
            if (isset($columns[$field])) return $field;
        }
        return null;
    }

    public function generateRules($tableSchema)
    {
        $ts = $this->getTimestampFields($tableSchema);
        $table = $tableSchema;
        if ($ts) {
            $table = clone $tableSchema;
            foreach ($ts as $field) {
                unset($table->columns[$field]);
            }
        }
        $rules = parent::generateRules($table);

        $nulls = [];
        foreach ($table->columns as $column) {
            if ($column->allowNull && $column->defaultValue === null) {
                $nulls[] = $column->name;
            }
        }
        if ($nulls) {
            array_unshift($rules, "[['" . implode("', '", $nulls) . "'], 'trim']");
            $rules[] = "[['" . implode("', '", $nulls) . "'], 'default', 'value' => null]";
        }
        return $rules;
    }

    public function getTimestampFields($table)
    {
        if (!$this->timestampFields) return [];
        $a = $this->explode($this->timestampFields);
        $columns = $table->columns;
        return array_intersect(array_keys($columns), $a);
    }

    protected function explode($str)
    {
        return $str ?  preg_split("/[\s,]+/", $str, -1, PREG_SPLIT_NO_EMPTY) : [];
    }

    public function generateLabels($table)
    {
        $labels = parent::generateLabels($table);
        array_walk($labels, function (&$value) {
            if (substr_compare($value, ' id', -3, 3, true) === 0) {
                $value = substr($value, 0, -3); // remove ID
            }
            $value = ucfirst(strtolower($value));
        });
        return $labels;
    }

}

