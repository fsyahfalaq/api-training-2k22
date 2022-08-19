<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Api {

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
        $authHeader = explode(" ", $reqToken);
        $token = $authHeader[1];
        // echo json_encode(JWT::decode($token, new Key($this->configToken()['secretkey'], 'HS256')));die;
        if ($token) {
            try {
                $decoded = JWT::decode($token, new Key($this->configToken()['secretkey'], 'HS256'));
                if ($decoded) {
                    return true;
                }
            } catch (\Exception $e) {
                $result = array('pesan' => 'Kode Signature Tidak Sesuai');
                return false;
                die;
            }
            // die;
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
        $output = [
            'status' => 200,
            'message' => 'Berhasil login',
            "token" => $jwt,
            "expireAt" => date("Y-m-d H:i:s", $token['exp']),
            "data" => $data
        ];

        return $output;
    }
}