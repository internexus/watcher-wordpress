<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit909e533dd0947d920a42d8e6d5659d6e
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Internexus\\Watcher\\' => 19,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Internexus\\Watcher\\' => 
        array (
            0 => __DIR__ . '/..' . '/internexus/watcher-php/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit909e533dd0947d920a42d8e6d5659d6e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit909e533dd0947d920a42d8e6d5659d6e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit909e533dd0947d920a42d8e6d5659d6e::$classMap;

        }, null, ClassLoader::class);
    }
}