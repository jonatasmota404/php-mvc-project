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

function params($uri ,$matchedUri): array
{
    if (!empty($matchedUri)) {
        $matchedParams = array_keys($matchedUri)[0];
        return array_diff(
            $uri,
            explode("/", ltrim($matchedParams, '/'))
        );
    }
    return [];
}

function formatParams($uri, $params): array
{
    $paramsData = [];

    foreach ($params as $index => $param) {
        $paramsData[$uri[$index - 1]] = $param;
    }

    return $paramsData;
}

/**
 * @throws Exception
 */
function router(){
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $routes = routes();
    $params = null;

    $matchedUri = matchUriInRoutesArray($uri, $routes);
    if (empty($matchedUri)){
        $matchedUri = regexMatchArrayRoutes($uri, $routes);
        $uri = explode("/", ltrim($uri, '/'));
        $params = params($uri, $matchedUri);
        $params = formatParams($uri, $params);
    }

    if (!empty($matchedUri)){
        loadController($matchedUri, $params);
        return;
    }

    throw new Exception('Uri não encontrada');
}