<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Classdetail extends Admin_Controller {
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
        $this->load->model("faq_m");
        $this->load->model("classes_m");
        $this->load->model("classgroup_m");
        $this->load->model("classes_block_type_m");
        $this->load->model("classes_content_blocks_m");
        $this->load->model("classes_detail_content_m");
        $this->load->model("enrollment_m");
        $this->load->model("classes_extra_information_m");
        $this->load->helper("frontenddata");
        $language = $this->session->userdata('lang');
        $this->lang->load('classdetail', $language);
    }

    public function index() {

        $this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css'
			),
			'js' => array(
				'assets/select2/select2.js'
			)
		);

        $this->data['classgroups'] = $this->classgroup_m->get_order_by_classgroup();
        $this->data['classes'] = [];
        $this->data["subview"] = "classdetail/index";
        $this->load->view('_layout_main', $this->data);

    }

    public function detailsTemplate() {

        $classesID = $this->input->post('classesID');
        $contentBlocks = $this->classes_content_blocks_m->get_order_by_content_blocks(['classes_id' => $classesID]);
        $newContentBlocks = [];
        if(customCompute($contentBlocks)){
            foreach($contentBlocks as $contentBlock){
                if($contentBlock->type_id == 1){
                    $detail = $this->classes_detail_content_m->get_single_detail_content(['classes_content_blocks_id'=> $contentBlock->id]);
                    $newContentBlocks[] = [
                        'blockID'        => $contentBlock->id,
                        'type_id'        => $contentBlock->type_id,
                        'order'          => $contentBlock->order,
                        'title'          => $detail->title,
                        'description'    => $detail->body,
                        'image'          => $detail->image,
                    ];
                }else{
                    $newContentBlocks[] = [
                        'blockID'        => $contentBlock->id,
                        'type_id'        => $contentBlock->type_id,
                        'order'          => $contentBlock->order,
                    ];
                }
            }
        }
        
        $this->data['newContentBlocks'] = $newContentBlocks;
        $this->data['faqs'] = $faqs = $this->faq_m->get_faq_by_class($classesID);
        if(customCompute($faqs)){
            $faqids = pluck($faqs,'id');
            $this->data['otherfaqs'] = $this->faq_m->get_other_faq_by_class($faqids);
        }else{
            $this->data['otherfaqs'] = $this->faq_m->get_other_faq_by_class();
        }

        $this->data['enrollments'] = $enrollments = $this->enrollment_m->get_enrollment_by_class($classesID);
        if(customCompute($enrollments)){
            $enrollids = pluck($enrollments,'id');
            $this->data['otherenrollments'] = $this->enrollment_m->get_other_enrollment_by_class($enrollids);
        }else{
            $this->data['otherenrollments'] = $this->enrollment_m->get_other_enrollment_by_class();
        }

        $this->data['extraInformations'] = $this->classes_extra_information_m->get_single_classes_extra_information(['classes_id' => $classesID]);
        $this->data['class'] = $this->classes_m->getClassByID(['classesID' => $classesID]);
        $this->data['blockTypes'] = $this->classes_block_type_m->get_order_by_blocktype();
        echo $this->load->view('classdetail/detail', $this->data, true);
    }

    public function saveContent(){
        
        $contents = $_POST['contents']; 
        $classes_id = $_POST['classes_id'];
        $extraInformations = $_POST['extraDetails'];
        $status = $_POST['status'];

        $extraData = [
            'classes_id'          => $classes_id,
            'description'         => $extraInformations['class_description'],
            'student'             => $extraInformations['student'],
            'study_mode'          => $extraInformations['study_mode'],
            'campus_location'     => $extraInformations['campus_location'],
            'duration'            => $extraInformations['duration'],
            'total_hours'         => $extraInformations['total_hours'],
            'fees'                => $extraInformations['fees'],
            'tution_fees_onshore' => $extraInformations['tution_fees_onshore'],
            'tution_fees_offshore_no_coe_visa' => $extraInformations['tution_fees_offshore_no_coe_visa'],
            'domestic_vet'        => $extraInformations['domestic_vet'],
            'discounted_fees'     => $extraInformations['discounted_fees'],
            'material_fees'       => $extraInformations['material_fees'],
            'enrollment_fees'     => $extraInformations['enrollment_fees'],
            'covid_scholarship'   => $extraInformations['covid_scholarship'],
            'cricos'              => $extraInformations['cricos'],
            "create_date"         => date("Y-m-d H:i:s"),
            "modify_date"         => date("Y-m-d H:i:s"),
			"create_userID"       => $this->session->userdata('loginuserID'),
			"create_usertypeID"   => $this->session->userdata('usertypeID')
        ]; 

        if(customCompute($contents)){

            // dd($_FILES["contents"]);
            // upload files
            if (isset($_FILES['contents']['name']['photo'])) {
                if (count($_FILES["contents"]['name']['photo']) > 0) {
                    $uploadPath = 'uploads/class-detail';
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    //check if any file uploaded
                    $acceptable = array("gif", "jpeg", "jpg", "png");

                    for ($j = 0; $j < count($_FILES["contents"]['name']['photo']); $j++) {

                        if($_FILES["contents"]['name']['photo'][$j] != ''){
                       
                        //loop the uploaded file array
                        $filename = $_FILES["contents"]['name']['photo'][$j]; //file name
                        
                        $explode = explode('.', $filename);
                        $ext = end($explode);
                        $path = './uploads/class-detail/' . $filename; //generate the destination path
                        if (in_array($ext, $acceptable)) {
                            if (!move_uploaded_file($_FILES["contents"]['tmp_name']['photo']["$j"], $path)) {
                                $retArray['status'] = False;
                                $retArray['message'] = 'Unable to upload this type of file: '.$filename;
                                echo json_encode($retArray);
                                exit;
                            }
                        }else{
                                $retArray['status'] = False;
                                $retArray['error'] = 'Unable to upload file: '.$filename;
                                echo json_encode($retArray);
                                exit;
                        }
                    }
                    }
                }
            }


            $oldContents = $this->classes_content_blocks_m->get_order_by_content_blocks(['classes_id' => $classes_id]);
            if(customCompute($oldContents)){
                $this->classes_content_blocks_m->delete_all_content_blocks(['classes_id' => $classes_id]);
            }

            $oldContentDetails = $this->classes_detail_content_m->get_order_by_detail_content(['classes_id' => $classes_id]);
            if(customCompute($oldContentDetails)){
                $this->classes_detail_content_m->delete_all_detail_content(['classes_id' => $classes_id]);
            }

                
            foreach($contents as $content){

                $contentBlocksArray = [
                    "type_id"             =>  $content["block_type"],
                    "classes_id"          =>  $classes_id,
                    "order"               =>  $content["content_order"],
                    "create_date"         => date("Y-m-d H:i:s"),
					"create_userID"       => $this->session->userdata('loginuserID'),
					"create_usertypeID"   => $this->session->userdata('usertypeID')
                ];
               
                $this->classes_content_blocks_m->insert_content_blocks($contentBlocksArray);
                $insert_id = $this->db->insert_id();

                if($insert_id){
                    if($content['block_type'] == 1){
                        $classesDetailsArray = [
                            "title"                     =>  $content["title"],
                            "body"                      =>  $content["description"],
                            "image"                     =>  $content["attachment"],
                            "classes_id"                =>  $classes_id,
                            "classes_content_blocks_id" =>  $insert_id,
                            "create_date"               =>  date("Y-m-d H:i:s"),
                            "create_userID"             =>  $this->session->userdata('loginuserID'),
                            "create_usertypeID"         =>  $this->session->userdata('usertypeID')
                        ];
                        $this->classes_detail_content_m->insert_detail_content($classesDetailsArray);
                    }
                }
            }

            $this->classes_m->update_classes(['status' => $status], $classes_id);
        }

        if(customCompute($extraInformations)){
           $oldExtraInformations = $this->classes_extra_information_m->get_single_classes_extra_information(['classes_id' => $classes_id]);
           if($oldExtraInformations){
                $this->classes_extra_information_m->update_classes_extra_information($extraData,$oldExtraInformations->id);
           }else{
                $this->classes_extra_information_m->insert_classes_extra_information($extraData);
           }
        }
        
        $retArray['status'] = TRUE;
		$retArray['message'] = 'Details successfully added.';
		echo json_encode($retArray);
        exit;


    }

    public function deleteCotentBlocks(){
         
         $blockID = $this->input->get('blockID');
         $contentBlock = $this->classes_content_blocks_m->get_content_blocks($blockID);
         if($contentBlock){
            $this->classes_content_blocks_m->delete_content_blocks($contentBlock->id);
            if($contentBlock->type_id == 1){
                $contentBlockDetail = $this->classes_detail_content_m->get_single_detail_content(['classes_content_blocks_id' => $contentBlock->id]);
                if($contentBlockDetail){
                    $this->classes_detail_content_m->delete_detail_content($contentBlockDetail->id);
                    $path_to_file = FCPATH.'uploads/class-detail/'.$contentBlockDetail->image;
                    if(file_exists($path_to_file)){
                       unlink($path_to_file);
                    }
                }
            }
         }

         $retArray['status'] = TRUE;
         $retArray['message'] = 'Block successfully deleted.';
         echo json_encode($retArray);
         exit;
         

    }

    
    public function getclasses() {

		$classgroupID  = $this->input->post('classgroupID');
		

		$array = [
            'classgroupID' => $classgroupID
        ];
		
		$classes = $this->classes_m->general_get_order_by_classes($array);
		echo "<option value='0'>", $this->lang->line("classdetail_please_select"),"</option>";
		foreach ($classes as $class) {
			echo "<option value=".$class->classesID.">".$class->classes."</option>";
		}
	}

    public function importFaq(){

       $ids = $this->input->get('ids');
       $classesID = $this->input->get('classesID');

        $idsArray = $ids;
        $data = [];
        foreach($idsArray as $value){
            $row = $this->faq_m->get_single_faq_classes_relation([
                'classes_id' => $classesID,
                'faq_id'     => $value
            ]); 
            if(!$row){
                $data[] = [
                    'classes_id' => $classesID,
                    'faq_id'     => $value
                ];
            }
        }
       
        if(customCompute($data)){
            if($this->faq_m->insert_batch_faq_relation($data)){
                $faqs = $this->faq_m->get_faqs($idsArray);
                $html = '';
                foreach($faqs as $faq){
                    $html = $html.'<li id="f'.$faq->id.'">'.$faq->question.' <a href="javascript:void(0)" class="removeFaq btn btn-xs btn-default" data-id="'.$faq->id.'" title="Remove FAQ"><i class="fa fa-times"></i></a></li>';
                }
                $retArray['status'] = true;
                $retArray['template'] = $html;
                $retArray['message'] = 'FAQ added.';
                echo json_encode($retArray);
                exit;
            }else{
                $retArray['status'] = false;
                $retArray['message'] = 'Unable to add FAQ.';
                echo json_encode($retArray);
                exit;
            }
        }else{
            $retArray['status'] = false;
            $retArray['message'] = 'Already added.';
            echo json_encode($retArray);
            exit;
        }

    }

    public function removeFAQ(){
       $faqID = $this->input->get('id');
       $classesId = $this->input->get('classesId');
       $this->faq_m->delete_faq_relation_by_class_id($faqID,$classesId);
       echo true;
	}

    public function removedFaqs()
    {
        $classID = $this->input->get('classesId');
        if ((int) $classID) {
            $faqs = $this->faq_m->get_faq_by_class($classID);
            if(customCompute($faqs)){
                $faqids = pluck($faqs,'id');
                $otherfaqs = $this->faq_m->get_other_faq_by_class($faqids);
            }else{
                $otherfaqs = $this->faq_m->get_other_faq_by_class();
            }
            $array = [];
                if(customCompute($otherfaqs)) {
                    foreach ($otherfaqs as $otherfaq) {
                        $array[$otherfaq->id] = $otherfaq->question;
                    }
                }
                echo form_multiselect("faqID[]", $array, set_value("faqID"), "id='faqID' class='mdb-select'");
        }
    }

    public function importEnrollment(){

        $ids = $this->input->get('ids');
        $classesID = $this->input->get('classesID');
 
         $idsArray = $ids;
         $data = [];
         $newidsArray = [];
         foreach($idsArray as $value){
             $row = $this->enrollment_m->get_single_enrollment_classes_relation([
                 'class_id' => $classesID,
                 'enrollment_id'     => $value
             ]); 
             if(!$row){
                 $newidsArray[] = $value;
                 $data[] = [
                     'class_id' => $classesID,
                     'enrollment_id'     => $value
                 ];
             }
         }
        
         if(customCompute($data)){
             if($this->enrollment_m->insert_batch_enrollment_relation($data)){
                 $entrollments = $this->enrollment_m->get_enrollments($newidsArray);
                 $html = '';
                 foreach($entrollments as $entrollment){
                     $label = 
                     $html = $html.'<label class="label label-success">'.$entrollment->title.'<a href="javascript:void(0)" class="removeEnrollment" data-id="'.$entrollment->id.'" title="Remove"> <i class="fa fa-times"></i></a></label>';
                    }
                 $retArray['status'] = true;
                 $retArray['template'] = $html;
                 $retArray['message'] = 'Enrolment added.';
                 echo json_encode($retArray);
                 exit;
             }else{
                 $retArray['status'] = false;
                 $retArray['message'] = 'Unable to add Enrolment.';
                 echo json_encode($retArray);
                 exit;
             }
         }else{
             $retArray['status'] = false;
             $retArray['message'] = 'Already added.';
             echo json_encode($retArray);
             exit;
         }
 
     }

     public function removeEnrollment(){
        $id = $this->input->get('id');
        $classesId = $this->input->get('classesId');
        $this->enrollment_m->delete_enrollment_relation_by_class_id($id,$classesId);
        echo true;
     }
     public function removedEnrollments()
    {
        $classID = $this->input->get('classesId');
        if ((int) $classID) {
            $enrollments = $this->enrollment_m->get_enrollment_by_class($classID);
            
            
            if(customCompute($enrollments)){
                $enrollids = pluck($enrollments,'id');
                $otherenrollments = $this->enrollment_m->get_other_enrollment_by_class($enrollids);
            }else{
                $otherenrollments = $this->enrollment_m->get_other_enrollment_by_class();
            }
            // if ($otherenrollments) {
            //     $retArray['enroll'] = $otherenrollments;
            // } 
            //echo json_encode($retArray);
            $array = [];
             foreach ($otherenrollments as $otherenrollment) {
                $array[$otherenrollment->id] = $otherenrollment->title;
                }  
            echo form_multiselect("enrollID[]", $array, set_value("enrollID"), "id='enrollID' class='mdb-select'");
        }
    }

     public function switchToPending(){

        $classesID = $this->input->get('classesID');
        $this->classes_m->update_classes(['status' => 'pending'],$classesID);
        echo true;
     }


}


