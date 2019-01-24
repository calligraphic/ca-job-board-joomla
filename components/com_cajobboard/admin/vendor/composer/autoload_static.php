<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcb44c6864943a25e005acca01ebc1066
{
    public static $prefixLengthsPsr4 = array (
        'I' => 
        array (
            'Identicon\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Identicon\\' => 
        array (
            0 => __DIR__ . '/..' . '/yzalis/identicon/src/Identicon',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcb44c6864943a25e005acca01ebc1066::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcb44c6864943a25e005acca01ebc1066::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}