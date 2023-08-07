<?php

namespace Src\Controllers;

use Laminas\Diactoros\Response\JsonResponse;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Src\Components\DataBase;

class UsersController
{

    public function show(int $id)
    {
        $db = (new DataBase())->link;
        $query = $db->prepare('select * from users where id = :id limit 1');
        $query->execute(['id' => $id]);

        $user = $query->fetch();
        if ($user) {
            return new JsonResponse([
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'second_name' => $user['second_name'],
                'birthdate' => date('Y-m-d', strtotime($user['birthdate'])),
                'sex' => $user['sex'],
                'biography' => $user['biography'],
                'city' => $user['city'],
            ], 200);
        } else {
            return new JsonResponse([
                'error' => 'User is not exists'
            ], 400);
        }
    }
}