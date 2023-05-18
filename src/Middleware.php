<?php declare(strict_types=1);

namespace AshleyHardy\Framework;

use Hardy\PeriodApi\Issues\ApiIssue;

class Middleware
{
    private static array $middlewareClasses = [];

    public static function register(...$classes): void
    {
        foreach($classes as $class) {
            if(get_parent_class($class) != AbstractMiddleware::class) throw ApiIssue::middlewareNotInstanceOfMiddleware();
            self::$middlewareClasses[] = $class;
        }
    }

    public static function checkMiddleware(Request $request): ?Response
    {
        foreach(self::$middlewareClasses as $middleware) {
            /** @var AbstractMiddleware */
            $middleware = new $middleware($request);
            if(!$middleware->run()) {
                //Middleware rejected this request. Abandon here and return a response.
                return $middleware->rejection();
            }
        }

        return null;
    }

    public static function reset(): void
    {
        self::$middlewareClasses = [];
    }

    public static function getRegistered(): array
    {
        return self::$middlewareClasses;
    }
}