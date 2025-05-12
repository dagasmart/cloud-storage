<?php

namespace DagaSmart\CloudStorage\Factory\CloudStorage;


class CloudStorageFactory implements BaseFactory
{
    public static function make(object $config): object
    {
        $className = __NAMESPACE__.'\\'.ucfirst(strtolower(($config->driver))).'\\'.'Client';
        if (! class_exists($className)) {
            admin_abort('类不存在');
        }

        return new $className($config->config);
    }
}
