<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $pwd
 * @property string $pwd_reset_token
 * @property string $email
 * @property string $avatar
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public $avatarUpload;
    public $avatarRemove;
    public $password;
    public $passwordRepeat;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'pwd_reset_token' => $token,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.token_expire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }



    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->pwd);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->pwd = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->pwd_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->pwd_reset_token = null;
    }

    public static function afterLogin($event)
    {
        $model = $event->identity;
        $model->updateAttributes(['last_login' => time()]);
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'trim'],
            [['username', 'email'], 'required'],
            [['password'], 'required', 'on' => 'create'],
            [['password', 'username', 'email'], 'string', 'max' => 128],
            [['password'], 'string', 'min' => 6],
            [['email'], 'email'],
            [['username', 'email'], 'unique'],
            [['passwordRepeat'], 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false],

            [['avatarUpload'], 'image', 'extensions' => ['png', 'jpg', 'gif'], 'maxSize' => 2*1024*1024 ],
            [['avatarRemove'], 'boolean'],

        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => \app\components\behaviors\ImageUploadBehavior::className(),
                'config' => [
                    'avatar' => [
                        'upload' => 'avatarUpload',
                        'remove' => 'avatarRemove',
                    ],
                ],
                'resizeConfig' => [
                    'avatar' => [
                        'thumb' => [
                            'suffix' => '-t',
                            'w' => 150,
                            'h' => 150,
                            'method' => 'crop',
                        ],
                    ],
                ],
                'dirName' => 'files/users',
                'fileNameCallback' => function ($owner, $fname, $ext, $attr) {
                    return floor($owner->id / 100).'/'.$owner->id.'-'.$fname;
                },
            ],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->password && $this->passwordRepeat === $this->password) {
                $this->setPassword($this->password);
            }
            return true;
        }
        return false;
    }

    /* public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // ...custom code here...
            return true;
        }
        return false;
    } */

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth key'),
            'pwd' => Yii::t('app', 'Password'),
            'password' => Yii::t('app', 'Password'),
            'passwordRepeat' => Yii::t('app', 'Repeat password'),
            'pwd_reset_token' => Yii::t('app', 'Password reset token'),
            'email' => Yii::t('app', 'Email'),
            'avatar' => Yii::t('app', 'Avatar'),
            'avatarUpload' => Yii::t('app', 'Avatar'),
            'avatarRemove' => Yii::t('app', 'Remove avatar'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
        ];
    }


    public function getLabel()
    {
        return $this->username;
    }
}