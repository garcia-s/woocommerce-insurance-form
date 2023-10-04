<?php

function load_classes($className)
{
    $file_dir = WC_INSURANCE_DIR . 'classes/' . strtolower($className) . '.php';
    if (file_exists($file_dir))
        require_once($file_dir);
}

spl_autoload_register('load_classes');
