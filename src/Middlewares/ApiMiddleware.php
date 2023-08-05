<?php

namespace Src\Middlewares;

use Closure;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;

class ApiMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        if ((isset($request->getHeader('Content-Type')[0]) && $request->getHeader('Content-Type')[0] === 'application/json')
            || (isset($request->getHeader('accept')[0]) && $request->getHeader('accept')[0] === 'application/json')
            || (isset($request->getHeader('Accept')[0]) && $request->getHeader('Accept')[0] === 'application/json')) {
            // Call the next middleware/controller
            return $next($request);
        }

        return new JsonResponse(['error' => 'Is not API!'], 401);
    }

}