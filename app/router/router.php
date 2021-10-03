<?php
require "routes.php";
function routes(array $routes = ROUTES): array
{
    return $routes;
}

function matchUriInRoutesArray($uri, $routes): array
{
    if (array_key_exists($uri, $routes)){
        return ['uri'=>$uri];
    }
    return [];
}

function router(){
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $routes = routes();

    print_r(matchUriInRoutesArray($uri, $routes));
}