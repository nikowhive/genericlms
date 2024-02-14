<?php
$CI =& get_instance(); 
/* List Language  */
$lang['courses_name'] = "Courses";
$lang['classes_name'] = "Class";
$lang['subject_name'] = "Subject";


if($CI->uri->segment(2)=='contents'){
    $lang['panel_title'] = "Content List";
}elseif($CI->uri->segment(2)=='attachments'){
    $lang['panel_title'] = "Attachment List";
}
elseif($CI->uri->segment(2)=='links'){
    $lang['panel_title'] = "Link List";
}elseif($CI->uri->segment(2)=='quizzes'){
    $lang['panel_title'] = "Quiz List";
}
elseif($CI->uri->segment(2)=='assignment'){
    $lang['panel_title'] = "Assignment List";
}
elseif($CI->uri->segment(2)=='homework'){
    $lang['panel_title'] = "Homework List";
}elseif($CI->uri->segment(2)=='classwork'){
    $lang['panel_title'] = "Classwork List";
}
elseif($CI->uri->segment(2)=='student_view'){
    $lang['panel_title'] = "Student View";
}
elseif($CI->uri->segment(2)=='annual'){
    $lang['panel_title'] = "Annual Plan";
}
elseif($CI->uri->segment(2)=='lesson'){
    $lang['panel_title'] = "Lesson Plan";
}
elseif($CI->uri->segment(2)=='daily'){
    $lang['panel_title'] = "Daily Plan";
}
else{
    $lang['panel_title'] = "Course List";
}

$lang['select_course'] = "Select Courses";
$lang['select_class'] = "Select Classes";
$lang['select_subject'] = "Select Subjects";
$lang['select_unit'] = "Select Units";
$lang['select_chapter'] = "Select Chapters";
$lang['select_section'] = "Select Sections";
$lang['add_assignment'] = "Add an Assignment";
$lang['add_homework'] = "Add a Homework";
$lang['add_classwork'] = "Add a Classwork";
$lang['take_exam_name'] = "Name";
$lang['take_exam_duration'] = "Duration";
$lang['take_exam_no_question'] = "Don't Have any question";
$lang['take_exam_mark'] = "Mark";
$lang['take_exam_add'] = "Add";
$lang['take_exam_question'] = "Question";
$lang['take_exam_of'] = "of";
$lang['take_exam_time_status'] = "Time Status";
$lang['take_exam_total_time'] = "Total Time";
$lang['take_exam_summary'] = "Summary";
$lang['take_exam_answered'] = "Answered";
$lang['take_exam_marked'] = "Marked";
$lang['take_exam_not_answer'] = "Not Answered";
$lang['take_exam_not_visited'] = "Not Visited";
$lang['take_exam_next'] = "Next";
$lang['take_exam_previous'] = "Previous";
$lang['take_exam_finish'] = "Finish";
$lang['take_exam_clear_answer'] = "Clear Answer";
$lang['take_exam_mark_review'] = "Mark For Review & Next";
$lang['take_exam_start_exam'] = "Start Exam";
$lang['take_exam_exam_name'] = "Exam Name";
$lang['take_exam_warning'] = "Warning";
$lang['take_exam_page_refresh'] = "Do Not Press Back/refresh Button";
$lang['action'] = "Action";
$lang['take_exam_registerNO'] = "Register NO";
$lang['take_exam_roll'] = "Roll";
$lang['take_exam_class'] = "Class";
$lang['take_exam_section'] = "Section";
$lang['take_exam_total_question'] = "Total Question";
$lang['take_exam_total_answer'] = "Total Answer";
$lang['take_exam_total_current_answer'] = "Total Correct Answer";
$lang['take_exam_total_mark'] = "Total Mark";
$lang['take_exam_total_obtained_mark'] = "Total Obtained Mark";
$lang['take_exam_total_percentage'] = "Total Percentage";

$lang['take_exam_pass'] = "PASS";
$lang['take_exam_fail'] = "FAIL";
$lang['take_exam_examstatus'] = "Exam Status";
$lang['take_exam_onetime'] = "One Time";
$lang['take_exam_multipletime'] = "Multiple Time";
$lang['take_exam_exam_info'] = "Exam Info";

