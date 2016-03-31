<?php
namespace app\models\admin;

use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ProfileForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $passwordRepeat;

    private $_user;

    public function __construct($user, $config = [])
    {
        parent::__construct($config);
        $this->_user = $user;
        $this->username = $user->username;
        $this->email = $user->email;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'trim'],
            ['username', 'required'],

            ['password', 'required', 'on' => 'create'],

            [['username', 'email'], 'validateUnique', ],
            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            ['email', 'default', 'value' => null],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],
        ];
    }

    public function validateUnique($attribute, $params)
    {
        $query = Admin::find();
        /* @var $query \yii\db\ActiveQuery */
        $query->where([$attribute => $this->$attribute]);
        if (!$this->_user->isNewRecord) {
            $query->andWhere(['!=', 'id', $this->_user->id]);
        }
        if ($query->exists()) {
            $this->addError($attribute, Yii::t('admin', '{attribute} "{value}" is already in use.', ['attribute' => $this->getAttributeLabel($attribute), 'value' => $this->$attribute]));
        }
    }

    public function attributeLabels() {
        return [
            'password' => Yii::t('admin', 'Password'),
            'passwordRepeat' => Yii::t('admin', 'Repeat password'),
            'email' => Yii::t('admin', 'Email'),
            'username' => Yii::t('admin', 'Username'),
        ];
    }

    public function saveUser()
    {
        $this->_user->username = $this->username;
        $this->_user->email = $this->email;
        if ($this->password) {
            $this->_user->setPassword($this->password);
        }

        return $this->_user->save();
    }

}