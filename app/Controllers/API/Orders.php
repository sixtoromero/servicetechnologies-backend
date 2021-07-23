<?php namespace App\Controllers\API;

session_start();

use App\Models\OrdersModel;
use App\Models\UsersModel;
use App\Models\InvoicesModel;
use App\Models\PaymentsModel;

use CodeIgniter\RESTful\ResourceController;

class Orders extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new OrdersModel());
    }

	public function index()
	{
		$orders = $this->model->findAll();
		$json = array(
			"status" => 200,
			"message" => '',
			"data"=>$orders
		);

		return $this->respond($json);
	}

	public function create(){
		try {
						
			$orders = $this->request->getJSON();
			
			$orders->user_id = $_SESSION['user'];
			$orders->date = date("Y-m-d");			

			if ($this->model->insert($orders)):
				$orders->id = $this->model->insertID();
				
				$json = array(
					"status" => 201,
					"message" => 'successfull',
					"data"=>$orders
				);
				return $this->respondCreated($json);
			else:				
				$json = array(
					"status" => 500,
					"message" => 'An error occurred while inserting the record',
					"data"=>null
				);
				return $this->failValidationError($json);

				//return $this->failValidationError($this->model->validation->listErrors());
			endif;

		} catch (\Exception $e) {			
			$json = array(
				"status" => 500,
				"message" => 'Ha ocurrido un error',
				"data"=>null
			);
			return $this->respond($json);
			//return $this->failServerError('Ha ocurrido un error');
		}
	}	


	public function FindById($id = null){
		try {

			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$orders = $this->model->find($id);

			if ($orders == null) {
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
				"data"=>$orders
			);
	
			return $this->respond($json);

		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => $e,
				"data"=>$orders
			);
	
			return $this->respond($json);
		}
	}	

	public function FindByUserId($user_id = null){
		try {
			
			$modelUser =  new UsersModel();
			
			if ($user_id == null)
			{
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);				
			}

			$user = $modelUser->find($user_id);						

			if ($user == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);					
				return $this->respond($json);
			}

			$orders = $this->model->FindByUserId($user_id);

			$json = array(
				"status" => 200,
				"message" => 'Found record',
				"data"=>$orders
			);
	
			return $this->respond($json);			

		} catch (\Exception $e) {
			return $this->failServerError('Ha ocurrido un error en el servidor');
		}
	}

	public function FindByUserId_($user_id = null){
		try {		

			if ($user_id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}
			
			$ordersModel = new OrdersModel();
			$orders = $ordersModel->where('user_id', (int)$user_id)->orderBy('id', 'asc')->findAll();			

			if ($orders == null) {
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
				"data"=>$orders
			);
	
			return $this->respond($json);

		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => $e,
				"data"=>$orders
			);
	
			return $this->respond($json);
		}
	}

	public function edit($id = null) {
		try {
			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$orders = $this->model->find($id);

			if ($orders == null) {
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
				"data"=>$orders
			);
	
			return $this->respond($json);



		} catch (\Throwable $th) {
			return $this->failServerError('An error has occurred on the server.');
		}
	}

	public function update($id = null){
		try {

			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$ordersVerified = $this->model->find($id);

			if ($ordersVerified == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}						

			$orders = $this->request->getJSON();			

			if($this->model->update($id, $orders)):				
				$orders->id = $id;
				$json = array(
					"status" => 200,
					"message" => 'successfull',
					"data"=>$orders
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
				"data"=>$orders
			);
	
			return $this->respond($json);
		}
	}

	public function delete($id = null){
		try {

			if ($id == null) {
				$json = array(
					"status" => 404,
					"message" => 'A valid Id has not been passed',
					"data"=> null
				);
					
				return $this->respond($json);
			}

			$ordersVerified = $this->model->find($id);			

			if ($ordersVerified == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}						
			
			//Validando que no tenga pagos las facturas.
			//Se consulta la factura que tiene asociada la Orden
			$invoicesModel = new InvoicesModel();						

			$invoiceVerified = $invoicesModel->where('id', $id)->first();						
			

			if ($invoiceVerified != null) {
				$paymentsModel = new PaymentsModel();
				$paymentsVerified = $paymentsModel->where('invoice_id', (int)$invoiceVerified["id"])->first();				

				if ($paymentsVerified != null){
					$json = array(
						"status" => 404,
						"message" => 'The Order cannot be deleted, because it already has payments made.',
						"data"=> null
					);
						
					return $this->respond($json);
				}
			}

			if	($this->model->delete($id)):
				$json = array(
					"status" => 200,
					"message" => 'Registry removed successfully.',
					"data"=>null
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
				"data"=>$orders
			);
	
			return $this->respond($json);
		}
	}


}
