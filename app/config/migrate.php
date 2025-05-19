<?php
require __DIR__ . '/database.php';

$files = glob(__DIR__ . '/../data/migrations/*.php');
$pdo = getPDO();

foreach ($files as $file) {
    $migration = require $file;
    echo "Running migration: $file\n";
    executeUp($migration, $pdo);
}

function executeUp($migration, $pdo)
{
    if (method_exists($migration, 'up')) {
        $migration->up($pdo);
    }
}
function executeDown($migration, $pdo)
{
    if (method_exists($migration, 'down')) {
        $migration->down($pdo);
    }
}
