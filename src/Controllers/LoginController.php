<?php
namespace Src\Controllers;

use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Validation\Constraint;
use Lcobucci\JWT\Validation\Constraint\ValidAt;

use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Validation\Constraint\RelatedTo;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;


class LoginController {

    public function login(): JsonResponse
    {
        $key = InMemory::base64Encoded($_ENV['JWT_SECRET']);

        $tokenBuilder = (new Builder(new JoseEncoder(), ChainedFormatter::default()));
        $algorithm    = new Sha256();
        $now   = new DateTimeImmutable();

        $token = $tokenBuilder
            ->issuedBy($_ENV['APP_URL'])
            ->issuedAt($now)
            ->expiresAt($now->modify('+1 second'))

            ->withClaim('uid', 1)
            ->getToken($algorithm, $key);
        return new JsonResponse([
            'token' => $token->toString()
        ]);
    }

    public function logout() {
//        unset($_SESSION['logged_user']);
//        header('Location: /tasks');
    }
}