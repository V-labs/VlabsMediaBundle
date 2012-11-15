<?php

if (file_exists($file = __DIR__.'/autoload.php')) {
    require_once $file;
} elseif (file_exists($file = __DIR__.'/autoload.php.dist')) {
    require_once $file;
}

if (class_exists($annotationRegistry = 'Doctrine\Common\Annotations\AnnotationRegistry')) {
    $annotationRegistry::registerLoader(function($class) {
        if (0 === strpos(ltrim($class, '/'), 'Vlabs\MediaBundle')) {
            if (file_exists($file = __DIR__.'/../'.substr(str_replace('\\', '/', $class), strlen('Vlabs\MediaBundle')).'.php')) {
                require_once $file;
            }
        }

        return class_exists($class, false);
    });
}
