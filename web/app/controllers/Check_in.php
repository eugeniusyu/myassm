<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Check_in extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('logout');
        }

        $this->load->library('form_validation');
        $this->load->model('check_in_model');
        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
    }

    public function index() {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('check_ins');
        $this->page_construct('check_in/index', $this->data);
    }

    public function get_list() {
        $start_date = $this->input->get('start_date') ? $this->input->get('start_date') : NULL;
        $end_date = $this->input->get('end_date') ? $this->input->get('end_date') : NULL;

        $this->load->library('datatables');
        $this->datatables
        ->select($this->db->dbprefix('check_in').".id as id, date, reference, ".$this->db->dbprefix('suppliers').".name, concat(".$this->db->dbprefix('users').".first_name, ' ', ".$this->db->dbprefix('users').".last_name) as created_by, note, attachment", FALSE)
        ->from('check_in')
        ->join('users', 'users.id=check_in.created_by', 'left')
        ->join('suppliers', 'suppliers.id=check_in.supplier', 'left')
        ->group_by('check_in.id')
        ->add_column("Actions", "<div class='text-center'><div class='btn-group btn-group-justified' role='group'> <div class='btn-group' role='group'><a class=\"btn btn-warning btn-xs tip\" title='" . lang("edit_check_in") . "' href='" . site_url('check_in/edit/$1') . "'><i class=\"fa fa-edit\"></i></a></div> <div class='btn-group' role='group'><a href='#' class='btn btn-danger btn-xs tip po' title='<b>" . lang("delete_check_in") . "</b>' data-content=\"<p>" . lang('action_x_undone') . "</p><a class='btn btn-danger po-delete' href='" . site_url('check_in/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div></div>", "id");
        //$this->datatables->unset_column("id");
        if($start_date) { $this->datatables->where('date >=', $start_date.' 00:00:00'); }
        if($end_date) { $this->datatables->where('date <=', $end_date.' 23:59:59'); }
        echo $this->datatables->generate();
    }

    public function view($id) {

        $inv = $this->check_in_model->getStockInByID($id);
        $this->data['inv'] = $inv;
        $this->data['items'] = $this->check_in_model->getAllInItems($id);
        $this->data['created_by'] = $this->check_in_model->getUser($inv->created_by);
        $this->data['updated_by'] = $this->check_in_model->getUser($inv->updated_by);
        $this->data['supplier'] = $this->check_in_model->getSupplierByID($inv->supplier);
        $this->data['page_title'] = lang('check_in_id')." ".$id;
        $this->load->view($this->theme.'check_in/view', $this->data);
    }

    public function add() {

        $this->form_validation->set_rules('reference', lang("reference"), 'trim|required|is_unique[check_in.reference]');
        $this->form_validation->set_rules('date', lang("date"), 'trim|required');
        $this->form_validation->set_rules('supplier', lang("supplier"), 'trim');

        if ($this->form_validation->run() == true) {

            $quantity = "quantity";
            $product_id = "product_id";
            $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
            for ($r = 0; $r < $i; $r++) {
                $item_id = $_POST['product_id'][$r];
                $item_qty = $_POST['quantity'][$r];
                if( $item_id && $item_qty ) {

                    if(!$this->check_in_model->getItemByID($item_id)) {
                        $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                        redirect('check_in/add');
                    }

                    $items[] = array(
                        'item_id' => $item_id,
                        'quantity' => $item_qty,
                    );

                }
            }

            if (!isset($items) || empty($items)) {
                $this->form_validation->set_rules('product', lang("order_items"), 'required');
            } else {
                krsort($items);
            }

            $data = array( 'date' => $this->input->post('date'),
            'reference' => $this->input->post('reference'),
            'supplier' => $this->input->post('supplier'),
            'note' => $this->input->post('note'),
            'created_by' => $this->session->userdata('user_id'),
        );

        if ($_FILES['attachment']['size'] > 0) {

            $this->load->library('upload');
            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = $this->digital_file_types;
            $config['max_size'] = 2000;
            $config['encrypt_name'] = TRUE;
            $config['file_ext_tolower'] = TRUE;
            $config['overwrite'] = FALSE;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('attachment')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect("check_in/add");
            }

            $photo = $this->upload->file_name;
            $data['attachment'] = $photo;

        }

        // $this->tec->print_arrays($data, $items);

    }

    if ( $this->form_validation->run() == true && $this->check_in_model->addIn($data, $items)) {
        $this->session->set_flashdata('message', lang("check_in_added"));
        redirect('check_in');
    } else {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('add_check_in');
        $this->data['suppliers'] = $this->check_in_model->getAllSuppliers();
        $this->data['reference'] = $this->check_in_model->generateReference();
        $this->page_construct('check_in/add', $this->data);

    }
}

public function add_by_csv() {

    $this->form_validation->set_rules('reference', lang("reference"), 'trim|required|is_unique[check_in.reference]');
    $this->form_validation->set_rules('date', lang("date"), 'trim|required');
    $this->form_validation->set_rules('supplier', lang("supplier"), 'trim');

    if ($this->form_validation->run() == true) {

        if (isset($_FILES["userfile"])) {

            $this->load->library('upload');

            $config['upload_path'] = 'uploads/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = '500';
            $config['overwrite'] = TRUE;
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload()) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect("check_in/add_by_csv");
            }


            $csv = $this->upload->file_name;

            $arrResult = array();
            $handle = fopen("uploads/" . $csv, "r");
            if ($handle) {
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $arrResult[] = $row;
                }
                fclose($handle);
            }
            array_shift($arrResult);

            $keys = array('code', 'quantity');

            $final = array();
            foreach ($arrResult as $key => $value) {
                $final[] = array_combine($keys, $value);
            }

            if (sizeof($final) > 1001) {
                $this->session->set_flashdata('error', lang("more_than_allowed"));
                redirect("check_in/add_by_csv");
            }

            foreach ($final as $csv_pr) {
                if($item = $this->check_in_model->getItemByCode($csv_pr['code'])) {
                    $items[] = array('item_id' => $item->id, 'quantity' => $csv_pr['quantity']);
                } else {
                    $this->session->set_flashdata('error', lang("check_item_code") . " (" . $csv_pr['code'] . "). " . lang("item_x_exist"));
                    redirect("check_in/add_by_csv");
                }

            }
        } else {
            $this->form_validation->set_rules('userfile', lang("csv_file"), 'required');
        }

        $data = array( 'date' => $this->input->post('date'),
        'reference' => $this->input->post('reference'),
        'supplier' => $this->input->post('supplier'),
        'note' => $this->input->post('note'),
        'created_by' => $this->session->userdata('user_id'),
    );

    if ($_FILES['attachment']['size'] > 0) {

        $this->load->library('upload');
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = $this->digital_file_types;
        $config['max_size'] = 2000;
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = FALSE;
        $config['file_ext_tolower'] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('attachment')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect("check_in/add");
        }

        $photo = $this->upload->file_name;
        $data['attachment'] = $photo;

    }

    // $this->tec->print_arrays($data, $items);

}

