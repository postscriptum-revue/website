<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita75007a765c0339a385e1d01cfa8cdba
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'ColorThief\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ColorThief\\' => 
        array (
            0 => __DIR__ . '/..' . '/ksubileau/color-thief-php/src/ColorThief',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita75007a765c0339a385e1d01cfa8cdba::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita75007a765c0339a385e1d01cfa8cdba::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita75007a765c0339a385e1d01cfa8cdba::$classMap;

        }, null, ClassLoader::class);
    }
}
