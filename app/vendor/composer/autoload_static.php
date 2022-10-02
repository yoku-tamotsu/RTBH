<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit27551c70afa3084617f7d54c8032107c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Prewk\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Prewk\\' => 
        array (
            0 => __DIR__ . '/..' . '/prewk/xml-string-streamer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit27551c70afa3084617f7d54c8032107c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit27551c70afa3084617f7d54c8032107c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit27551c70afa3084617f7d54c8032107c::$classMap;

        }, null, ClassLoader::class);
    }
}