if ( $this->form_validation->run() == true && $this->check_in_model->addIn($data, $items)) {
    $this->session->set_flashdata('message', lang("check_in_added"));
    redirect('check_in');
} else {
    $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
    $this->data['page_title'] = lang('add_check_in');
    $this->data['suppliers'] = $this->check_in_model->getAllSuppliers();
    $this->data['reference'] = $this->check_in_model->generateReference();
    $this->page_construct('check_in/add_by_csv', $this->data);

}
}

public function edit($id) {
    if (!$this->Admin) {
        $this->session->set_flashdata('warning', lang('access_denied'));
        redirect('check_in');
    }

    $this->form_validation->set_rules('reference', lang("reference"), 'trim|required');
    $this->form_validation->set_rules('date', lang("date"), 'trim|required');
    $this->form_validation->set_rules('supplier', lang("supplier"), 'trim');
    $check_in = $this->check_in_model->getStockInByID($id);
    if($check_in->reference != $this->input->post('reference')) {
        $this->form_validation->set_rules('reference', lang("reference"), 'is_unique[check_in.reference]');
    }

    if ($this->form_validation->run() == true) {

        $quantity = "quantity";
        $product_id = "product_id";
        $i = isset($_POST['product_id']) ? sizeof($_POST['product_id']) : 0;
        for ($r = 0; $r < $i; $r++) {
            $item_id = $_POST['product_id'][$r];
            $item_qty = $_POST['quantity'][$r];
            if( $item_id && $item_qty ) {

                if(!$this->check_in_model->getItemByID($item_id)) {
                    $this->session->set_flashdata('error', $this->lang->line("product_not_found")." ( ".$item_id." ).");
                    redirect('check_in/add');
                }

                $items[] = array(
                    'item_id' => $item_id,
                    'quantity' => $item_qty,
                );

            }
        }

        if (!isset($items) || empty($items)) {
            $this->form_validation->set_rules('product', lang("order_items"), 'required');
        } else {
            krsort($items);
        }

        $data = array( 'date' => $this->input->post('date'),
        'reference' => $this->input->post('reference'),
        'supplier' => $this->input->post('supplier'),
        'note' => $this->input->post('note'),
        'updated_by' => $this->session->userdata('user_id'),
        'updated_at' => date('Y-m-d H-i:s'),
    );

    if ($_FILES['attachment']['size'] > 0) {

        $this->load->library('upload');
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = $this->digital_file_types;
        $config['max_size'] = 2000;
        $config['encrypt_name'] = TRUE;
        $config['overwrite'] = FALSE;
        $config['file_ext_tolower'] = TRUE;
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('attachment')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
            redirect("check_in/eidt/".$id);
        }

        $photo = $this->upload->file_name;
        $data['attachment'] = $photo;

    }

    // $this->tec->print_arrays($data, $items);

}

