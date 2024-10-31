<?php

namespace QuizAd;

use Exception;

/**
 * Autoloader
 *
 * An autoloader using to automatically load classes
 *
 * @param $class_name
 */
class Autoloader
{
    const PLUGIN_NAMESPACE = "QuizAd";

    /**
     * @throws Exception
     */
    public static function register()
    {
        \spl_autoload_register(function ($className) {
            // Only if in our namespace.
            if (strpos($className, self::PLUGIN_NAMESPACE) !== 0)
            {
                return false;
            }

            $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
            $path     = str_replace(self::PLUGIN_NAMESPACE, __DIR__ . '/src', $filePath);

            if (\file_exists($path))
            {
                // ignore this include case - it has to be like that
                require_once $path;
                return true;
            }
            return false;

        });
    }

}


