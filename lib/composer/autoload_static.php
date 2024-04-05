<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9f2c1550fee16e6a91a75432d3e5bdae
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DFR\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DFR\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'DFR\\CSS_Vars' => __DIR__ . '/../..' . '/src/CSS_Vars.php',
        'DFR\\Init' => __DIR__ . '/../..' . '/src/Init.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9f2c1550fee16e6a91a75432d3e5bdae::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9f2c1550fee16e6a91a75432d3e5bdae::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9f2c1550fee16e6a91a75432d3e5bdae::$classMap;

        }, null, ClassLoader::class);
    }
}
