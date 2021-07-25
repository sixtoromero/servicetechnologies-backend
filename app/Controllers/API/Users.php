<?php namespace App\Controllers\API;

// Header('Access-Control-Allow-Origin: *'); //for allow any domain, insecure
// Header('Access-Control-Allow-Headers: *'); //for allow any headers, insecure
// Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE'); //method allowed

use CodeIgniter\RESTful\ResourceController;
use App\Models\UsersModel;

class Users extends ResourceController
{
    public function __construct() {

		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");    

        $this->model = $this->setModel(new UsersModel());
		helper('secure_password');		

    }

	public function index()
	{
		//header("Access-Control-Allow-Origin: *");

		$users = $this->model->findAll();
		$json = array(
			"status" => 200,
			"message" => '',
			"data"=>$users
		);

		return $this->respond($json);
	}

	public function create(){

		//header("Access-Control-Allow-Origin: *");

		$validation =  \Config\Services::validation();

		try {

			//Validar datos
			$validation->setRules([
				'names' => 'required|string|max_length[255]',
				'surnames' => 'required|string|max_length[255]',
				'usertype' => 'required|string|max_length[255]',
				'email' => 'required|valid_email|is_unique[users.email]',
				'usertype' => 'required|string|max_length[120]',
				'address' => 'required|string|max_length[255]',
				'cellphone' => 'required|string|max_length[20]',
				'password' => 'required|string|max_length[255]'
			]);
	
			$validation->withRequest($this->request)
			   ->run();

			if ($validation->getErrors()){
				
				$errors = $validation->getErrors();

				$json = array(
					"status" => 404,
					"message" => 'Las validaciones no pasaron',
					"data"=>$errors
				);
					
				return $this->respond($json);
			}

			$users = $this->request->getJSON();						
			//Encriptamos la clave
			$users->password = hashPassword($users->password);			

			if($this->model->insert($users)):

				$users->id = $this->model->insertID();
				
				$json = array(
					"status" => 201,
					"message" => 'successfull',
					"data"=>$users
				);

				return $this->respondCreated($json);
			else:
				$json = array(
					"status" => 404,
					"message" => 'The record could not be inserted please try again',
					"data"=> null
				);
					
				return $this->respond($json);
				//return $this->failValidationError($this->model->validation->listErros());
			endif;
		}
		catch (\Exception $e) {
			//return $this->failServerError('An error has occurred on the server.');
			$json = array(
				"status" => 404,
				"message" => 'An error has occurred on the server.',
				"data"=> null
			);
				
			return $this->respond($json);
		}
	}


	public function FindById($id = null){
		//header("Access-Control-Allow-Origin: *");
		try {

			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$users = $this->model->find($id);

			if ($users == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);					
				return $this->respond($json);
			}

			$json = array(
				"status" => 200,
				"message" => 'Found record',
				"data"=>$users
			);
	
			return $this->respond($json);

		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => $e,
				"data"=>$users
			);
	
			return $this->respond($json);
		}
	}

	public function edit($id = null) {
		//header("Access-Control-Allow-Origin: *");
		try {
			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$users = $this->model->find($id);

			if ($users == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}

			$json = array(
				"status" => 200,
				"message" => '',
				"data"=>$users
			);
	
			return $this->respond($json);



		} catch (\Throwable $th) {
			return $this->failServerError('An error has occurred on the server.');
		}
	}

	public function update($id = null){
		//header("Access-Control-Allow-Origin: *");
		try {

			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);

				return $this->respond($json);
			}
			
			$usersVerified = $this->model->find($id);

			if ($usersVerified == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}

			$users = $this->request->getJSON();			

			if($this->model->update($id, $users)):				
				$users->id = $id;
				$json = array(
					"status" => 200,
					"message" => 'successfull',
					"data"=>$users
				);
				return $this->respondUpdated($json);
			else:
				$json = array(
					"status" => 404,
					"message" => 'The record could not be updated please try again',
					"data"=> null
				);
					
				return $this->respond($json);
				//return $this->failValidationError($this->model->validation->listErros());
			endif;
		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => $e,
				"data"=>$users
			);
	
			return $this->respond($json);
		}
	}

	public function delete($id = null){
		//header("Access-Control-Allow-Origin: *");
		try {

			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$usersVerified = $this->model->find($id);

			if ($usersVerified == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}						
			
			if	($this->model->delete($id)):
				$json = array(
					"status" => 200,
					"message" => 'Registry removed successfully.',
					"data"=>$users
				);
				return $this->respondDeleted($json);
			else:
				$json = array(
					"status" => 404,
					"message" => 'The record could not be deleted.',
					"data"=> null
				);
				return $this->respond($json);
			endif;

		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => $e,
				"data"=>$users
			);
	
			return $this->respond($json);
		}
	}


}
