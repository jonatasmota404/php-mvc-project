<?php
require "routes.php";
function routes(array $routes = ROUTES): array
{
    return $routes;
}

function matchUriInRoutesArray($uri, $routes): array
{
    if (array_key_exists($uri, $routes)){
        return [$uri => $routes[$uri]];
    }
    return [];
}

function regexMatchArrayRoutes($uri, $routes): array
{
    return array_filter($routes, static function ($key) use ($uri){
        $regex = str_replace('/', '\/', ltrim($key,'/'));
        return preg_match("/^$regex$/", ltrim($uri,'/'));
    }, ARRAY_FILTER_USE_KEY);
}

function router(){
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $routes = routes();

    $matchedUri = matchUriInRoutesArray($uri, $routes);
    if (empty($matchedUri)){
        $matchedUri = regexMatchArrayRoutes($uri, $routes);
    }

    var_dump($matchedUri);
}