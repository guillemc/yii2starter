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

        static::customizeSampleEnvFile();

        if ($io->askConfirmation('Generate/replace .env.php file? (y/n)')) {
            copy('.env-sample.php', '.env.php');
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

    public static function customizeSampleEnvFile()
    {
        $appId = strtolower(basename(getcwd()));

        $replaces = array(
            'my-app-id' => $appId,
            'mydbname' => $appId,
            'mydbname_test' => "$appId-test",
            'some random string - CHANGE IT!' => Installer::randomString(),
        );
        static::applyValues('.env-sample.php', $replaces);
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

class Installer extends \yii\composer\Installer
{
    public static function randomString()
    {
        return parent::generateRandomString();
    }
}
