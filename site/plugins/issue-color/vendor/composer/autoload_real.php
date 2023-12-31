<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInita75007a765c0339a385e1d01cfa8cdba
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInita75007a765c0339a385e1d01cfa8cdba', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInita75007a765c0339a385e1d01cfa8cdba', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInita75007a765c0339a385e1d01cfa8cdba::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
