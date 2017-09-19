<?php
require 'vendor/autoload.php';

try {
    \Commissions\Config::init('config/currency.json');
    $results = \Commissions\Calculator::solve(@file($argv[1]));
    foreach ($results as $result) {
        echo "$result\n";
    }
} catch(\Exception $e) {
    die("Error: {$e->getMessage()}\n");
}
