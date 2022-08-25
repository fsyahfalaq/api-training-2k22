<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Api
{

    public function configToken()
    {
        $cnf['exp'] = time() + 3600; //milisecond
        $cnf['secretkey'] = '2212336221';
        return $cnf;
    }

    public function authtoken($reqToken)
    {
        $secret_key = $this->configToken()['secretkey'];
        $token = null;

        // split string beaerer from token
        $authHeader = explode(" ", $reqToken);
        $token = $authHeader[1];

        // verify token
        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));
                if ($decoded) {
                    return true;
                }
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    public function getToken($data)
    {
        $exp = $this->configToken()['exp'];

        $token = array(
            "exp" => $exp,
            "data" => $data
        );

        $jwt = JWT::encode($token, $this->configToken()['secretkey'], 'HS256');
        return [
            'status' => 200,
            'message' => 'Berhasil login',
            "token" => $jwt,
            "expireAt" => date("Y-m-d H:i:s", $token['exp']),
            "data" => $data
        ];
    }
}