if ( $this->form_validation->run() == true && $this->check_in_model->updateIn($id, $data, $items)) {
    $this->session->set_flashdata('message', lang("check_in_updated"));
    redirect('check_in');
} else {
    $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
    $this->data['check_in'] = $check_in;
    $items = array();
    if($check_in_items = $this->check_in_model->getAllInItems($id)) {
        foreach ($check_in_items as $item) {
            $row = $this->check_in_model->getItemByID($item->item_id);
            $row->qty = $item->quantity;
            $pr[$row->id] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
        }
        $items = json_encode($pr);
    }
    $this->data['items'] = $items;
    $this->data['suppliers'] = $this->check_in_model->getAllSuppliers();
    $this->data['page_title'] = lang('edit_check_in');
    $this->page_construct('check_in/edit', $this->data);

}
}

public function delete($id = NULL) {
    if (!$this->Admin) {
        $this->session->set_flashdata('warning', lang('access_denied'));
        redirect('check_in');
    }
    if ($this->check_in_model->deleteIn($id)) {
        $this->session->set_flashdata('message', lang("check_in_deleted"));
        redirect("check_in");
    } else {
        $this->session->set_flashdata('error', lang("delete_failed"));
        redirect("check_in");
    }
}

function suggestions()
{
    $term = $this->input->get('term', TRUE);

    $rows = $this->check_in_model->getProductNames($term);
    if ($rows) {
        foreach ($rows as $row) {
            $row->qty = 1;
            $pr[] = array('id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'row' => $row);
        }
        echo json_encode($pr);
    } else {
        echo json_encode(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));
    }
}

function suppliers()
{
    $term = $this->input->get('term', TRUE);

    $rows = $this->check_in_model->getSuppliers($term);
    if ($rows) {
        foreach ($rows as $row) {
            $sp[] = array('id' => $row->supplier, 'label' => $row->supplier, 'value' => $row->supplier);
        }
        echo json_encode($sp);
    } else {
        echo NULL;
    }
}

}
