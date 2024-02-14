USE erp;
DELIMITER $$
-- Create a temporary stored procedure for checking if Indexes exist before attempting to re-create them.
DROP PROCEDURE IF EXISTS `erp`.`spCreateIndex` $$
CREATE PROCEDURE `erp`.`spCreateIndex` (tableName VARCHAR(128), in indexName VARCHAR(128), in indexColumns VARCHAR(128))
BEGIN
  IF((SELECT COUNT(*) AS index_exists FROM information_schema.statistics WHERE TABLE_SCHEMA = DATABASE() AND table_name = tableName AND index_name = indexName)  = 0) THEN
    SET @sqlCommand = CONCAT('CREATE INDEX ' , indexName , ' ON ' , tableName, '(', indexColumns, ')');
    PREPARE _preparedStatement FROM @sqlCommand;
    EXECUTE _preparedStatement;
  END IF;
END $$
DELIMITER ;

CALL spCreateIndex('activities', 'activitiescategoryID', 'activitiescategoryIDssetID');

CALL spCreateIndex('activities', 'usertypeID', 'usertypeID');

CALL spCreateIndex('activities', 'userID', 'userID');

CALL spCreateIndex('activities', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('activitiescategory', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('activitiescategory', 'userID', 'userID');

CALL spCreateIndex('activitiescategory', 'usertypeID', 'usertypeID');

CALL spCreateIndex('activitiescategory', 'create_date', 'create_date');

CALL spCreateIndex('activitiescategory', 'modify_date', 'modify_date');

CALL spCreateIndex('activitiescomment', 'activitiesID', 'activitiesID');

CALL spCreateIndex('activitiescomment', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('activitiescomment', 'userID', 'userID');

CALL spCreateIndex('activitiescomment', 'usertypeID', 'usertypeID');

CALL spCreateIndex('activitiescomment', 'create_date', 'create_date');

CALL spCreateIndex('activitiesmedia', 'activitiesID', 'activitiesID');

CALL spCreateIndex('activitiesmedia', 'create_date', 'create_date');

CALL spCreateIndex('activitiesstudent', 'activitiesID', 'activitiesID');

CALL spCreateIndex('activitiesstudent', 'studentID', 'studentID');

CALL spCreateIndex('activitiesstudent', 'classesID', 'classesID');

CALL spCreateIndex('addons', 'userID', 'userID');

CALL spCreateIndex('addons', 'usertypeID', 'usertypeID');

CALL spCreateIndex('addons', 'status', 'status');

CALL spCreateIndex('alert', 'itemID', 'itemID');

CALL spCreateIndex('alert', 'userID', 'userID');

CALL spCreateIndex('alert', 'usertypeID', 'usertypeID');

CALL spCreateIndex('asset', 'status', 'status');

CALL spCreateIndex('asset', 'asset_condition', 'asset_condition');

CALL spCreateIndex('asset', 'asset_categoryID', 'asset_categoryID');

CALL spCreateIndex('asset', 'asset_locationID', 'asset_locationID');

CALL spCreateIndex('asset', 'create_userID', 'create_userID');

CALL spCreateIndex('asset', 'create_usertypeID', 'create_usertypeID');

CALL spCreateIndex('asset_assignment', 'assetID', 'assetID');
 
CALL spCreateIndex('asset_assignment', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('asset_assignment', 'asset_locationID', 'asset_locationID');
 
CALL spCreateIndex('asset_category', 'category', 'category');
 
CALL spCreateIndex('asset_category', 'create_userID', 'create_userID');
 
CALL spCreateIndex('asset', 'asset_categoryID', 'asset_categoryID');
 
CALL spCreateIndex('asset', 'asset_locationID', 'asset_locationID');
 
CALL spCreateIndex('assignment', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('assignment', 'unit_id', 'unit_id');
 
CALL spCreateIndex('assignment', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('assignment', 'classesID', 'classesID');
 
CALL spCreateIndex('assignment', 'sectionID', 'sectionID');
 
CALL spCreateIndex('assignment', 'subjectID', 'subjectID');

CALL spCreateIndex('assignment', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('assignment', 'userID', 'userID');
 
CALL spCreateIndex('assignment', 'course_id', 'course_id');
 
CALL spCreateIndex('assignmentanswer', 'assignmentID', 'assignmentID');
 
CALL spCreateIndex('assignmentanswer', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('assignmentanswer', 'uploaderID', 'uploaderID');

CALL spCreateIndex('attendance', 'userID', 'userID');
 
CALL spCreateIndex('attendance', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('attendance', 'classesID', 'classesID');
 
CALL spCreateIndex('attendance', 'studentID', 'studentID');
 
CALL spCreateIndex('attendance', 'sectionID', 'sectionID');
 
CALL spCreateIndex('attendance_note', 'attendanceID', 'attendanceID');
 
CALL spCreateIndex('automation_rec', 'studentID', 'studentID');
 
CALL spCreateIndex('book_keywords', 'bookID', 'bookID');
 
CALL spCreateIndex('book_addtional_fields', 'bookID', 'bookID');
 
CALL spCreateIndex('category', 'hostelID', 'hostelID');
 
CALL spCreateIndex('childcare', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('childcare', 'classesID', 'classesID');
 
CALL spCreateIndex('childcare', 'parentID', 'parentID');
 
CALL spCreateIndex('childcare', 'userID', 'userID');
 
CALL spCreateIndex('classes', 'teacherID', 'teacherID');
 
CALL spCreateIndex('classes', 'classes_numeric', 'classes_numeric');
 
CALL spCreateIndex('classwork', 'userID', 'userID');
 
CALL spCreateIndex('classwork', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('classwork', 'classesID', 'classesID');
 
CALL spCreateIndex('classwork', 'unit_id', 'unit_id');
 
CALL spCreateIndex('classwork', 'course_id', 'course_id');
 
CALL spCreateIndex('classwork', 'sectionID', 'sectionID');
 
CALL spCreateIndex('classwork', 'subjectID', 'subjectID');
 
CALL spCreateIndex('classwork', 'is_published', 'is_published');

CALL spCreateIndex('classwork', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('classworkanswer', 'classworkID', 'classworkID');
 
CALL spCreateIndex('classworkanswer', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('classworkanswer', 'uploaderID', 'uploaderID');

CALL spCreateIndex('classworkanswer', 'answerdate', 'answerdate');

CALL spCreateIndex('classwork_answer_media', 'create_date', 'create_date');

CALL spCreateIndex('classwork_media', 'classworkID', 'classworkID');

CALL spCreateIndex('classwork_media', 'create_date', 'create_date');
 
CALL spCreateIndex('complain', 'userID', 'userID');
 
CALL spCreateIndex('complain', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('complain', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('conversation_msg', 'conversation_id', 'conversation_id');
 
CALL spCreateIndex('conversation_msg', 'user_id', 'user_id');
 
CALL spCreateIndex('conversation_user', 'user_id', 'user_id');
 
CALL spCreateIndex('coursechapterquiz_question', 'coursechapter_id', 'coursechapter_id');
 
CALL spCreateIndex('coursechapterquiz_question', 'quiz_id', 'quiz_id');
 
CALL spCreateIndex('coursechapterquiz_question', 'question_id', 'question_id');
 
CALL spCreateIndex('coursechapter_quiz', 'coursechapter_id', 'coursechapter_id');
 
CALL spCreateIndex('coursechapter_resource', 'coursechapter_id', 'coursechapter_id');
 
CALL spCreateIndex('coursechapter_resource', 'published', 'published');

CALL spCreateIndex('coursechapter_resource', 'course_id', 'course_id');
 
CALL spCreateIndex('coursefiles_resources', 'coursechapter_id', 'coursechapter_id');
 
CALL spCreateIndex('coursefiles_resources', 'published', 'published');
 
CALL spCreateIndex('coursefiles_resources', 'course_id', 'course_id');
 
CALL spCreateIndex('courselink', 'coursechapter_id', 'coursechapter_id');
 
CALL spCreateIndex('courselink', 'published', 'published');

CALL spCreateIndex('courselink', 'course_id', 'course_id');
 
CALL spCreateIndex('coursequiz_result', 'user_id', 'user_id');
 
CALL spCreateIndex('coursequiz_result', 'quiz_id', 'quiz_id');
 
CALL spCreateIndex('coursequiz_result', 'total_percentage', 'total_percentage');
 
CALL spCreateIndex('courses', 'class_id', 'class_id');
 
CALL spCreateIndex('courses', 'subject_id', 'subject_id');
 
CALL spCreateIndex('coursestudent_progress', 'student_id', 'student_id');
 
CALL spCreateIndex('coursestudent_progress', 'content_id', 'content_id');
 
CALL spCreateIndex('coursestudent_progress', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('course_unit', 'unit_id', 'unit_id');
 
CALL spCreateIndex('course_unit', 'course_id', 'course_id');
 
CALL spCreateIndex('course_unit', 'published', 'published');
 
CALL spCreateIndex('course_unit_chapter', 'unit_id', 'unit_id');
 
CALL spCreateIndex('course_unit_chapter', 'course_id', 'course_id');
 
CALL spCreateIndex('course_unit_chapter', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('course_unit_chapter', 'published', 'published');
 
CALL spCreateIndex('document', 'userID', 'userID');
 
CALL spCreateIndex('eattendance', 'examID', 'examID');
 
CALL spCreateIndex('eattendance', 'classesID', 'classesID');
 
CALL spCreateIndex('eattendance', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('eattendance', 'sectionID', 'sectionID');
 
CALL spCreateIndex('eattendance', 'subjectID', 'subjectID');
 
CALL spCreateIndex('eattendance', 'studentId', 'studentId');
 
CALL spCreateIndex('event', 'title', 'title');
 
CALL spCreateIndex('event', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('eventcounter', 'eventID', 'eventID');
 
CALL spCreateIndex('eventcounter', 'status', 'status');
 
CALL spCreateIndex('eventcounter', 'username', 'username');
 
CALL spCreateIndex('event_comment', 'eventID', 'eventID');
 
CALL spCreateIndex('event_comment', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('event_comment', 'userID', 'userID');
 
CALL spCreateIndex('event_media', 'eventID', 'eventID');

CALL spCreateIndex('event_media', 'create_date', 'create_date');
 
CALL spCreateIndex('examschedule', 'classesID', 'classesID');
 
CALL spCreateIndex('examschedule', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('examschedule', 'sectionID', 'sectionID');
 
CALL spCreateIndex('examschedule', 'subjectID', 'subjectID');
 
CALL spCreateIndex('examtermsetting', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('examtermsetting', 'finaltermexamID', 'finaltermexamID');
 
CALL spCreateIndex('examtermsettingrelation', 'examID', 'examID');
 
CALL spCreateIndex('examtermsettingrelation', 'examtermsettingID', 'examtermsettingID');
 
CALL spCreateIndex('expense', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('expense', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('globalpayment', 'classesID', 'classesID');
 
CALL spCreateIndex('globalpayment', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('globalpayment', 'sectionID', 'sectionID');
 
CALL spCreateIndex('globalpayment', 'studentID', 'studentID');
 
CALL spCreateIndex('hmember', 'hostelID', 'hostelID');
 
CALL spCreateIndex('hmember', 'categoryID', 'categoryID');
 
CALL spCreateIndex('hmember', 'studentID', 'studentID');
 
CALL spCreateIndex('holiday', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('holiday', 'title', 'title');
 
CALL spCreateIndex('holiday_comment', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('holiday_comment', 'holidayID', 'holidayID');
 
CALL spCreateIndex('holiday_comment', 'userID', 'userID');
 
CALL spCreateIndex('holiday_media', 'holidayID', 'holidayID');

CALL spCreateIndex('holiday_media', 'create_date', 'create_date');
 
CALL spCreateIndex('homework', 'userID', 'userID');
 
CALL spCreateIndex('homework', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('homework', 'classesID', 'classesID');
 
CALL spCreateIndex('homework', 'unit_id', 'unit_id');
 
CALL spCreateIndex('homework', 'sectionID', 'sectionID');
 
CALL spCreateIndex('homework', 'subjectID', 'subjectID');
 
CALL spCreateIndex('homework', 'is_published', 'is_published');
 
CALL spCreateIndex('homeworkanswer', 'homeworkID', 'homeworkID');
 
CALL spCreateIndex('homeworkanswer', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('homeworkanswer', 'uploaderID', 'uploaderID');

CALL spCreateIndex('homework_media', 'homeworkID', 'homeworkID');

CALL spCreateIndex('homework_media', 'create_date', 'create_date');
 
CALL spCreateIndex('invoice', 'feetypeID', 'feetypeID');
 
CALL spCreateIndex('invoice', 'classesID', 'classesID');
 
CALL spCreateIndex('invoice', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('invoice', 'studentID', 'studentID');
 
CALL spCreateIndex('invoice', 'userID', 'userID');
 
CALL spCreateIndex('invoice', 'maininvoiceID', 'maininvoiceID');
 
CALL spCreateIndex('invoice', 'paidstatus', 'paidstatus');
 
CALL spCreateIndex('income', 'userID', 'userID');
 
CALL spCreateIndex('income', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('issue', 'bookID', 'bookID');

CALL spCreateIndex('jobs', 'created_dt', 'created_dt');
 
CALL spCreateIndex('leaveapplications', 'leavecategoryID', 'leavecategoryID');
 
CALL spCreateIndex('leaveapplications', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('leaveassign', 'leavecategoryID', 'leavecategoryID');
 
CALL spCreateIndex('leaveassign', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('leaveassign', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('lmember', 'studentID', 'studentID');
 
CALL spCreateIndex('mark', 'examID', 'examID');
 
CALL spCreateIndex('mark', 'classesID', 'classesID');
 
CALL spCreateIndex('mark', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('mark', 'subjectID', 'subjectID');
 
CALL spCreateIndex('mark', 'studentID', 'studentID');
 
CALL spCreateIndex('mark', 'year', 'year');
 
CALL spCreateIndex('markpercentage', 'examID', 'examID');
 
CALL spCreateIndex('markpercentage', 'classesID', 'classesID');
 
CALL spCreateIndex('markpercentage', 'subjectID', 'subjectID');
 
CALL spCreateIndex('markrelation', 'markID', 'markID');
 
CALL spCreateIndex('markrelation', 'markpercentageID', 'markpercentageID');
 
CALL spCreateIndex('marksetting', 'examID', 'examID');
 
CALL spCreateIndex('marksetting', 'classesID', 'classesID');
 
CALL spCreateIndex('marksetting', 'marktypeID', 'marktypeID');
 
CALL spCreateIndex('marksetting', 'subjectID', 'subjectID');
 
CALL spCreateIndex('marksettingrelation', 'marktypeID', 'marktypeID');
 
CALL spCreateIndex('marksettingrelation', 'marksettingID', 'marksettingID');
 
CALL spCreateIndex('marksettingrelation', 'markpercentageID', 'markpercentageID');
 
CALL spCreateIndex('marksheet', 'class_id', 'class_id');
 
CALL spCreateIndex('marksheet_details', 'marksheet_id', 'marksheet_id');
 
CALL spCreateIndex('marksheet_details', 'terminal_id', 'terminal_id');
 
CALL spCreateIndex('media', 'mcategoryID', 'mcategoryID');

CALL spCreateIndex('media', 'userID', 'userID');

CALL spCreateIndex('media', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('media_category', 'userID', 'userID');

CALL spCreateIndex('media_category', 'usertypeID', 'usertypeID');

CALL spCreateIndex('media_gallery', 'media_gallery_type', 'media_gallery_type');
 
CALL spCreateIndex('media_share', 'item_id', 'item_id');

CALL spCreateIndex('mobile_push_deliveries', 'user_id', 'user_id');

CALL spCreateIndex('mobile_push_deliveries', 'user_type', 'user_type');

CALL spCreateIndex('mobile_push_deliveries', 'job_id', 'job_id');
 
CALL spCreateIndex('notes', 'usertypeID', 'usertypeID');

CALL spCreateIndex('notes', 'userID', 'userID');

CALL spCreateIndex('notes', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('notes', 'userID', 'userID');

CALL spCreateIndex('notes', 'modify_date', 'modify_date');
 
CALL spCreateIndex('notice', 'title', 'title');

CALL spCreateIndex('notice', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('notice', 'create_date', 'create_date');

CALL spCreateIndex('notice', 'create_userID', 'create_userID');

CALL spCreateIndex('notice', 'create_usertypeID', 'create_usertypeID');
 
CALL spCreateIndex('notice_comment', 'noticeID', 'noticeID');
 
CALL spCreateIndex('notice_comment', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('notice_comment', 'userID', 'userID');
 
CALL spCreateIndex('notice_media', 'noticeID', 'noticeID');

CALL spCreateIndex('notice_media', 'create_date', 'create_date');
 
CALL spCreateIndex('onlineadmission', 'classesID', 'classesID');
 
CALL spCreateIndex('onlineadmission', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('onlineadmission', 'studentID', 'studentID');

CALL spCreateIndex('onlineadmission', 'create_date', 'create_date');
 
CALL spCreateIndex('online_exam', 'classID', 'classID');
 
CALL spCreateIndex('online_exam', 'sectionID', 'sectionID');
 
CALL spCreateIndex('online_exam', 'subjectID', 'subjectID');
 
CALL spCreateIndex('online_exam', 'userTypeID', 'userTypeID');
 
CALL spCreateIndex('online_exam', 'instructionID', 'instructionID');
 
CALL spCreateIndex('online_exam_question', 'onlineExamID', 'onlineExamID');
 
CALL spCreateIndex('online_exam_question', 'questionID', 'questionID');
 
CALL spCreateIndex('online_exam_user_answer', 'onlineExamQuestionID', 'onlineExamQuestionID');
 
CALL spCreateIndex('online_exam_user_answer', 'onlineExamRegisteredUserID', 'onlineExamRegisteredUserID');
 
CALL spCreateIndex('online_exam_user_answer', 'userID', 'userID');
 
CALL spCreateIndex('online_exam_user_answer_option', 'optionID', 'optionID');
 
CALL spCreateIndex('online_exam_user_answer_option', 'typeID', 'typeID');
 
CALL spCreateIndex('online_exam_user_answer_option', 'questionID', 'questionID');
 
CALL spCreateIndex('online_exam_user_answer_option', 'user_id', 'user_id');
 
CALL spCreateIndex('online_exam_user_answer_option', 'onlineExamQuestionID', 'onlineExamQuestionID');
 
CALL spCreateIndex('online_exam_user_answer_option', 'onlineExamUserAnswerID', 'onlineExamUserAnswerID');
 
CALL spCreateIndex('online_exam_user_answer_option', 'examID', 'examID');
 
CALL spCreateIndex('online_exam_user_status', 'onlineExamID', 'onlineExamID');
 
CALL spCreateIndex('online_exam_user_status', 'userID', 'userID');
 
CALL spCreateIndex('online_exam_user_status', 'classesID', 'classesID');
 
CALL spCreateIndex('online_exam_user_status', 'sectionID', 'sectionID');
 
CALL spCreateIndex('online_exam_user_status', 'statusID', 'statusID');
 
CALL spCreateIndex('online_exam_user_status', 'onlineExamUserAnswerID', 'onlineExamUserAnswerID');

CALL spCreateIndex('parents', 'create_date', 'create_date');

CALL spCreateIndex('parents', 'modify_date', 'modify_date');

CALL spCreateIndex('parents', 'create_userID', 'create_userID');

CALL spCreateIndex('parents', 'usertypeID', 'usertypeID');
  
CALL spCreateIndex('payment', 'invoiceID', 'invoiceID');
 
CALL spCreateIndex('payment', 'studentID', 'studentID');
 
CALL spCreateIndex('payment', 'userID', 'userID');
 
CALL spCreateIndex('payment', 'globalpaymentID', 'globalpaymentID');
 
CALL spCreateIndex('push_subscriptions', 'user_id', 'user_id');

CALL spCreateIndex('posts', 'parentID', 'parentID');

CALL spCreateIndex('posts', 'publish_date', 'publish_date');

CALL spCreateIndex('posts', 'create_date', 'create_date');

CALL spCreateIndex('posts', 'modify_date', 'modify_date');

CALL spCreateIndex('posts', 'create_userID', 'create_userID');

CALL spCreateIndex('posts', 'create_usertypeID', 'create_usertypeID');
 
CALL spCreateIndex('posts_category', 'postsID', 'postsID');
 
CALL spCreateIndex('posts_category', 'posts_categoriesID', 'posts_categoriesID');
 
CALL spCreateIndex('product', 'productcategoryID', 'productcategoryID');
 
CALL spCreateIndex('productpurchase', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('productpurchase', 'productsupplierID', 'productsupplierID');
 
CALL spCreateIndex('productpurchase', 'productpurchasereferenceno', 'productpurchasereferenceno');
 
CALL spCreateIndex('productpurchase', 'productpurchasedate', 'productpurchasedate');
 
CALL spCreateIndex('productpurchase', 'productwarehouseID', 'productwarehouseID');
 
CALL spCreateIndex('productpurchase', 'productpurchasetaxID', 'productpurchasetaxID');
 
CALL spCreateIndex('productpurchase', 'productpurchasestatus', 'productpurchasestatus');
 
CALL spCreateIndex('productpurchase', 'productpurchaserefund', 'productpurchaserefund');
 
CALL spCreateIndex('productpurchasepaid', 'productpurchasepaidschoolyearID', 'productpurchasepaidschoolyearID');
 
CALL spCreateIndex('productpurchasepaid', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('productpurchasepaid', 'productpurchaseID', 'productpurchaseID');
 
CALL spCreateIndex('productsale', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('productsale', 'productsalecustomertypeID', 'productsalecustomertypeID');
 
CALL spCreateIndex('productsale', 'productsalecustomerID', 'productsalecustomerID');
 
CALL spCreateIndex('productsale', 'productsalereferenceno', 'productsalereferenceno');
 
CALL spCreateIndex('productsale', 'productsaledate', 'productsaledate');
 
CALL spCreateIndex('productsale', 'productsaletaxID', 'productsaletaxID');
 
CALL spCreateIndex('productsale', 'productsalestatus', 'productsalestatus');
 
CALL spCreateIndex('productsaleitem', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('productsaleitem', 'productsaleID', 'productsaleID');
 
CALL spCreateIndex('productsaleitem', 'productID', 'productID');
 
CALL spCreateIndex('productsalepaid', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('productsalepaid', 'productsalepaidschoolyearID', 'productsalepaidschoolyearID');
 
CALL spCreateIndex('productsalepaid', 'productsaleID', 'productsaleID');
 
CALL spCreateIndex('purchase', 'assetID', 'assetID');
 
CALL spCreateIndex('purchase', 'vendorID', 'vendorID');
 
CALL spCreateIndex('purchase', 'purchased_by', 'purchased_by');
 
CALL spCreateIndex('push_deliveries', 'user_id', 'user_id');
 
CALL spCreateIndex('push_deliveries', 'job_id', 'job_id');
 
CALL spCreateIndex('push_subscriptions', 'user_id', 'user_id');
 
CALL spCreateIndex('question_answer', 'questionID', 'questionID');
 
CALL spCreateIndex('question_answer', 'optionID', 'optionID');
 
CALL spCreateIndex('question_bank', 'levelID', 'levelID');
 
CALL spCreateIndex('question_bank', 'groupID', 'groupID');
 
CALL spCreateIndex('question_bank', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('question_bank', 'type_id', 'type_id');
 
CALL spCreateIndex('question_bank', 'typeNumber', 'typeNumber');
 
CALL spCreateIndex('routine', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('routine', 'classesID', 'classesID');
 
CALL spCreateIndex('routine', 'sectionID', 'sectionID');
 
CALL spCreateIndex('routine', 'subjectID', 'subjectID');
 
CALL spCreateIndex('routine', 'teacherID', 'teacherID');
 
CALL spCreateIndex('salary_option', 'salary_templateID', 'salary_templateID');
 
CALL spCreateIndex('salary_option', 'salary_optionID', 'salary_optionID');
 
CALL spCreateIndex('section', 'classesID', 'classesID');
 
CALL spCreateIndex('section', 'teacherID', 'teacherID');
 
CALL spCreateIndex('slider', 'pagesID', 'pagesID');
 
CALL spCreateIndex('sociallink', 'userID', 'userID');

CALL spCreateIndex('sponsor', 'create_date', 'create_date');

CALL spCreateIndex('sponsor', 'modify_date', 'modify_date');

CALL spCreateIndex('sponsor', 'create_userID', 'create_userID');

CALL spCreateIndex('sponsor', 'create_usertypeID', 'create_usertypeID');

CALL spCreateIndex('sponsor', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('sponsorship', 'create_date', 'create_date');

CALL spCreateIndex('sponsorship', 'modify_date', 'modify_date');

CALL spCreateIndex('sponsorship', 'create_userID', 'create_userID');

CALL spCreateIndex('sponsorship', 'create_usertypeID', 'create_usertypeID');

CALL spCreateIndex('sponsorship', 'schoolyearID', 'schoolyearID');

CALL spCreateIndex('sponsorship', 'sponsorID', 'sponsorID');

CALL spCreateIndex('sponsorship', 'candidateID', 'candidateID');

CALL spCreateIndex('sponsorship', 'studentID', 'studentID');

CALL spCreateIndex('student', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('student', 'classesID', 'classesID');
 
CALL spCreateIndex('student', 'sectionID', 'sectionID');
 
CALL spCreateIndex('student', 'parentID', 'parentID');
 
CALL spCreateIndex('student', 'registerNO', 'registerNO');

CALL spCreateIndex('student', 'create_date', 'create_date');
 
CALL spCreateIndex('student', 'modify_date', 'modify_date');
 
CALL spCreateIndex('student', 'create_userID', 'create_userID');
 
CALL spCreateIndex('student', 'active', 'active');
 
CALL spCreateIndex('student', 'presentdays', 'presentdays');
 
CALL spCreateIndex('studentattendancebyexam', 'classID', 'classID');
 
CALL spCreateIndex('studentattendancebyexam', 'sectionID', 'sectionID');
 
CALL spCreateIndex('studentattendancebyexam', 'examID', 'examID');
 
CALL spCreateIndex('studentattendancebyexamdetails', 'studentattendancebyexamID', 'studentattendancebyexamID');
 
CALL spCreateIndex('studentattendancebyexamdetails', 'studentID', 'studentID');
 
CALL spCreateIndex('studentextend', 'studentgroupID', 'studentgroupID');
 
CALL spCreateIndex('studentextend', 'optionalsubjectID', 'optionalsubjectID');
 
CALL spCreateIndex('studentextend', 'anotheroptionalsubjectID', 'anotheroptionalsubjectID');
 
CALL spCreateIndex('studentextend', 'studentID', 'studentID');
 
CALL spCreateIndex('studentrelation', 'srschoolyearID', 'srschoolyearID');
 
CALL spCreateIndex('studentrelation', 'srstudentID', 'srstudentID');
 
CALL spCreateIndex('studentrelation', 'srclassesID', 'srclassesID');
 
CALL spCreateIndex('studentrelation', 'srsectionID', 'srsectionID');
 
CALL spCreateIndex('studentrelation', 'srstudentgroupID', 'srstudentgroupID');
 
CALL spCreateIndex('studentrelation', 'sroptionalsubjectID', 'sroptionalsubjectID');
 
CALL spCreateIndex('studentrelation', 'sranotheroptionalsubjectID', 'sranotheroptionalsubjectID');
 
CALL spCreateIndex('studentremark', 'classID', 'classID');
 
CALL spCreateIndex('studentremark', 'examID', 'examID');
 
CALL spCreateIndex('studentremark', 'studentID', 'studentID');
 
CALL spCreateIndex('studentremark', 'sectionID', 'sectionID');
 
CALL spCreateIndex('subject', 'classesID', 'classesID');
 
CALL spCreateIndex('subject', 'coscholatics', 'coscholatics');
 
CALL spCreateIndex('subject', 'type', 'type');
 
CALL spCreateIndex('subjectteacher', 'subjectID', 'subjectID');
 
CALL spCreateIndex('subjectteacher', 'classesID', 'classesID');
 
CALL spCreateIndex('subjectteacher', 'teacherID', 'teacherID');
 
CALL spCreateIndex('subject_marks', 'subject_id', 'subject_id');
 
CALL spCreateIndex('subject_marks', 'exam_id', 'exam_id');
 
CALL spCreateIndex('subject_marks', 'class_id', 'class_id');
 
CALL spCreateIndex('subject_marks', 'no_coscholastic', 'no_coscholastic');
 
CALL spCreateIndex('subject_marks', 'order_no', 'order_no');

CALL spCreateIndex('sub_attendance', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('sub_attendance', 'studentID', 'studentID');
 
CALL spCreateIndex('sub_attendance', 'classesID', 'classesID');
 
CALL spCreateIndex('sub_attendance', 'sectionID', 'sectionID');
 
CALL spCreateIndex('sub_attendance', 'subjectID', 'subjectID');

CALL spCreateIndex('sub_attendance', 'userID', 'userID');
 
CALL spCreateIndex('sub_attendance', 'usertype', 'usertype');

CALL spCreateIndex('studentremark', 'classID', 'classID');
 
CALL spCreateIndex('studentremark', 'studentID', 'studentID');
 
CALL spCreateIndex('studentremark', 'sectionID', 'sectionID');
 
CALL spCreateIndex('studentremark', 'examID', 'examID');
 
CALL spCreateIndex('syllabus', 'userID', 'userID');
 
CALL spCreateIndex('syllabus', 'classesID', 'classesID');
 
CALL spCreateIndex('syllabus', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('tattendance', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('tattendance', 'teacherID', 'teacherID');
 
CALL spCreateIndex('tattendance', 'usertypeID', 'usertypeID');

CALL spCreateIndex('teacher', 'create_date', 'create_date');

CALL spCreateIndex('teacher', 'modify_date', 'modify_date');

CALL spCreateIndex('teacher', 'create_userID', 'create_userID');

CALL spCreateIndex('teacher', 'create_usertype', 'create_usertype');
 
CALL spCreateIndex('tmember', 'studentID', 'studentID');
 
CALL spCreateIndex('tmember', 'transportID', 'transportID');
 
CALL spCreateIndex('uattendance', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('uattendance', 'userID', 'userID');
 
CALL spCreateIndex('uattendance', 'usertypeID', 'usertypeID');
 
CALL spCreateIndex('weaverandfine', 'schoolyearID', 'schoolyearID');
 
CALL spCreateIndex('weaverandfine', 'globalpaymentID', 'globalpaymentID');
 
CALL spCreateIndex('weaverandfine', 'invoiceID', 'invoiceID');
 
CALL spCreateIndex('weaverandfine', 'paymentID', 'paymentID');
 
CALL spCreateIndex('weaverandfine', 'studentID', 'studentID');
 
CALL spCreateIndex('zzz_1_chapters', 'subject_id', 'subject_id');
 
CALL spCreateIndex('zzz_1_chapters', 'unit_id', 'unit_id');
 
CALL spCreateIndex('zzz_1_chapters', 'published', 'published');
 
CALL spCreateIndex('zzz_2_exam_settings', 'subject_id', 'subject_id');
 
CALL spCreateIndex('zzz_3_uploaded_answers', 'student_id', 'student_id');
 
CALL spCreateIndex('zzz_3_uploaded_answers', 'exam_id', 'exam_id');
 
CALL spCreateIndex('zzz_4_units', 'subject_id', 'subject_id');
 
CALL spCreateIndex('zzz_4_units', 'published', 'published');
 
CALL spCreateIndex('zzz_6_temp_answer', 'exam_id', 'exam_id');
 
CALL spCreateIndex('zzz_6_temp_answer', 'question_id', 'question_id');
 
CALL spCreateIndex('zzz_6_temp_answer', 'user_id', 'user_id');
 
CALL spCreateIndex('annual_plan', 'course_id', 'course_id');
 
CALL spCreateIndex('annual_plan', 'published', 'published');
 
CALL spCreateIndex('annual_plan_media', 'annual_plan_id', 'annual_plan_id');
 
CALL spCreateIndex('assignment_answer_media', 'assignmentanswerID', 'assignmentanswerID');

CALL spCreateIndex('assignment_media', 'assignmentID', 'assignmentID');

CALL spCreateIndex('assignment_media', 'create_date', 'create_date');
  
CALL spCreateIndex('classwork_answer_media', 'classworkanswerID', 'classworkanswerID');
 
CALL spCreateIndex('daily_plans', 'course_id', 'course_id');
 
CALL spCreateIndex('daily_plans', 'unit_id', 'unit_id');
 
CALL spCreateIndex('daily_plans', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('daily_plans', 'user_id', 'user_id');
 
CALL spCreateIndex('daily_plans', 'published', 'published');
 
CALL spCreateIndex('daily_plans', 'user_type', 'user_type');
 
CALL spCreateIndex('daily_plans', 'status', 'status');
 
CALL spCreateIndex('daily_plan_comments', 'daily_plan_id', 'daily_plan_id');
 
CALL spCreateIndex('daily_plan_comments', 'course_id', 'course_id');
 
CALL spCreateIndex('daily_plan_comments', 'user_id', 'user_id');
 
CALL spCreateIndex('daily_plan_comments', 'user_type_id', 'user_type_id');
 
CALL spCreateIndex('daily_plan_medias', 'daily_plan_id', 'daily_plan_id');
 
CALL spCreateIndex('daily_plan_medias', 'daily_plan_version_id', 'daily_plan_version_id');
  
 
CALL spCreateIndex('daily_plan_versions', 'daily_plan_id', 'daily_plan_id');
 
CALL spCreateIndex('daily_plan_versions', 'finalized_id', 'finalized_id');
 
CALL spCreateIndex('daily_plan_versions', 'user_id', 'user_id');
 
CALL spCreateIndex('daily_plan_versions', 'user_type', 'user_type');
 
CALL spCreateIndex('homework_answer_media', 'homeworkanswerID', 'homeworkanswerID');
 
CALL spCreateIndex('lesson_medias', 'lesson_plan_id', 'lesson_plan_id');
 
CALL spCreateIndex('lesson_medias', 'lesson_plan_version_id', 'lesson_plan_version_id');
 
CALL spCreateIndex('lesson_plans', 'course_id', 'course_id');
 
CALL spCreateIndex('lesson_plans', 'unit_id', 'unit_id');
 
CALL spCreateIndex('lesson_plans', 'chapter_id', 'chapter_id');
 
CALL spCreateIndex('lesson_plans', 'user_id', 'user_id');
 
CALL spCreateIndex('lesson_plans', 'published', 'published');
 
CALL spCreateIndex('lesson_plans', 'user_type', 'user_type');
 
CALL spCreateIndex('lesson_plans', 'status', 'status');
 
CALL spCreateIndex('lesson_plan_comments', 'lesson_plan_id', 'lesson_plan_id');
 
CALL spCreateIndex('lesson_plan_comments', 'course_id', 'course_id');
 
CALL spCreateIndex('lesson_plan_comments', 'user_id', 'user_id');
 
CALL spCreateIndex('lesson_plan_comments', 'user_type_id', 'user_type_id');
 
CALL spCreateIndex('lesson_plan_versions', 'lesson_plan_id', 'lesson_plan_id');
 
CALL spCreateIndex('lesson_plan_versions', 'finalized_id', 'finalized_id');
 
CALL spCreateIndex('lesson_plan_versions', 'user_id', 'user_id');
 
CALL spCreateIndex('lesson_plan_versions', 'user_type', 'user_type');
 
CALL spCreateIndex('liveclass', 'classes_id', 'classes_id');
 
CALL spCreateIndex('liveclass', 'section_id', 'section_id');
 
CALL spCreateIndex('liveclass', 'subject_id', 'subject_id');
 
CALL spCreateIndex('liveclass', 'usertype_id', 'usertype_id');
 
CALL spCreateIndex('liveclass', 'user_id', 'user_id');
 
CALL spCreateIndex('liveclass', 'creator_id', 'creator_id');
 
CALL spCreateIndex('liveclass', 'editor_id', 'editor_id');
 
CALL spCreateIndex('liveclass', 'school_year_id', 'school_year_id');
 

DELIMITER $$
-- Drop the temporary stored procedure.
DROP PROCEDURE IF EXISTS `erp`.`spCreateIndex` $$
DELIMITER ;