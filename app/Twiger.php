<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twiger
{
    public static $loader;
    public static $twig;
    public static function init()
    {
        // Specify the directory for Twig cache files
        $cacheDir = realpath(__DIR__ . '/../tmp/twig-cache');

        // Specify the directory where your Twig templates are located
        $loader = new FilesystemLoader(VIEWS_PATH);
        // Create a Twig environment
        $twig = new Environment(
            $loader,
            [
                'debug' => true,
                // 'cache' => $cacheDir
                'cache' => false
            ]
        );
        // self::$twig->addExtension(new \Twig\Extension\DebugExtension());

        $function = new \Twig\TwigFunction('dmp', function ($var, $fn = 's') {
            if (self::$twig->isDebug() && function_exists($fn)) {
                $fn($var);
            } else {
                var_dump($var);
            }
        });
        $twig->addFunction($function);

        self::$twig = $twig;
        self::$loader = $loader;
    }

    public static function render($template, $variables = [])
    {
        $variables['BASE_PATH'] = BASE_PATH;
        return call_user_func_array([self::$twig, 'render'], [$template.".html.twig", $variables]);
    }
}
