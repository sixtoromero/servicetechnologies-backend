<?php namespace App\Models;

use CodeIgniter\Model;


class OrdersModel extends Model {
    protected $table            = 'orders';
    protected $primarykey       = 'id';

    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'status', 'date'];    

    public function FindByUserId($user_id = null){
      $builder = $this->db->table($this->table);
      $builder->select('orders.status, orders.id, users.names, users.surnames');
      $builder->join('users', 'orders.user_id = users.id');
      $builder->where('users.id', $user_id);

      $query = $builder->get();
      return $query->getResult();
	}

//   public function FindByInvoiceByOrderId($user_id = null){
//     $builder = $this->db->table($this->table);
//     $builder->select('orders.status, orders.id, users.names, users.surnames');
//     $builder->join('invoices', 'orders.user_id = users.id');
//     $builder->where('users.id', $user_id);

//     $query = $builder->get();
//     return $query->getResult();
// }



}