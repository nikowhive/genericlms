<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Book extends Admin_Controller
{
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
	function __construct()
	{
		parent::__construct();
		$this->load->model("book_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('book', $language);
	}

	public function index()
	{
		$this->data['books'] = $this->book_m->get_order_by_book();
		$this->data["subview"] = "book/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function getBooks()
	{
		$books = $this->book_m->getRows($_POST);
		$i = $_POST['start'];
		foreach ($books as $book) {
			$i++;
			if ($book->quantity == $book->due_quantity) {
				$status =  "<button class='btn btn-danger btn-xs'>" . $this->lang->line('book_unavailable') . "</button>";
			} else {
				$status = "<button class='btn btn-success btn-xs'>" . $this->lang->line('book_available') . "</button>";
			}
			

			if (permissionChecker('book_edit') || permissionChecker('book_delete') ) {
				$editbtn =  btn_edit('book/edit/' . $book->bookID, $this->lang->line('edit'));
				$deletebtn = btn_delete('book/delete/' . $book->bookID, $this->lang->line('delete'));
				$viewbtn =  btn_view('book/view/' . $book->bookID, $this->lang->line('view'));
				$action = $editbtn . $deletebtn . $viewbtn;
			} else {
				$action = '';
			}

			$additionalData = $this->book_m->get_addtional_book_detail($book->bookID);

			if (isset($additionalData) && !empty($additionalData)) {
				$retdata['publisher'] = $additionalData->publisher;
				$retdata['edition'] = $additionalData->edition;
			} else {
				$retdata['publisher'] = '';
				$retdata['edition'] = '';
			}

			$data[] = array(
				$i,
				$book->book,
				$book->author,
				$book->call,
				$retdata['publisher'],
				$retdata['edition'],
				$status,
				$action
			);
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => 1,
			"recordsFiltered" => $this->book_m->countFiltered($_POST),
			"data" => isset($data) && !empty($data) ? $data : '',
		);

		// Output to JSON format
		echo json_encode($output);
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'book',
				'label' => $this->lang->line("book_name"),
				'rules' => 'trim|required|xss_clean|max_length[60]|callback_unique_book'
			),
			array(
				'field' =>  'subject_code',
				'label' =>  $this->lang->line("book_subject_code"),
				'rules' => 'trim|required|xss_clean|max_length[3]|numeric|callback_unique_subject_code'
			),
			array(
				'field' =>  'call',
				'label' =>  $this->lang->line("book_call"),
				'rules' => 'trim|required|xss_clean|max_length[60]'
			),
		);
		return $rules;
	}

	public function add()
	{
		if ($_POST) {
			$rules = $this->rules();
			$this->form_validation->set_rules($rules);
			if ($this->form_validation->run() == FALSE) {
				$this->data["subview"] = "book/add";
				$this->load->view('_layout_main', $this->data);
			} else {

				//check book photo
				if ($this->input->post('addtional_field')) {
					$addtional_field = $this->input->post('addtional_field');
					$bookPhoto = '';
					$barcode = '';
					if ($_FILES["book_photo"]['name'] != "" || $_FILES["barcode"]['name'] != "") {
						$fileRule = array(
							array(
								'field' => 'book_photo',
								'label' => $this->lang->line("book_photo"),
								'rules' => 'trim|xss_clean|callback_unique_book_photo'
							),
							array(
								'field' => 'barcode',
								'label' => $this->lang->line("book_barcode"),
								'rules' => 'trim|xss_clean|callback_unique_barcode'
							)

						);
						$this->form_validation->set_rules($fileRule);
						if ($this->form_validation->run() == FALSE) {
							$this->data["subview"] = "book/add";
							$this->load->view('_layout_main', $this->data);
							redirect(base_url("book/add"));
						} else {
							$bookPhoto = $this->upload_data['book_photo']['file_name'];
							$barcode = $this->upload_data['barcode']['file_name'];
						}
					}
				} else {
					$addtional_field = 0;
				}

				$array = array(
					"book" => $this->input->post("book"),
					"author" => $this->input->post("author"),
					"isbn" => $this->input->post("isbn"),
					"call" => $this->input->post("call"),
					"subject_code" => $this->input->post("subject_code"),
					"price" => $this->input->post("price"),
					"quantity" => $this->input->post("quantity"),
					"due_quantity" => 0,
					"rack" => $this->input->post("rack"),
					"addtional_field" => $addtional_field
				);

				//save book data
				$bookID = $this->book_m->insert_book($array);

				// save keywords
				if ($this->input->post('keyword') != '') {
					$keywords =  $this->input->post('keyword');
					$kDatas = explode(',', $keywords);
					$kArray = [];
					foreach ($kDatas as $kData) {
						$kArray[] = [
							'BookID' => $bookID,
							'keyword' => $kData,
						];
					}
					$this->book_m->insert_bookKeywords($kArray);
				}

				//save addtional book data

				if ($this->input->post('addtional_field')) {
					$extrAarray = array(
						"bookID" => $bookID,
						"publisher" => $this->input->post("publisher"),
						"published_year" => $this->input->post("published_year"),
						"place_of_publication" => $this->input->post("place_of_publication"),
						"pages" => $this->input->post("pages"),
						"edition" => $this->input->post("edition"),
						"second_author" => $this->input->post("second_author"),
						"third_author" => $this->input->post("third_author"),
						"language" => $this->input->post("language"),
						"volume" => $this->input->post("volume"),
						"source" => $this->input->post("source"),
						"form" => $this->input->post("form"),
						"barcode" => $barcode,
						"book_photo" => $bookPhoto
					);
					$this->book_m->insert_addtionalBookDetails($extrAarray);
				}

				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("book/index"));
			}
		} else {
			$this->data["subview"] = "book/add";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$this->data['book'] = $this->book_m->get_book($id);
			$this->data['bookDetails'] = $additionalData = $this->book_m->get_addtional_book_detail($id);
			$keywords = $this->book_m->allKeywords($id);
			$this->data['keyword'] = $keywordsVal = $this->getKeyword($keywords);
			if ($this->data['book']) {
				if ($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {

						$this->data['form_validation'] = validation_errors();
						$this->data["subview"] = "book/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						//check book photo
						if ($this->input->post('addtional_field')) {
							
							$addtional_field = $this->input->post('addtional_field');
							$bookPhoto = $additionalData->book_photo;
							$barcode = $additionalData->barcode;
							
							if ($_FILES["book_photo"]['name'] != "" || $_FILES["barcode"]['name'] != "") {

								$fileRule = array(
									array(
										'field' => 'book_photo',
										'label' => $this->lang->line("book_photo"),
										'rules' => 'trim|xss_clean|callback_unique_book_photo'
									),
									array(
										'field' => 'book_barcode',
										'label' => $this->lang->line("barcode"),
										'rules' => 'trim|xss_clean|callback_unique_barcode'
									)
								);
								$this->form_validation->set_rules($fileRule);
								if ($this->form_validation->run() == FALSE) {
									$this->data["subview"] = "book/edit";
									$this->load->view('_layout_main', $this->data);
									redirect(base_url("book/edit/" . $id));
								} else {
									$bookPhoto = $this->upload_data['book_photo']['file_name'];
									$barcode = $this->upload_data['barcode']['file_name'];
								}
							}
						} else {
							$addtional_field = 0;
						}

						if ($keywords) {
							foreach ($keywords as $keyword) {
								$this->book_m->delete_bookKeywords(['id' => $keyword->id]);
							}
						}

						$array = array(
							"book" => $this->input->post("book"),
							"author" => $this->input->post("author"),
							"isbn" => $this->input->post("isbn"),
							"call" => $this->input->post("call"),
							"subject_code" => $this->input->post("subject_code"),
							"price" => $this->input->post("price"),
							"quantity" => $this->input->post("quantity"),
							"rack" => $this->input->post("rack"),
							"addtional_field" => $addtional_field
						);
						$this->book_m->update_book($array, $id);

						// save keywords
						if ($this->input->post('keyword') != '') {
							$keywords =  $this->input->post('keyword');
							$kDatas = explode(',', $keywords);
							$kArray = [];
							foreach ($kDatas as $kData) {
								$kArray[] = [
									'BookID' => $id,
									'keyword' => $kData,
								];
							}
							$this->book_m->insert_bookKeywords($kArray);
						}

						//save addtional book data
						if ($this->input->post('addtional_field')) {
							$extrAarray = array(
								"bookID" => $id,
								"publisher" => $this->input->post("publisher"),
								"published_year" => $this->input->post("published_year"),
								"place_of_publication" => $this->input->post("place_of_publication"),
								"pages" => $this->input->post("pages"),
								"edition" => $this->input->post("edition"),
								"second_author" => $this->input->post("second_author"),
								"third_author" => $this->input->post("third_author"),
								"language" => $this->input->post("language"),
								"volume" => $this->input->post("volume"),
								"source" => $this->input->post("source"),
								"form" => $this->input->post("form"),
								"barcode" => $barcode,
								"book_photo" => $bookPhoto
							);

							if ($additionalData) {
								$this->book_m->update_addtionalBookDetails($extrAarray, ['id' => $additionalData->id]);
							} else {
								$this->book_m->insert_addtionalBookDetails($extrAarray);
							}
						}
						$this->session->set_flashdata('success', $this->lang->line('menu_success'));
						redirect(base_url("book/index"));
					}
				} else {
					$this->data["subview"] = "book/edit";
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

	public function view()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		$this->data['book'] = '';
		if ((int)$id) {
			$this->data['book'] = $this->book_m->get_book($id);
			$keywords = $this->book_m->allKeywords($id);
			$this->data['keywords'] = $this->getKeyword($keywords);
			$this->data['enable'] = $this->data['book']->addtional_field;
			$this->data['additional_fields'] = $this->book_m->get_addtional_book_detail($id);
			$this->data["subview"] = "book/view";
			$this->load->view('_layout_main', $this->data);
		} else {
			$this->data["subview"] = "book/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$book = $this->book_m->get_book($id);
			if (customCompute($book)) {
				$this->book_m->delete_book($id);
				$this->session->set_flashdata('success', $this->lang->line('menu_success'));
				redirect(base_url("book/index"));
			} else {
				redirect(base_url("book/index"));
			}
		} else {
			redirect(base_url("book/index"));
		}
	}

	public function unique_book()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$student = $this->book_m->get_order_by_book(array("book" => $this->input->post("book"), "bookID !=" => $id, "author" => $this->input->post('author'), "subject_code" => $this->input->post('subject_code')));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_book", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->book_m->get_order_by_book(array("book" => $this->input->post("book"), "author" => $this->input->post('author'), "subject_code" => $this->input->post('subject_code')));

			if (customCompute($student)) {
				$this->form_validation->set_message("unique_book", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}
	}

	public function unique_subject_code()
	{
		$id = htmlentities(escapeString($this->uri->segment(3)));
		if ((int)$id) {
			$student = $this->book_m->get_order_by_book(array("bookID !=" => $id, "subject_code" => $this->input->post('subject_code')));
			if (customCompute($student)) {
				$this->form_validation->set_message("unique_subject_code", "%s already exists");
				return FALSE;
			}
			return TRUE;
		} else {
			$student = $this->book_m->get_order_by_book(array("subject_code" => $this->input->post('subject_code')));

			if (customCompute($student)) {
				$this->form_validation->set_message("unique_subject_code", "%s already exists");
				return FALSE;
			}
			return TRUE;
		}
	}

	function valid_number()
	{
		if ($this->input->post('price') && $this->input->post('price') < 0) {
			$this->form_validation->set_message("valid_number", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	function valid_number_for_quantity()
	{
		if ($this->input->post('quantity') && $this->input->post('quantity') < 0) {
			$this->form_validation->set_message("valid_number_for_quantity", "%s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

	public function unique_book_photo()
	{
		$new_file = '';
		if ($_FILES["book_photo"]['name'] != "") {
			$file_name = $_FILES["book_photo"]['name'];
			$random = random19();
			$makeRandom = hash('sha512', $random . (strtotime(date('Y-m-d H:i:s'))) . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/books";
				$config['allowed_types'] = "gif|jpg|png|jpeg|JPG|PNG|JPEG";
				$config['file_name'] = $new_file;
				// $config['max_size'] = '2048';
				// $config['max_width'] = '3000';
				// $config['max_height'] = '3000';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload("book_photo")) {
					$this->form_validation->set_message("unique_book_photo", $this->upload->display_errors());
					$this->session->set_flashdata('error', $this->upload->display_errors());
					return FALSE;
				} else {
					$this->upload_data['book_photo'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_book_photo", "Invalid file");
				$this->session->set_flashdata('error', "Invalid file");
				return FALSE;
			}
		}
	}

	public function unique_barcode()
	{
		$new_file = '';
		if ($_FILES["barcode"]['name'] != "") {
			$file_name = $_FILES["barcode"]['name'];
			$random = random19();
			$makeRandom = hash('sha512', $random . (strtotime(date('Y-m-d H:i:s'))) . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
			$explode = explode('.', $file_name);
			if (customCompute($explode) >= 2) {
				$new_file = $file_name_rename . '.' . end($explode);
				$config['upload_path'] = "./uploads/books";
				$config['allowed_types'] = "gif|jpg|png|jpeg|JPG|PNG|JPEG";
				$config['file_name'] = $new_file;
				// $config['max_size'] = '2048';
				// $config['max_width'] = '3000';
				// $config['max_height'] = '3000';
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if (!$this->upload->do_upload("barcode")) {
					$this->form_validation->set_message("unique_barcode", $this->upload->display_errors());
					$this->session->set_flashdata('error', $this->upload->display_errors());
					return FALSE;
				} else {
					$this->upload_data['barcode'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("unique_barcode", "Invalid file");
				$this->session->set_flashdata('error', "Invalid file");
				return FALSE;
			}
		}
	}

	public function getKeyword($keywords)
	{
		$newArray = [];
		if ($keywords && count($keywords) > 0) {
			foreach ($keywords as $keyword) {
				$newArray[] = $keyword->keyword;
			}
			return implode(',', $newArray);
		} else {
			return '';
		}
	}
}

/* End of file book.php */
/* Location: .//D/xampp/htdocs/school/mvc/controllers/book.php */