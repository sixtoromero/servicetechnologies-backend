<?php namespace App\Controllers\API;

use App\Models\InvoicesModel;
use App\Models\PaymentsModel;
use CodeIgniter\RESTful\ResourceController;

class Invoices extends ResourceController
{
    public function __construct() {
        $this->model = $this->setModel(new InvoicesModel());
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
						
			$invoices = $this->request->getJSON();
			$invoices->amounttopay = rand(10, 20);
			
			$invoicesModel = new InvoicesModel();
						
			$invoiceVerified = $invoicesModel->where('order_id', $invoices->order_id)->first();			

			if ($invoiceVerified == null){
				if ($this->model->insert($invoices)):
					$invoices->id = $this->model->insertID();
					
					$json = array(
						"status" => 201,
						"message" => 'successfull',
						"data"=>$invoices
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
			} else {
				$json = array(
					"status" => 400,
					"message" => 'The order already has an associated invoice.',
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

			//$invoices = $this->model->find($id);
			//$invoicesModel = new InvoicesModel();
			$invoices = $this->model->where('order_id', $id)->first();

			if ($invoices == null) {
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
				"data"=>$invoices
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

			$invoicesVerified = $this->model->find($id);

			if ($invoicesVerified == null) {
				$json = array(
					"status" => 404,
					"message" => 'Record with Id not found '. $id,
					"data"=> null
				);
				return $this->respond($json);
			}

			$invoices = $this->request->getJSON();			

			$invoicesVerified["amountpaid"] = (int)$invoices->amountpaid;			

			if($this->model->update($id, $invoicesVerified)):
				$invoices->id = $id;

				$json = array(
					"status" => 201,
					"message" => 'successfull',
					"data"=>$invoices
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
				"data"=>$invoices
			);
	
			return $this->respond($json);
		}
	}

	public function updatePayment($id = null){
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



			//$invoices = $this->request->getJSON();					

			//Consultar los pagos de la factura.
			$paymentsModel = new PaymentsModel();			
			$payments = $paymentsModel->where('invoice_id', $id)->orderBy('id', 'asc')->findAll();
			$mountSum = 0;
			foreach ($payments as $pay){
				$mountSum = $mountSum + $pay['amount'];
			}
			
			$invoicesVerified["amountpaid"] = $mountSum;			


			if($this->model->update($id, $invoicesVerified)):

				$json = array(
					"status" => 200,
					"message" => 'successfull',
					"data"=>$invoices
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
				"data"=>$invoices
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
