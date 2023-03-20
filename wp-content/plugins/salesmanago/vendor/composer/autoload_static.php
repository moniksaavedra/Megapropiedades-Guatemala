<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitca0769e468ade83e75e2963dcf58b610
{
    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'tests\\' => 6,
        ),
        'b' => 
        array (
            'bhr\\' => 4,
        ),
        'S' => 
        array (
            'SALESmanago\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'tests\\' => 
        array (
            0 => __DIR__ . '/../..' . '/tests',
        ),
        'bhr\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'SALESmanago\\' => 
        array (
            0 => __DIR__ . '/..' . '/salesmanago/api-sso-util/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitca0769e468ade83e75e2963dcf58b610::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitca0769e468ade83e75e2963dcf58b610::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitca0769e468ade83e75e2963dcf58b610::$classMap;

        }, null, ClassLoader::class);
    }
}