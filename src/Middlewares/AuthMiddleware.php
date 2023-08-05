<?php

namespace Src\Middlewares;

use Closure;
use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Exception;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\StrictValidAt;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware
{
    public function handle(ServerRequestInterface $request, Closure $next)
    {
        if ($request->getHeader('Authorization')) {
            $key = InMemory::base64Encoded($_ENV['JWT_SECRET']);

            $token = str_replace('Bearer ', '', $request->getHeader('Authorization')[0] ?? '');

            $config = Configuration::forSymmetricSigner(
                new Sha256(),
                $key
            );

            try {
                $token = $config->parser()->parse($token);

            } catch (Exception $e) {
                return new JsonResponse(['error' => $e->getMessage()], 401);
            }

            if (
                $config->validator()->validate(
                    $token,
                    new SignedWith(
                        new Sha256(),
                        $key                ),
                    new LooseValidAt(new FrozenClock(new DateTimeImmutable()))
                )
            ) {
                return $next($request);
            }
        }

        return new JsonResponse(['error' => 'Unauthorized!'], 401);


    }

}