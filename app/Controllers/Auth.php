<?php namespace App\Controllers;

Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed

session_start();

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
            $users = $this->request->getJSON();
            

            
            $email = $users->email; // $this->request->getPost('email');
            $password = $users->password;//$this->request->getPost('password');
                        
            $usersModel = new UsersModel();
            //$where = ['email' => $email, 'password' => $password];            
            //$validateUser = $usersModel->where($where)->find();
            $validateUser = $usersModel->where('email', $email)->first();

            if ($validateUser == null){
                 return $this->failNotfound('Incorrect username or password');
            }

            if (verifyPassword($password, $validateUser["password"])):
                
                $json = array(
                    "status" => 200,
                    "message" => '',
                    "data"=>$validateUser
                );
        
                $_SESSION['user'] = (int)$validateUser["id"];

                return $this->respond($json);
                // $jwt = $this->generateJWT($validateUser);
                // return $this->respond(['token' => $jwt], 201);                
            else:
                $json = array(
                    "status" => 401,
                    "message" => 'Incorrect username or password',
                    "data"=>null
                );
        
                return $this->respond($json);
                //return $this->failValidationError('Incorrect password');
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
            'data' => [
                'names' => $user["names"],
                'surnames' => $user["surnames"],
                'email' => $user["email"]
            ]
        ];

        $jwt = JWT::encode($payload, $key);
        return $jwt;
    }

}
