<?php

namespace install;

use Composer\Script\Event;

class App
{

    protected static $writableDirectories = ['runtime', 'web/assets', 'web/files'];

    /*
     * To manually run all the post-create-project scripts, execute: composer run-script post-create-project-cmd
     */
    public static function postCreateProject(Event $e)
    {
        //$composer = $e->getComposer();
        $io = $e->getIO();

        if ($io->askConfirmation('Generate/replace .env.php file? (y/n)')) {
            static::copyEnv();
            $io->write('Generated .env.php');
        } elseif (!file_exists('.env.php')) {
            $io->write('Remember to create file .env.php with your own settings, using .env-sample.php as a base.');
        }

        if ($io->askConfirmation('Set write permissions using the setfacl command? (y/n)')) {
            static::setPermissions($io);
        } else {
            $io->write('Remember to give the webserver write permissions over these directories: '.implode(', ', static::$writableDirectories));
        }


    }

    public static function setPermissions($io)
    {
        $directories = implode(' ', static::$writableDirectories);
        $command = "sudo setfacl -R -m u:www-data:rwX -m u:`whoami`:rwX ".$directories;
        $io->write($command);
        exec($command);

        $command = "sudo setfacl -dR -m u:www-data:rwX -m u:`whoami`:rwX ".$directories;
        $io->write($command);
        exec($command);
    }

    public static function copyEnv()
    {
        copy('.env-sample.php', '.env.php');

        $appId = strtolower(basename(getcwd()));

        $replaces = array(
            'my-app-id' => $appId,
            'mydbname' => $appId,
            'mydbname_test' => "$appId-test",
            'some random string - CHANGE IT!' => static::generateRandomString(),
        );
        static::applyValues('.env.php', $replaces);
    }

    /* copied from vendor/yiisoft/yii2-composer/Installer.php */
    protected static function generateRandomString()
    {
        if (!extension_loaded('mcrypt')) {
            throw new \Exception('The mcrypt PHP extension is required by Yii2.');
        }
        $length = 32;
        $bytes = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        return strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
    }


    protected static function applyValues($target, $replaces)
    {
        file_put_contents(
            $target,
            strtr(
                file_get_contents($target),
                $replaces
            )
        );
    }
}