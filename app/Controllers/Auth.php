<?php namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\UsersModel;
use Config\Services;
use Firebase\JWT\JWT;

class Auth extends BaseController
{
    use ResponseTrait;

    public function __construct() {
        helper('secure_password');
    }

	public function login()
	{
        // $email = $this->request->getPost('email');
        // $password = $this->request->getPost('password');

        // echo $email;

        //echo 'Email: '.$email." Password: ".$password;
        
        
		try {            

            //Si funciona
            // $users = $this->request->getJSON();
            // return $this->respond($users);

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
                        
            $usersModel = new UsersModel();
            //$where = ['email' => $email, 'password' => $password];            
            //$validateUser = $usersModel->where($where)->find();
            $validateUser = $usersModel->where('email', $email)->first();

            if ($validateUser == null){
                 return $this->failNotfound('Incorrect username or password');
            }

            if (verifyPassword($password, $validateUser["password"])):
                $jwt = $this->generateJWT($validateUser);
                return $this->respond($jwt);
                //return $this->respond($validateUser);
            else:
                return $this->failValidationError('Incorrect password');
            endif;


            // if ($validateUser == null){
            //      return $this->failNotfound('Incorrect username or password');
            // }            


        } catch (\Exception $e) {
            //return $this->failServerError('Ha ocurrido un error en el servidor');
        }
	}

    protected function generateJWT($user){
                        
        $key = Services::getSecretKey();
        $time = time();        
        //'aud' => 'http://localhost:80/',
        $payload = [
            'aud' => base_url(),
            'iat' => $time, //como entero el tiempo, 
            'exp' => $time + 60, //como entero cuando expira el token
        ];

        $jwt = JWT::encode($payload, $key);
        return $jwt;
    }

}
