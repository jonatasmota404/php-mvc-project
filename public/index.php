<?php
require "bootstrap.php";

try {
    router();
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}