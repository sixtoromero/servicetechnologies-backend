<?php namespace App\Models;

use CodeIgniter\Model;


class InvoicesModel extends Model {
    protected $table            = 'invoices';
    protected $primarykey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['order_id', 'amounttopay', 'amountpaid'];

}