<?php

/**
 * Debug function
 * d($var);
 */
function d($var,$caller=null)
{
//    var_dump($caller); exit;

    if(!isset($caller)){
        $temp = debug_backtrace(1);
        $caller = array_shift($temp);
    }
    if (isset($caller['file'])) {
        echo '<code>File: ' . $caller['file'] . '</code>';
    }
    if (isset($caller['line'])) {
        echo '<code>Line: ' . $caller['line'] . '</code>';
    }
 
    echo '<pre>';
    yii\helpers\VarDumper::dump($var, 10, true);
    echo '</pre>';
}

/**
 * Debug function with die() after
 * dd($var);
 */
function dd($var)
{
    $temp = debug_backtrace(1);
    $caller = array_shift($temp);
    d($var,$caller);
    die();
}


/**
 * Debug function with die() after
 * dd($var);
 */
function dtd($var)
{
    $temp = debug_backtrace();

    foreach ($temp as $key => $item) {
        d($key, $item);
    }
    $temp = debug_backtrace(1);
    $caller = array_shift($temp);
    d($var,$caller);
    die();
}