$lang['take_exam_taken'] = "Taken";
$lang['take_exam_anytime'] = "Any Time";
$lang['take_exam_today_only'] = "Today Only";
$lang['take_exam_retaken'] = "Retaken";
$lang['take_exam_expired'] = "Expired";
$lang['take_exam_running'] = "Running";
$lang['take_exam_attend'] = "Attend";
$lang['take_exam_upcoming'] = "Upcoming";
$lang['take_exam_todays'] = "Todays";
$lang['take_exam_days'] = "Days";
$lang['take_exam_exam_not_found'] = "Exam information not found";
$lang['take_exam_not_published'] = "The exam was not published";
$lang['take_exam_not_allowed'] = 'The exam not allowed';
$lang['take_exam_exam_expired'] = "Exam Expired";
$lang['take_exam_exam_upcoming'] = "Exam Upcoming";


$lang['onlineexamreport_onlineexam'] = "Online Exam";
$lang['onlineexamreport_report_for'] = "Report For";
$lang['onlineexamreport_please_select'] = "Please Select";
$lang['onlineexamreport_select_all_section'] = "All Section";
$lang['onlineexamreport_select_all_classes'] = "All Class";
$lang['onlineexamreport_schoolyear'] = "School Year";
$lang['onlineexamreport_classes'] = "Class";
$lang['onlineexamreport_exam'] = "Exam";
$lang['onlineexamreport_course'] = "Course";
$lang['onlineexamreport_name'] = "Name";
$lang['onlineexamreport_section'] = "Section";
$lang['onlineexamreport_student'] = "Student";
$lang['onlineexamreport_status'] = "Status";
$lang['onlineexamreport_data_not_found'] = "Don't have any data";
$lang['onlineexamreport_photo'] = "Photo";
$lang['onlineexamreport_obtained_mark'] = "Obtained Mark";
$lang['onlineexamreport_percentage'] = "Percentage";
$lang['onlineexamreport_datetime'] = "Date Time";
$lang['onlineexamreport_subject'] = "Subject";
$lang['onlineexamreport_rank'] = "Rank";
$lang['onlineexamreport_id'] = "ID";

$lang['onlineexamreport_passed'] = "Passed";
$lang['onlineexamreport_failed'] = "Failed";
$lang['onlineexamreport_submit'] = "Get Report";

$lang['action'] = "Action";
$lang['view'] = "View";

$lang['onlineexamreport_question'] = "Total Question";
$lang['onlineexamreport_answer'] = "Total Answer";
$lang['onlineexamreport_current_answer'] = "Total Currect Answer";
$lang['onlineexamreport_mark'] = "Total Mark";
$lang['onlineexamreport_totle_obtained_mark'] = "Total Obtained Mark";
$lang['onlineexamreport_total_percentage'] = "Total Percentage";

$lang['onlineexamreport_examinformation'] = "Exam Information";
$lang['onlineexamreport_studentinformation'] = "Student Information";

$lang['onlineexamreport_phone'] = "Phone";
$lang['onlineexamreport_email'] = "Email";
$lang['onlineexamreport_address'] = "Address";

$lang['onlineexamreport_hotline'] = "Hotline";

$lang['onlineexamreport_mail'] = "Send Pdf To Mail";
$lang['onlineexamreport_to'] = "To";
$lang['onlineexamreport_subject'] = "Subject";
$lang['onlineexamreport_message'] = "Message";
$lang['onlineexamreport_close'] = "Close";
$lang['onlineexamreport_send'] = "Send";

$lang['onlineexamreport_mail_to'] = "The To field is required";
$lang['onlineexamreport_mail_valid'] = "The To field must contain a valid email address";
$lang['onlineexamreport_mail_subject'] = "The Subject field is required";
$lang['mail_success'] = 'Email send successfully';
$lang['mail_error'] = 'Oops, Email not send';

$lang['onlineexamreport_id_not found'] = "ID not found";
$lang['onlineexamreport_onlineexam_found'] = "Online exam does not found";
$lang['onlineexamreport_permission'] = "Permission not allowed";
$lang['onlineexamreport_permissionmethod'] = "Method not allowed";
