<?php namespace App\Models;

use CodeIgniter\Model;


class PaymentsModel extends Model {
    protected $table            = 'payments';
    protected $primarykey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['invoice_id', 'amount', 'date'];

}