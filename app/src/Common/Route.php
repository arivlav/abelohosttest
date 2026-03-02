<?php

namespace App\Common;

use App\Enums\Response;

class Route {
    private static array $routes;

    public static function load() {
        return self::$routes = require __DIR__ . "/../Config/routes.php";
    }

    private static array $resolving = [];

    private static function respond(mixed $result): void
    {
        if (is_string($result)) {
            echo $result;
        }
    }

    private static function make(string $class): object
    {
        if (isset(self::$resolving[$class])) {
            throw new \RuntimeException("Circular dependency: $class");
        }
        self::$resolving[$class] = true;

        try {
            $ref = new \ReflectionClass($class);
            if (!$ref->isInstantiable()) {
                throw new \RuntimeException("Class is not instantiable: $class");
            }

            $ctor = $ref->getConstructor();
            if ($ctor === null || $ctor->getNumberOfParameters() === 0) {
                return $ref->newInstance();
            }

            $args = [];
            foreach ($ctor->getParameters() as $param) {
                $type = $param->getType();

                if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                    $args[] = self::make($type->getName());
                    continue;
                }

                if ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                    continue;
                }
                
                throw new \RuntimeException("Unable to resolve parameter \${$param->getName()} for $class");
            }

            return $ref->newInstanceArgs($args);
        } finally {
            unset(self::$resolving[$class]);
        }
    }

    public static function findRouteDataByUrl(string $url): array
    {
        foreach(self::$routes as $route => $routeData) {
            $pattern = '/^' . $route . '$/';
            if (preg_match($pattern, $url)) {
                return $routeData;
            }
        }
        return [];
    }

    // Обработка запроса
    public static function dispatch($url): void {
        $routeData = self::findRouteDataByUrl($url);
        if ($routeData) {
            [$controller, $method] = $routeData;
            $instance = self::make($controller);
            $arg = new Request()->getArgFromUrl();
            if ($arg !== null) {
                self::respond($instance->{$method}($arg));
            } else {
                self::respond($instance->{$method}());
            }
        } else {
            getResponseCodeAndMessage(Response::NOT_FOUND);
        }
    }
}