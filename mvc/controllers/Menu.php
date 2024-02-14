<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu extends Admin_Controller {
/*
| -----------------------------------------------------
| PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
| -----------------------------------------------------
| AUTHOR:			INILABS TEAM
| -----------------------------------------------------
| EMAIL:			info@inilabs.net
| -----------------------------------------------------
| COPYRIGHT:		RESERVED BY INILABS IT
| -----------------------------------------------------
| WEBSITE:			http://inilabs.net
| -----------------------------------------------------
*/
	function __construct() {
		parent::__construct();
		$this->load->model("menu_m");
		$this->load->model("Permission_m");
		$userTypeID = $this->session->userdata('usertypeID');
		if($userTypeID != 1){
			if(config_item('demo') == FALSE || ENVIRONMENT == 'production') {
				redirect('dashboard/index');
			}
		}
		
	}

	public function index() {
		
		$this->data['menus'] = $this->menu_m->get_order_by_menu();
		$this->data["subview"] = "menu/index";
		$this->load->view('_layout_main', $this->data);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'menuName',
				'label' => 'Menu Name',
				'rules' => 'trim|required|xss_clean|max_length[120]'
			),
			array(
				'field' => 'parentID',
				'label' => 'Parent',
				'rules' => 'trim|numeric|max_length[11]|xss_clean'
			),
			array(
				'field' => 'link',
				'label' => 'Link',
				'rules' => 'trim|required|xss_clean|callback_duplicate'
			),
			array(
				'field' => 'icon',
				'label' => 'Icon',
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'status',
				'label' => 'Status',
				'rules' => 'trim|numeric|xss_clean'
			),
			array(
				'field' => 'priority',
				'label' => 'Priority',
				'rules' => 'trim|numeric|max_length[200]|xss_clean'
			),
            array(
				'field' => 'pullRight',
				'label' => 'Pull Right',
				'rules' => 'trim|max_length[200]|xss_clean'
			)
		);
		return $rules;
	}

	public function add() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

        $this->data['menus'] = $this->menu_m->get_order_by_menu();

		if($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "menu/add";
				$this->load->view('_layout_main', $this->data);
			} else {
				if($this->menu_m->insert_menu(array_filter($this->input->post()))){
					$data = [
						'description' => $this->input->post('menuName'),
						'name'        => $this->input->post('link'),
					];
					$this->permission_m->insert_permission($data);
				}
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("menu/index"));
			}
		} else {
			$this->data["subview"] = "menu/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);
		
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$id) {
			$this->data['menu'] = $oldMenu =  $this->menu_m->get_menu($id);
            $this->data['menus'] = $this->menu_m->get_order_by_menu();

			if($this->data['menu']) {
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "menu/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $postData = $this->input->post();
                        $postData['status'] = (int) $postData['status'];
						
						if($this->menu_m->update_menu($postData, $id)){
							if($oldMenu->menuName != $this->input->post('menuName') || $oldMenu->link != $this->input->post('link') ){
								$oldPermission = $this->permission_m->general_get_single_permission(['name' => $oldMenu->link]);
								$data = [
									'description' => $this->input->post('menuName'),
									'name'        => $this->input->post('link'),
								];
								$this->permission_m->update_permission($data,$oldPermission->permissionID);
							}
							
						}
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("menu/index"));
					}
				} else {
					$this->data["subview"] = "menu/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$menu = $this->menu_m->get_menu($id); 
		$link = $menu->link;
		if((int)$id) {
			if($this->menu_m->delete_menu($id)){
				$permission = $this->permission_m->general_get_single_permission(['name' => $link]);
				if($permission){
					$this->permission_m->delete_permission($permission->permissionID);
					$this->permission_m->delete_permission_relation_byid($permission->permissionID);
			    }				
			};
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			redirect(base_url("menu/index"));
		} else {
			redirect(base_url("menu/index"));
		}
	}

    public function menuList()
    {
        $menus = json_decode(json_encode(pluck($this->menu_m->get_order_by_menu(), 'obj', 'menuID')), true);
        dd($this->menuTrees($menus));
    }


    public function menuTrees($dataset) {
    	$tree = array();
    	foreach ($dataset as $id=>&$node) {
    		if ($node['parentID'] == 0) {
    			$tree[$id]=&$node;
    		} else {
    			if (!isset($dataset[$node['parentID']]['child']))
    				$dataset[$node['parentID']]['child'] = array();
    			$dataset[$node['parentID']]['child'][$id] = &$node;
    		}
    	}
    	return $tree;
    }

	public function duplicate(){
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int) $id) {
			$menu = $this->menu_m->general_get_single_menu(array("link" => $this->input->post("link"), "menuID !=" => $id));
			if (customCompute($menu)) {
				$this->form_validation->set_message("duplicate", "The %s is already exists.");
				return false;
			}
			return true;
		} else {
			$menu = $this->menu_m->general_get_single_menu(array("link" => $this->input->post("link")));
			if (customCompute($menu)) {
				$this->form_validation->set_message("duplicate", "The %s is already exists.");
				return false;
			}
			return true;
		}
	}
}
