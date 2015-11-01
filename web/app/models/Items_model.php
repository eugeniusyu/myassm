<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Items_model extends CI_Model {

    public function __construct() {
        parent::__construct();
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

    public function getCategoryByCode($code) {
        $q = $this->db->get_where('categories', array('code' => $code), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    function addItem($data) {
        if($this->db->insert('items', $data)) {
            return TRUE;
        }
        return FALSE;
    }

    public function add_items($data = array()) {
        if ($this->db->insert_batch('items', $data)) {
            return true;
        }
        return false;
    }

    function updateItem($id, $data) {
        if($this->db->update('items', $data, array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function deleteItem($id = NULL) {
        if($this->db->delete('items', array('id' => $id))) {
            return TRUE;
        }
        return FALSE;
    }

    public function items_count($category_id = NULL) {
        if ($category_id) {
            $this->db->where('category_id', $category_id);
            return $this->db->count_all_results("items");
        } else {
            return $this->db->count_all("items");
        }
    }

    public function fetch_items($limit, $start, $category_id = NULL) {
        $this->db->select('name, code, barcode_symbology')
        ->limit($limit, $start)->order_by("code", "asc");
        if ($category_id) {
            $this->db->where('category_id', $category_id);
        }
        $q = $this->db->get("items");

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function getAllCategories() {
        $q = $this->db->get('categories');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;;
    }

}
