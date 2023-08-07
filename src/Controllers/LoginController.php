<?php
namespace Src\Controllers;

use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;

use Src\Components\DataBase;


class LoginController {

    public function login(): JsonResponse
    {
        $requestObject = json_decode(file_get_contents('php://input'));
        if(!is_null($requestObject)) {
            if(!isset($requestObject->id)) {
                return new JsonResponse([
                    'error' => 'Id must be required'
                ], 400);
            } else if (!is_int($requestObject->id)) {
                return new JsonResponse([
                    'error' => 'Id must be int'
                ], 400);
            }
            if(!isset($requestObject->password)) {
                return new JsonResponse([
                    'error' => 'Password must be required'
                ], 400);
            } else if (!is_string($requestObject->password)) {
                return new JsonResponse([
                    'error' => 'Password must be string'
                ], 400);
            }

            $db = (new DataBase())->link;
            $query = $db->prepare('select * from users where id = :id limit 1');
            $query->execute(['id' => $requestObject->id]);

            $user = $query->fetch();
            if($user) {
                if (password_verify($requestObject->password, $user['password'])) {
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
                } else {
                    return new JsonResponse([
                        'error' => 'Password is incorrect'
                    ], 400);
                }
            } else {
                return new JsonResponse([
                    'error' => 'User is not exists'
                ], 400);
            }

        } else {
            return new JsonResponse([
                'error' => 'Request Body is empty'
            ], 400);
        }
    }

    public function register() {
        $requestObject = json_decode(file_get_contents('php://input'));
        if(!is_null($requestObject)) {
            if(!isset($requestObject->first_name)) {
                return new JsonResponse([
                    'error' => 'first_name must be required'
                ], 400);
            } else if (!is_string($requestObject->first_name)) {
                return new JsonResponse([
                    'error' => 'first_name must be string'
                ], 400);
            }


            if(!isset($requestObject->second_name)) {
                return new JsonResponse([
                    'error' => 'second_name must be required'
                ], 400);
            } else if (!is_string($requestObject->second_name)) {
                return new JsonResponse([
                    'error' => 'second_name must be string'
                ], 400);
            }

            if(!isset($requestObject->birthdate)) {
                return new JsonResponse([
                    'error' => 'birthdate must be required'
                ], 400);
            } else if (!is_string($requestObject->birthdate)) {
                return new JsonResponse([
                    'error' => 'birthdate must be string'
                ], 400);
            } else if (!strtotime($requestObject->birthdate)) {
                return new JsonResponse([
                    'error' => 'birthdate must be time string'
                ], 400);
            }

            if(!isset($requestObject->sex)) {
                return new JsonResponse([
                    'error' => 'sex must be required'
                ], 400);
            } else if (!is_string($requestObject->sex)) {
                return new JsonResponse([
                    'error' => 'sex must be string'
                ], 400);
            }

            if(!isset($requestObject->biography)) {
                return new JsonResponse([
                    'error' => 'biography must be required'
                ], 400);
            } else if (!is_string($requestObject->biography)) {
                return new JsonResponse([
                    'error' => 'biography must be string'
                ], 400);
            }

            if(!isset($requestObject->city)) {
                return new JsonResponse([
                    'error' => 'city must be required'
                ], 400);
            } else if (!is_string($requestObject->city)) {
                return new JsonResponse([
                    'error' => 'city must be string'
                ], 400);
            }

            if(!isset($requestObject->password)) {
                return new JsonResponse([
                    'error' => 'Password must be required'
                ], 400);
            } else if (!is_string($requestObject->password)) {
                return new JsonResponse([
                    'error' => 'Password must be string'
                ], 400);
            }

            $db = (new DataBase())->link;
            $query = $db->prepare('insert into users (first_name, second_name, birthdate, sex, biography, city, password) values (:first_name, :second_name, :birthdate, :sex, :biography, :city, :password)');
            $query->execute([
                'first_name' => $requestObject->first_name,
                'second_name' => $requestObject->second_name,
                'birthdate' => date('Y-m-d', strtotime($requestObject->birthdate)),
                'sex' => $requestObject->sex,
                'biography' => $requestObject->biography,
                'city' => $requestObject->city,
                'password' => password_hash($requestObject->password, PASSWORD_DEFAULT),
                ]);

            $user = $db->lastInsertId();
            if($user) {
                return new JsonResponse([
                    'id' => (int) $user
                ], 200);

            } else {
                return new JsonResponse([
                    'error' => 'User can not creating'
                ], 400);
            }

        } else {
            return new JsonResponse([
                'error' => 'Request Body is empty'
            ], 400);
        }
    }
}