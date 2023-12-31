<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitf95d10a5363717b6a2ca39ed4461f210
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

        spl_autoload_register(array('ComposerAutoloaderInitf95d10a5363717b6a2ca39ed4461f210', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitf95d10a5363717b6a2ca39ed4461f210', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitf95d10a5363717b6a2ca39ed4461f210::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
