<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->loggedIn) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('logout');
        }

        $this->load->library('form_validation');
        $this->load->model('items_model');

    }

    public function index() {
        $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('items');
        $this->page_construct('items/index', $this->data);
    }

    public function get_items($alerts = NULL) {

        $this->load->library('datatables');
        $this->datatables
            ->select("items.id as id, image, ".$this->db->dbprefix('items').".code, ".$this->db->dbprefix('items').".name, ".$this->db->dbprefix('categories').".name as cname, quantity, unit, alert_quantity")
            ->from('items')
            ->join('categories', 'categories.id=items.category_id', 'left')
            ->group_by('items.id');
            if ($alerts) {
                $this->datatables->where('quantity < alert_quantity', NULL, FALSE);
            }
        $this->datatables->add_column("Actions", "<div class='text-center'><div class='btn-group btn-group-justified' role='group'><div class='btn-group' role='group'><a class=\"btn btn-warning btn-xs tip\" title='" . lang("edit_item") . "' href='" . site_url('items/edit/$1') . "'><i class=\"fa fa-edit\"></i></a></div> <div class='btn-group' role='group'><a href='#' class='btn btn-danger btn-xs tip po' title='<b>" . lang("delete_item") . "</b>' data-content=\"<p>" . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('items/delete/$1') . "'>" . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i></a></div></div>", "id");
        $this->datatables->unset_column("id");
        echo $this->datatables->generate();
    }

    function alerts() {
        $data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['page_title'] = lang('stock_alert');
        $bc = array(array('link' => '#', 'page' => lang('stock_alert')));
        $meta = array('page_title' => lang('stock_alert'), 'bc' => $bc);
        $this->page_construct('items/alerts', $this->data, $meta);
    }

    function get_alerts() {

        $this->load->library('datatables');
        $this->datatables->select($this->db->dbprefix('products').".id as pid, ".$this->db->dbprefix('products').".image as image, ".$this->db->dbprefix('products').".code as code, ".$this->db->dbprefix('products').".name as pname, type, ".$this->db->dbprefix('categories').".name as cname, quantity, alert_quantity, tax, tax_method, cost, price", FALSE)
        ->join('categories', 'categories.id=products.category_id')
        ->from('products')
        ->where('quantity < alert_quantity', NULL, FALSE)
        ->group_by('products.id');
        $this->datatables->add_column("Actions", "<div class='text-center'><a href='#' class='btn btn-xs btn-primary ap tip' data-id='$1' title='".lang('add_to_purcahse_order')."'><i class='fa fa-plus'></i></a></div>", "pid");
        $this->datatables->unset_column('pid');
        echo $this->datatables->generate();

    }

    public function add() {

        $this->form_validation->set_rules('code', lang("code"), 'trim|required|is_unique[items.code]');
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $this->form_validation->set_rules('quantity', lang("name"), 'trim|required|numeric');
        $this->form_validation->set_rules('unit', lang("unit"), 'trim|required');
        $this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'trim|required|numeric');

        if ($this->form_validation->run() == true) {

            $data = array( 'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'quantity' => $this->input->post('quantity'),
                'unit' => $this->input->post('unit'),
                'category_id' => $this->input->post('category'),
                'alert_quantity' => $this->input->post('alert_quantity'),
            );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '500';
                $config['max_width'] = '800';
                $config['max_height'] = '800';
                $config['overwrite'] = FALSE;
                $config['file_ext_tolower'] = TRUE;
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("items/add");
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;

            }

        }

        if ( $this->form_validation->run() == true && $this->items_model->addItem($data)) {
            $this->session->set_flashdata('message', lang("item_added"));
            redirect('items');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors(): $this->session->flashdata('error');
            $this->data['categories'] = $this->items_model->getAllCategories();
            $this->data['page_title'] = lang('add_item');
            $this->page_construct('items/add', $this->data);

        }
    }

    public function edit($id) {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('items');
        }

        $this->form_validation->set_rules('code', lang("code"), 'trim|required');
        $this->form_validation->set_rules('name', lang("name"), 'trim|required');
        $this->form_validation->set_rules('quantity', lang("name"), 'trim|required|numeric');
        $this->form_validation->set_rules('unit', lang("unit"), 'trim|required');
        $this->form_validation->set_rules('alert_quantity', lang("alert_quantity"), 'trim|required|numeric');
        $item = $this->items_model->getItemByID($id);
        if($item->code != $this->input->post('code')) {
            $this->form_validation->set_rules('code', lang("code"), 'is_unique[items.code]');
        }

        if ($this->form_validation->run() == true) {

            $data = array( 'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'quantity' => $this->input->post('quantity'),
                'unit' => $this->input->post('unit'),
                'category_id' => $this->input->post('category'),
                'alert_quantity' => $this->input->post('alert_quantity'),
            );

            if ($_FILES['userfile']['size'] > 0) {

                $this->load->library('upload');

                $config['upload_path'] = 'uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '500';
                $config['max_width'] = '800';
                $config['max_height'] = '800';
                $config['overwrite'] = FALSE;
                $config['file_ext_tolower'] = TRUE;
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload()) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect("items/add");
                }

                $photo = $this->upload->file_name;
                $data['image'] = $photo;

            }

        }

        if ( $this->form_validation->run() == true && $this->items_model->updateItem($id, $data)) {
            $this->session->set_flashdata('message', lang("item_updated"));
            redirect('items');
        } else {
            $this->data['error'] = validation_errors() ? validation_errors() : $this->session->flashdata('error');
            $this->data['item'] = $item;
            $this->data['categories'] = $this->items_model->getAllCategories();
            $this->data['page_title'] = lang('edit_item');
            $this->page_construct('items/edit', $this->data);

        }
    }

    function import() {
        if (!$this->Admin) {
            $this->session->set_flashdata('error', lang('access_denied'));
            redirect('items');
        }
        $this->load->helper('security');
        $this->form_validation->set_rules('userfile', lang("upload_file"), 'xss_clean');

        if ($this->form_validation->run() == true) {
            if (DEMO) {
                $this->session->set_flashdata('warning', lang("disabled_in_demo"));
                redirect('items');
            }

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
                    redirect("items/import");
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

                $keys = array('code', 'name', 'category', 'quantity', 'unit', 'alert_quantity', 'image');

                $final = array();
                foreach ($arrResult as $key => $value) {
                    $final[] = array_combine($keys, $value);
                }

                if (sizeof($final) > 1001) {
                    $this->session->set_flashdata('error', lang("more_than_allowed"));
                    redirect("items/import");
                }

                foreach ($final as $csv_pr) {
                  $category = FALSE;
                    if ($this->items_model->getItemByCode($csv_pr['code'])) {
                        $this->session->set_flashdata('error', lang("check_item_code") . " (" . $csv_pr['code'] . "). " . lang("code_already_exist"));
                        redirect("items/import");
                    }
                    if(! empty($csv_pr['category'])) {
                      if(! ($category = $this->items_model->getCategoryByCode($csv_pr['category']))) {
                          $this->session->set_flashdata('error', lang("check_category") . " (" . $csv_pr['category'] . "). " . lang("category_x_already_exist"));
                          redirect("items/import");
                      }
                    }
                    $data[] = array(
                        'code' => $csv_pr['code'],
                        'name' => $csv_pr['name'],
                        'quantity' => $csv_pr['quantity'],
                        'unit' => $csv_pr['unit'],
                        'alert_quantity' => $csv_pr['alert_quantity'],
                        'image' => $csv_pr['image'],
                        'category_id' => $category ? $category->id : NULL
                    );
                }
                // $this->tec->print_arrays($data);
            }

        }

        if ($this->form_validation->run() == true && $this->items_model->add_items($data)) {

            $this->session->set_flashdata('message', lang("items_added"));
            redirect('items');

        } else {

            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
            $this->data['page_title'] = lang('import_items');
            $this->page_construct('items/import', $this->data);

        }
    }

    public function delete($id = NULL) {
        if (!$this->Admin) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect('items');
        }
        if ($this->items_model->deleteItem($id)) {
            $this->session->set_flashdata('message', lang("item_deleted"));
            redirect("items");
        } else {
            $this->session->set_flashdata('error', lang("delete_failed"));
            redirect("items");
        }
    }

    /* -------------------------------------------------------------------------------- */


    function item_barcode($item_code = NULL, $bcs = 'code39', $height = 60) {
        if ($this->input->get('code')) {
            $item_code = $this->input->get('code');
        }
        return "<img src='" . base_url() . "items/gen_barcode/{$item_code}/{$bcs}/{$height}' alt='{$item_code}' />";
    }

    function gen_barcode($item_code = NULL, $bcs = 'code39', $height = 60, $text = 1) {
        $drawText = ($text != 1) ? FALSE : TRUE;
        $this->load->library('zend');
        $this->zend->load('Zend/Barcode');
        $barcodeOptions = array('text' => $item_code, 'barHeight' => $height, 'drawText' => $drawText);
        $rendererOptions = array('imageType' => 'png', 'horizontalPosition' => 'center', 'verticalPosition' => 'middle');
        $imageResource = Zend_Barcode::render($bcs, 'image', $barcodeOptions, $rendererOptions);
        return $imageResource;
    }


    function print_barcodes($per_page = 0) {

        $this->load->library('pagination');
        if ($this->input->get('per_page')) { $per_page = $this->input->get('per_page'); }

        $config['base_url'] = site_url('items/print_barcodes');
        $config['total_rows'] = $this->items_model->items_count();
        $config['per_page'] = 16;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);

        $items = $this->items_model->fetch_items($config['per_page'], $per_page);
        $r = 1;
        $html = "";
        $html .= '<table class="table table-bordered">
        <tbody><tr>';
        foreach ($items as $pr) {
            if ($r != 1) {
                $rw = (bool)($r & 1);
                $html .= $rw ? '</tr><tr>' : '';
            }
            $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $pr->name . '</strong><br>' . $this->item_barcode($pr->code, $pr->barcode_symbology, 60) . '</td>';
            $r++;
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme.'items/print_barcodes', $this->data);

    }

    function print_labels($per_page = 0) {

        $this->load->library('pagination');
        if ($this->input->get('per_page')) { $per_page = $this->input->get('per_page'); }

        $config['base_url'] = site_url('items/print_labels');
        $config['total_rows'] = $this->items_model->items_count();
        $config['per_page'] = 10;
        $config['num_links'] = 5;

        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        $this->pagination->initialize($config);

        $items = $this->items_model->fetch_items($config['per_page'], $per_page);

        $html = "";

        foreach ($items as $pr) {
            $html .= '<div class="labels"><strong>' . $pr->name . '</strong><br>' . $this->item_barcode($pr->code, $pr->barcode_symbology, 25) . '</div>';
        }

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_labels");
        $this->load->view($this->theme.'items/print_labels', $this->data);

    }

    function single_barcode($item_id = NULL)
    {
        $item = $this->items_model->getItemByID($item_id);
        $html = "";
        $html .= '<table class="table table-bordered">
        <tbody><tr>';
        if($item->quantity > 0) {
            for ($r = 1; $r <= $item->quantity; $r++) {
                if ($r != 1) {
                    $rw = (bool)($r & 1);
                    $html .= $rw ? '</tr><tr>' : '';
                }
                $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 60) . '</td>';
            }
        } else {
            for ($r = 1; $r <= 16; $r++) {
            if ($r != 1) {
                $rw = (bool)($r & 1);
                $html .= $rw ? '</tr><tr>' : '';
            }
            $html .= '<td><h4>' . $this->Settings->site_name . '</h4><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 60) . '</td>';
        }
        }
        $html .= '</tr></tbody>
        </table>';

        $this->data['html'] = $html;
        $this->data['page_title'] = lang("print_barcodes");
        $this->load->view($this->theme . 'items/single_barcode', $this->data);
    }

    function single_label($item_id = NULL, $warehouse_id = NULL)
    {
        $item = $this->items_model->getItemByID($item_id);
        $html = "";
        if($item->quantity > 0) {
            for ($r = 1; $r <= $item->quantity; $r++) {
                $html .= '<div class="labels"><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 25) . '</div>';
            }
        } else {
            for ($r = 1; $r <= 10; $r++) {
                $html .= '<div class="labels"><strong>' . $item->name . '</strong><br>' . $this->item_barcode($item->code, $item->barcode_symbology, 25) . '</div>';
            }
        }
        $this->data['html'] = $html;
        $this->data['page_title'] = lang("barcode_label");
        $this->load->view($this->theme . 'items/single_label', $this->data);

    }



}
