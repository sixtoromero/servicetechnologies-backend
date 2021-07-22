<?php

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;

namespace App\Filters;

class AuthFilter implements FilterInterface{

    use ResponseTrait;

    public function before(RequestInterface $request, $arguments = null) {
        //Se ejecuta antes que el controlador
        try {
            $key = Services::getSecretKey();
            $authHeader = $request->getServer('HTTP_AUTHORIZATION');
            if ($authHeader == null){
                return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED, 'You have not successfully authenticated.');
            }
            $arr = explode(' ', $authHeader);
            $jwt = $arr[1];
            JWT::decode($jwt, $key, ['HS256']);
            
            
        } catch (\Exception $e) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR, 'An error occurred while validating the session');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){
        //Se ejecuta después del controlador
    }


}