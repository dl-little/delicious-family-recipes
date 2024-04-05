<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit9f2c1550fee16e6a91a75432d3e5bdae
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

        spl_autoload_register(array('ComposerAutoloaderInit9f2c1550fee16e6a91a75432d3e5bdae', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit9f2c1550fee16e6a91a75432d3e5bdae', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit9f2c1550fee16e6a91a75432d3e5bdae::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
