<?php
namespace app\models\admin;

use Yii;
use app\models\admin\Admin;
use yii\base\Model;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\app\models\admin\Admin',
                'message' => Yii::t('admin', 'No matching user found.'),
            ],
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $admin Admin */
        $admin = Admin::findOne([
            'email' => $this->email,
        ]);

        if ($admin) {
            if (!Admin::isPasswordResetTokenValid($admin->pwd_reset_token)) {
                $admin->generatePasswordResetToken();
            }

            if ($admin->save()) {
                return \Yii::$app->mailer->compose(['html' => 'admin/password_reset.html.php', 'text' => 'admin/password_reset.txt.php'], ['user' => $admin])
                    ->setFrom([Yii::$app->params['from.email'] => Yii::$app->params['from.name']])
                    ->setTo($this->email)
                    ->setSubject(Yii::t('admin', 'Password reset for {appName}'), ['appName' => Yii::$app->name])
                    ->send();
            }
        }

        return false;
    }
}