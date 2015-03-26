<?php
namespace app\models\admin;

use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class PasswordResetForm extends Model
{
    public $password;
    public $passwordRepeat;

    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        parent::__construct($config);
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException(Yii::t('admin', 'Missing password reset token.'));
        }
        $this->_user = Admin::findByPasswordResetToken($token);
        if (!$this->_user) {
            throw new InvalidParamException(Yii::t('admin', 'Invalid password reset token.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => Yii::t('admin', 'Password'),
            'passwordRepeat' => Yii::t('admin', 'Repeat password'),
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }

    public function getUser()
    {
        return $this->_user ? $this->_user : null;
    }

    public function getUsername()
    {
        return $this->_user ? $this->_user->username : null;
    }

}