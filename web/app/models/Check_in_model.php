<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_in_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getStockInByID($id) {
        $q = $this->db->get_where('check_in', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllInItems($check_in_id)
    {
        $this->db->select('check_in_items.*, items.name as item_name, items.code as item_code')
        ->join('items', 'items.id=check_in_items.item_id')
        ->order_by('check_in_items.id');
        $q = $this->db->get_where('check_in_items', array('check_in_id' => $check_in_id));
        if($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    function addIn($data, $items) {
        if($this->db->insert('check_in', $data)) {
            $check_in_id = $this->db->insert_id();
            foreach ($items as $item) {
                $item['check_in_id'] = $check_in_id;
                if($this->db->insert('check_in_items', $item)) {
                    $product = $this->getItemByID($item['item_id']);
                    $this->db->update('items', array('quantity' => ($product->quantity+$item['quantity'])), array('id' => $product->id));
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    function updateIn($id, $data, $items) {
        $oitems = $this->getAllInItems($id);
        if($this->db->update('check_in', $data, array('id' => $id)) && $this->db->delete('check_in_items', array('check_in_id' => $id))) {
            foreach ($items as $item) {
                $item['check_in_id'] = $id;
                if($this->db->insert('check_in_items', $item)) {
                    $product = $this->getItemByID($item['item_id']);
                    $this->db->update('items', array('quantity' => ($product->quantity+$item['quantity'])), array('id' => $product->id));
                }
            }
            foreach ($oitems as $oitem) {
                $product = $this->getItemByID($oitem->item_id);
                $this->db->update('items', array('quantity' => ($product->quantity-$oitem->quantity)), array('id' => $product->id));
            }
            return TRUE;
        }
        return FALSE;
    }

    public function deleteIn($id = NULL) {
        if($this->db->delete('check_in', array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function getItemByID($id) {
        $q = $this->db->get_where('items', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getItemByCode($code) {
        $q = $this->db->get_where('items', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getProductNames($term, $limit = 10) {
        $this->db->where("(name LIKE '%" . $term . "%' OR code LIKE '%" . $term . "%' OR  concat(name, ' (', code, ')') LIKE '%" . $term . "%')");
        $this->db->limit($limit);
        $q = $this->db->get('items');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSuppliers($term, $limit = 10) {
        $this->db->select('supplier')
        ->distinct()
        ->like('supplier', $term, 'both')
        ->limit($limit);
        $q = $this->db->get('check_in');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getAllSuppliers() {
        $q = $this->db->get('suppliers');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function getSupplierByID($id)
    {
        $q = $this->db->get_where('suppliers', array('id' => $id), 1);
        if( $q->num_rows() > 0 ) {
            return $q->row();
        }
        return FALSE;
    }

    public function getStockInByRef($ref) {
        $q = $this->db->get_where('check_in', array('reference' => $ref), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function generateReference()
    {
        $this->load->helper('string');
        $ref = random_string('numeric', 12);
        if($this->getStockInByRef($ref)) {
            $this->generateReference();
        } else {
            return $ref;
        }
    }

    public function getUser($id = NULL) {
        if(!$id) { $id = $this->session->userdata('user_id'); }
        $q = $this->db->get_where('users', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

}
