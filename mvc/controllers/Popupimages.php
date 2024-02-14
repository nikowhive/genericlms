<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Popupimages extends Admin_Controller {
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
		$this->load->model("popupimages_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('popup_images', $language);	
	}

	public function index() {
		$this->data['images'] = $this->popupimages_m->get_images();
		$this->data["subview"] = "popupimages/index";
		$this->load->view('_layout_main', $this->data);
	}

    public function upload() {
		//if(permissionChecker('popup_image_add')) {
			
			if($_FILES['file']['name']=="" || $_POST['title'] == '') {
				$this->session->set_flashdata('error', 'Please select file! and title.');
				redirect(base_url('popupimages/index'));
			} else {
				$array = array();
				$file_name = $_FILES["file"]['name'];
				$file_name_rename = random19();
	            $explode = explode('.', $file_name);
	            if(customCompute($explode) >= 2) {
		            $new_file = $file_name_rename.'.'.end($explode);
					$config['upload_path'] = "./uploads/popupimages";
					$config['allowed_types'] = "gif|jpg|png|jpeg";
					$config['file_name'] = $new_file;
					// $config['max_size'] = '5120';
					// $config['max_width'] = '3000';
					// $config['max_height'] = '3000';
					$array['file_name'] = $new_file;
                    $array['title'] = $_POST['title'];
					$this->load->library('upload', $config);
					if(!$this->upload->do_upload("file")) {
						$this->data["attachment_error"] = $this->upload->display_errors();
						$this->session->set_flashdata('error', $this->data["attachment_error"]);
						redirect(base_url("popupimages/index"));
					} else {
						$data = array("upload_data" => $this->upload->data());
						$this->popupimages_m->insert_image(['title' => $_POST['title'],'file_name' => $new_file, ]);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("popupimages/index"));
					}
			 	} else {
					$this->data["attachment_error"] = "Invalid file";
					$this->session->set_flashdata('error', 'invalid file format! please upload only gif|jpg|png|pdf|docx|doc|csv|txt|ppt|xls|xlsx files');
					redirect(base_url("popupimages/index"));
				}
			}
		// } else {
		// 	$this->data["subview"] = "errorpermission";
		// 	$this->load->view('_layout_main', $this->data);
		// }
	}


    public function delete() {
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if($id) {
			$file = $this->popupimages_m->get_images($id);
			if($file) {
				$path = "uploads/popupimages/".$file->file_name;
				if(config_item('demo') == FALSE) {
					if (unlink($path)) {
						$this->popupimages_m->delete_image($id);
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
					}
				} else {
					$this->popupimages_m->delete_image($id);
					$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				}
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				redirect($_SERVER['HTTP_REFERER']);
			}
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}

    public function updateView(){
        $id = htmlentities(escapeString($this->uri->segment(3)));
		if($id) {
			$file = $this->popupimages_m->get_images($id);
			if($file) {
                $status = $file->disabled == 0?1:0;
                $this->popupimages_m->update_image(['disabled' => $status],$id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect($_SERVER['HTTP_REFERER']);
            }else{
                redirect($_SERVER['HTTP_REFERER']);
            }
        }else{
            redirect($_SERVER['HTTP_REFERER']);
        }
    }




	
}

/* End of file category.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/category.php */