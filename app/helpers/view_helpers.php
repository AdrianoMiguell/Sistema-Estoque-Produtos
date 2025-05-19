<?php

$globalCssExtras = [];
$globalJsExtras = [];

function pushCss(String $path)
{
    global $globalCssExtras;
    $globalCssExtras[] = $path;
}

function getCssExtras(): array
{
    global $globalCssExtras;
    return $globalCssExtras ?? [];
}

function pushJs(String $path)
{
    global $globalJsExtras;
    $globalJsExtras[] = $path;
}

function getJsExtras(): array
{
    global $globalJsExtras;
    return $globalJsExtras ?? [];
}
