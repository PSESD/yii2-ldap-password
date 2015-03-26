<?php
/**
 * @link http://www.psesd.org/
 *
 * @copyright Copyright (c) 2015 Puget Sound Educational Service District
 * @license https://raw.githubusercontent.com/PSESD/yii2-ldap-password/master/LICENSE
 */

namespace psesd\ldapPassword\setup\tasks;

use canis\setup\Exception;
use canis\composer\TwigRender;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;

class Environment extends \canis\setup\tasks\BaseTask
{
		/**
     * @inheritdoc
     */
    public function getTitle()
    {
        return 'Environment';
    }

    /**
     * @inheritdoc
     */
    public function test()
    {
        if ($this->setup->isEnvironmented) {
            try {
                $oe = ini_set('display_errors', 0);
                $dbh = ldap_connect (CANIS_APP_LDAP_HOST, CANIS_APP_LDAP_PORT);
                if (!$dbh) {
                    throw new Exception("Unable to connect to ldap server! Please verify your settings in <code>env.php</code>.");
                }
                ini_set('display_errors', $oe);
            } catch (\Exception $e) {
                throw new Exception("Unable to connect to ldap server! Please verify your settings in <code>env.php</code>.");
            }
        }

        return $this->setup->isEnvironmented && $this->setup->version <= $this->setup->instanceVersion;
    }

    /**
     *
     */
    protected static function generateRandomString()
    {
        if (!extension_loaded('openssl')) {
            throw new \Exception('The OpenSSL PHP extension is required by Yii2.');
        }
        $length = 120;
        $bytes = openssl_random_pseudo_bytes($length);
        return strtr(substr(base64_encode($bytes), 0, $length), '+/=', '_-.');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->fields) {
            $input = $this->input;
            $upgrade = false;
        } else {
            $input = [];
            $input['app'] = [];
            $input['app']['name'] = $this->setup->app()->name;
            $input['app']['template'] = 'development';

            $input['ldap'] = [];
            $input['ldap']['host'] = CANIS_APP_LDAP_HOST;
            $input['ldap']['port'] = CANIS_APP_LDAP_PORT;
            $upgrade = true;
        }

        $input['templateDirectory'] = $this->setup->environmentTemplatesPath . DIRECTORY_SEPARATOR . $input['app']['template'];
        $input['version'] = $this->setup->version;
        $input['app']['id'] = self::generateId($input['app']['name']);
        if ($this->setup->app()) {
            $input['salt'] = $this->setup->app()->params['salt'];
            $input['cookieValidationString'] = $this->setup->app()->request['cookieValidationKey'];
        }
        if (empty($input['salt'])) {
            $input['salt'] = static::generateRandomString();
        }
        if (empty($input['cookieValidationString'])) {
            $input['cookieValidationString'] = static::generateRandomString();
        }

        if (!$this->initEnv($input) || !file_exists($this->setup->environmentFilePath)) {
            $this->errors[] = 'Unable to set up environment (Env file: '. $this->setup->environmentFilePath .')';
            return false;
        }
        // if ($upgrade) {
        //     return true;
        // }

        return true;
    }

    public function initEnv($env)
    {
        $configDirectory = CANIS_APP_CONFIG_PATH;
        $templateDirectory = $env['templateDirectory'];
        $renderer = new TwigRender();
        $parser = function($file) use ($env, $renderer) {
            $content = file_get_contents($file);
            return $renderer->renderContent($content, $env);
        };

        $findOptions = [];
        $findOptions['only'] = ['*.sample'];
        $files = FileHelper::findFiles($templateDirectory, $findOptions);
        foreach ($files as $file) {
            $newFilePath = strtr($file, [$templateDirectory => $configDirectory, '.sample' => '']);
            if ($newFilePath === $file) { continue; }
            $newFileDir = dirname($newFilePath);
            if (!is_dir($newFileDir)) {
                mkdir($newFileDir, 0755, true);
            }
            $newContent = $parser($file);
            file_put_contents($newFilePath, $newContent);
            if (!is_file($newFilePath)) {
                return false;
            }
        }
        return true;
    }

    /**
     *
     */
    public static function generateId($name)
    {
        return strtolower(Inflector::slug($name));
    }

    /**
     * @inheritdoc
     */
    public function loadInput($input)
    {
        if (!parent::loadInput($input)) {
            return false;
        }
        $fieldId = 'field_' . $this->id . '_ldap_host';
        try {
            $oe = ini_set('display_errors', 0);
            $dbh = ldap_connect($this->input['ldap']['host'], $this->input['ldap']['port']);
            if (!$dbh) {
                $this->fieldErrors[$fieldId] = 'Error connecting to ldap server: ' . $e->getMessage();
                return false;
            }
            ini_set('display_errors', $oe);
        } catch (\Exception $e) {
            $this->fieldErrors[$fieldId] = 'Error connecting to ldap server: ' . $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * Get env options.
     */
    public function getEnvOptions()
    {
        $envs = [];
        $templatePath = $this->setup->environmentTemplatesPath;
        $o = opendir($templatePath);
        while (($file = readdir($o)) !== false) {
            $path = $templatePath . DIRECTORY_SEPARATOR . $file;
            if (substr($file, 0, 1) === '.' or !is_dir($path)) {
                continue;
            }
            $envs[$file] = $path;
        }
        //var_dump($envs);
        return $envs;
    }

    /**
     * Get env list options.
     */
    public function getEnvListOptions()
    {
        $options = $this->envOptions;
        $list = [];
        foreach ($options as $k => $v) {
            $list[$k] = ucwords($k);
        }

        return $list;
    }

    /**
     * @inheritdoc
     */
    public function getFields()
    {
        if ($this->setup->isEnvironmented && $this->setup->app()) {
            return false;
        }

        $fields = [];
        $fields['app'] = ['label' => 'General', 'fields' => []];
        $fields['app']['fields']['template'] = ['type' => 'select', 'options' => $this->envListOptions, 'label' => 'Environment', 'required' => true, 'value' => function () { return defined('CANIS_APP_ENVIRONMENT') ? CANIS_APP_ENVIRONMENT : 'development'; }];
        $fields['app']['fields']['name'] = ['type' => 'text', 'label' => 'Application Name', 'required' => true, 'value' => function () { return $this->setup->name; }];

        $fields['ldap'] = ['label' => 'LDAP Server', 'fields' => []];
        $fields['ldap']['fields']['host'] = ['type' => 'text', 'label' => 'Host', 'required' => true, 'value' => function () { return defined('CANIS_APP_LDAP_HOST') && CANIS_APP_LDAP_HOST ? CANIS_APP_LDAP_HOST : 'ldap'; }];
        $fields['ldap']['fields']['port'] = ['type' => 'text', 'label' => 'Port', 'required' => true, 'value' => function () { return defined('CANIS_APP_LDAP_PORT') && CANIS_APP_LDAP_PORT ? CANIS_APP_LDAP_PORT : '389'; }];

        return $fields;
    }
}