<?php namespace App\Controllers\API;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

use App\Models\PaymentsModel;
use App\Models\InvoicesModel;
use CodeIgniter\RESTful\ResourceController;

class Payments extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new PaymentsModel());
    }

	public function index()
	{
		$invoices = $this->model->findAll();
		$json = array(
			"status" => 200,
			"message" => '',
			"data"=>$invoices
		);

		return $this->respond($json);
	}

	public function create(){
		try {
						
			$payments = $this->request->getJSON();									
			
			$invoicesModel = new InvoicesModel();
			$invoiceVerified = $invoicesModel->where('id', $payments->invoice_id)->first();
			$payments->date = date("Y-m-d");
			

			if ($invoiceVerified != null){
				
				$amountpaid = (int)$invoiceVerified["amountpaid"];
				$amountpaid = $amountpaid + (int)$payments->amount;
				$invoiceVerified["amountpaid"] = $amountpaid;

				if ($this->model->insert($payments)):
					$payments->id = $this->model->insertID();															

					$json = array(
						"status" => 201,
						"message" => 'successfull',
						"data"=>$payments
					);
					return $this->respondCreated($json);
					//Actualizar el modelo de Invoices															
				else:				
					$json = array(
						"status" => 500,
						"message" => 'An error occurred while inserting the record',
						"data"=>null
					);
					return $this->failValidationError($json);
	
					//return $this->failValidationError($this->model->validation->listErrors());
				endif;
			} else {
				$json = array(
					"status" => 400,
					"message" => 'The invoice does not exist',
					"data"=>null
				);
		
				return $this->respond($json);
			}

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

			//$ordersModel = new OrdersModel();
			//$orders = $ordersModel->where('user_id', (int)$user_id)->orderBy('id', 'asc')->findAll();
			$payments = $this->model->where('invoice_id', (int)$id)->orderBy('id', 'asc')->findAll();
			//$invoices = $this->model->find($id);

			if ($payments == null) {
				$json = array(
					"status" => 200,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);					
				return $this->respond($json);
			}

			$json = array(
				"status" => 200,
				"message" => 'Found record',
				"data"=>$payments
			);
	
			return $this->respond($json);

		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => $e,
				"data"=>$invoices
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

			$invoices = $this->model->find($id);

			if ($invoices == null) {
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
				"data"=>$invoices
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

			$paymentVerified = $this->model->find($id);

			if ($paymentVerified == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}

			$payments = $this->request->getJSON();
			
			$paymentVerified["amount"] = (int)$payments->amount;
			$paymentVerified["date"] = date("Y-m-d");
						
			if($this->model->update($id, $paymentVerified)){								
				
				$json = array(
					"status" => 200,
					"message" => 'successfull',
					"data"=>$paymentVerified
				);

				return $this->respondUpdated($json);

			}
			else{
				$json = array(
					"status" => 404,
					"message" => 'The record could not be updated please try again',
					"data"=> null
				);
					
				return $this->respond($json);				
			}
		}
		catch (\Exception $e) {
			$json = array(
				"status" => 404,
				"message" => 'Ha ocurrido un error',
				"data"=>null
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

			$invoicesVerified = $this->model->find($id);

			if ($invoicesVerified == null) {
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
					"data"=>$invoices
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
				"data"=>$invoices
			);
	
			return $this->respond($json);
		}
	}


}
