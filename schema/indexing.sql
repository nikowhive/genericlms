DROP INDEX IF EXISTS activitiescategoryID ON activities;
CREATE index activitiescategoryID on activities(activitiescategoryID);

DROP INDEX IF EXISTS create_date ON activities;
CREATE index create_date on activities(create_date);

DROP INDEX IF EXISTS schoolYearID ON activities;
CREATE index schoolYearID on activities(schoolYearID);

DROP INDEX IF EXISTS create_date ON activities;
CREATE index create_date on activities(create_date);

DROP INDEX IF EXISTS index_activities_comment ON activitiescomment;
CREATE index index_activities_comment on activitiescomment(activitiesID);

DROP INDEX IF EXISTS index_activities_media ON activitiesmedia;
CREATE index index_activities_media on activitiesmedia(activitiesID);

DROP INDEX IF EXISTS index_activities_student ON activitiesstudent;
CREATE index index_activities_student on activitiesstudent(activitiesID);

DROP INDEX IF EXISTS itemID ON alert;
CREATE index itemID on alert(itemID);

DROP INDEX IF EXISTS index_alert ON alert;
CREATE index index_alert on alert(userID);

DROP INDEX IF EXISTS itemname ON alert;
CREATE index itemname on alert(itemname);

DROP INDEX IF EXISTS assetID ON asset_assignment;
CREATE index assetID on asset_assignment(assetID);

DROP INDEX IF EXISTS create_userID ON asset_assignment;
CREATE index create_userID on asset_assignment(assetID);

DROP INDEX IF EXISTS usertypeID ON asset_assignment;
CREATE index usertypeID on asset_assignment(usertypeID);

DROP INDEX IF EXISTS asset_locationID ON asset_assignment;
CREATE index asset_locationID on asset_assignment(asset_locationID);

DROP INDEX IF EXISTS category ON asset_category;
CREATE index category on asset_category(category);

DROP INDEX IF EXISTS create_userID ON asset_category;
CREATE index create_userID on asset_category(create_userID);

DROP INDEX IF EXISTS asset_categoryID ON asset;
CREATE index asset_categoryID on asset(asset_categoryID);

DROP INDEX IF EXISTS asset_locationID ON asset;
CREATE index asset_locationID on asset(asset_locationID);

DROP INDEX IF EXISTS asset_categoryID ON asset;
CREATE index asset_categoryID on asset(asset_categoryID);

DROP INDEX IF EXISTS schoolyearID ON assignment;
CREATE index schoolyearID on assignment(schoolyearID);

DROP INDEX IF EXISTS unit_id ON assignment;
CREATE index unit_id on assignment(unit_id);

DROP INDEX IF EXISTS chapter_id ON assignment;
CREATE index chapter_id on assignment(chapter_id);

DROP INDEX IF EXISTS classesID ON assignment;
CREATE index classesID on assignment(classesID);

DROP INDEX IF EXISTS sectionID ON assignment;
CREATE index sectionID on assignment(sectionID);

DROP INDEX IF EXISTS subjectID ON assignment;
CREATE index subjectID on assignment(subjectID);

DROP INDEX IF EXISTS assignmentID ON assignmentanswer;
CREATE index assignmentID on assignmentanswer(assignmentID);

DROP INDEX IF EXISTS schoolyearID ON assignmentanswer;
CREATE index schoolyearID on assignmentanswer(schoolyearID);

DROP INDEX IF EXISTS uploaderID ON assignmentanswer;
CREATE index uploaderID on assignmentanswer(uploaderID);

DROP INDEX IF EXISTS userID ON attendance;
CREATE index userID on attendance(userID);

DROP INDEX IF EXISTS schoolyearID ON attendance;
CREATE index schoolyearID on attendance(schoolyearID);

DROP INDEX IF EXISTS classesID ON attendance;
CREATE index classesID on attendance(classesID);

DROP INDEX IF EXISTS studentID ON attendance;
CREATE index studentID on attendance(studentID);

DROP INDEX IF EXISTS classesID ON attendance;
CREATE index classesID on attendance(classesID);

DROP INDEX IF EXISTS sectionID ON attendance;
CREATE index sectionID on attendance(sectionID);

DROP INDEX IF EXISTS attendanceID ON attendance_note;
CREATE index attendanceID on attendance_note(attendanceID);


DROP INDEX IF EXISTS studentID ON automation_rec;
CREATE index studentID on automation_rec(studentID);

DROP INDEX IF EXISTS bookID ON book_keywords;
CREATE index bookID on book_keywords(bookID);

DROP INDEX IF EXISTS bookID ON book_addtional_fields;
CREATE index bookID on book_addtional_fields(bookID);


DROP INDEX IF EXISTS hostelID ON category;
CREATE index hostelID on category(hostelID);

DROP INDEX IF EXISTS note ON category;
CREATE index note on category(note);

DROP INDEX IF EXISTS schoolyearID ON childcare;
CREATE index schoolyearID on childcare(schoolyearID);

DROP INDEX IF EXISTS classesID ON childcare;
CREATE index classesID on childcare(classesID);

DROP INDEX IF EXISTS parentID ON childcare;
CREATE index parentID on childcare(parentID);

DROP INDEX IF EXISTS userID ON childcare;
CREATE index userID on childcare(userID);

DROP INDEX IF EXISTS teacherID ON classes;
CREATE index teacherID on classes(teacherID);

DROP INDEX IF EXISTS classes_numeric ON classes;
CREATE index classes_numeric on classes(classes_numeric);

DROP INDEX IF EXISTS userID ON classwork;
CREATE index userID on classwork(userID);


DROP INDEX IF EXISTS schoolyearID ON classwork;
CREATE index schoolyearID on classwork(schoolyearID);

DROP INDEX IF EXISTS classesID ON classwork;
CREATE index classesID on classwork(classesID);

DROP INDEX IF EXISTS unit_id ON classwork;
CREATE index unit_id on classwork(unit_id);

DROP INDEX IF EXISTS userID ON classwork;
CREATE index userID on classwork(userID);

DROP INDEX IF EXISTS sectionID ON classwork;
CREATE index sectionID on classwork(sectionID);

DROP INDEX IF EXISTS subjectID ON classwork;
CREATE index subjectID on classwork(subjectID);

DROP INDEX IF EXISTS is_published ON classwork;
CREATE index is_published on classwork(is_published);

DROP INDEX IF EXISTS classworkID ON classworkanswer;
CREATE index classworkID on classworkanswer(classworkID);


DROP INDEX IF EXISTS schoolyearID ON classworkanswer;
CREATE index schoolyearID on classworkanswer(schoolyearID);

DROP INDEX IF EXISTS uploaderID ON classworkanswer;
CREATE index uploaderID on classworkanswer(uploaderID);

DROP INDEX IF EXISTS userID ON complain;
CREATE index userID on complain(userID);

DROP INDEX IF EXISTS usertypeID ON complain;
CREATE index usertypeID on complain(usertypeID);

DROP INDEX IF EXISTS schoolyearID ON complain;
CREATE index schoolyearID on complain(schoolyearID);

DROP INDEX IF EXISTS conversation_id ON conversation_msg;
CREATE index conversation_id on conversation_msg(conversation_id);

DROP INDEX IF EXISTS user_id ON conversation_msg;
CREATE index user_id on conversation_msg(user_id);

DROP INDEX IF EXISTS user_id ON conversation_user;
CREATE index user_id on conversation_user(user_id);

DROP INDEX IF EXISTS usertypeID ON conversation_user;
CREATE index usertypeID on conversation_user(usertypeID);

DROP INDEX IF EXISTS coursechapter_id ON coursechapterquiz_question;
CREATE index coursechapter_id on coursechapterquiz_question(coursechapter_id);

DROP INDEX IF EXISTS quiz_id ON coursechapterquiz_question;
CREATE index quiz_id on coursechapterquiz_question(quiz_id);

DROP INDEX IF EXISTS question_id ON coursechapterquiz_question;
CREATE index question_id on coursechapterquiz_question(question_id);

DROP INDEX IF EXISTS coursechapter_id ON coursechapter_quiz;
CREATE index coursechapter_id on coursechapter_quiz(coursechapter_id);

DROP INDEX IF EXISTS coursechapter_id ON coursechapter_resource;
CREATE index coursechapter_id on coursechapter_resource(coursechapter_id);

DROP INDEX IF EXISTS published ON coursechapter_resource;
CREATE index published on coursechapter_resource(published);

DROP INDEX IF EXISTS coursechapter_id ON coursefiles_resources;
CREATE index coursechapter_id on coursefiles_resources(coursechapter_id);

-- DROP INDEX IF EXISTS order ON coursefiles_resources;
-- CREATE index order on coursefiles_resources(order);

DROP INDEX IF EXISTS published ON coursefiles_resources;
CREATE index published on coursefiles_resources(published);

DROP INDEX IF EXISTS coursechapter_id ON courselink;
CREATE index coursechapter_id on courselink(coursechapter_id);

DROP INDEX IF EXISTS published ON courselink;
CREATE index published on courselink(published);

-- DROP INDEX IF EXISTS order ON courselink;
-- CREATE index order on courselink(order);

DROP INDEX IF EXISTS index_coursequiz_result ON coursequiz_result;
CREATE index index_coursequiz_result on coursequiz_result(user_id);

DROP INDEX IF EXISTS quiz_id ON coursequiz_result;
CREATE index quiz_id on coursequiz_result(quiz_id);

DROP INDEX IF EXISTS total_percentage ON coursequiz_result;
CREATE index total_percentage on coursequiz_result(total_percentage);

DROP INDEX IF EXISTS index_courses ON courses;
CREATE index index_courses on courses(class_id,subject_id);

DROP INDEX IF EXISTS student_id ON coursestudent_progress;
CREATE index student_id on coursestudent_progress(student_id);

DROP INDEX IF EXISTS content_id ON coursestudent_progress;
CREATE index content_id on coursestudent_progress(content_id);

DROP INDEX IF EXISTS chapter_id ON coursestudent_progress;
CREATE index chapter_id on coursestudent_progress(chapter_id);

DROP INDEX IF EXISTS unit_id ON course_unit;
CREATE index unit_id on course_unit(unit_id);

DROP INDEX IF EXISTS course_id ON course_unit;
CREATE index course_id on course_unit(course_id);

DROP INDEX IF EXISTS published ON course_unit;
CREATE index published on course_unit(published);

DROP INDEX IF EXISTS unit_id ON course_unit_chapter;
CREATE index unit_id on course_unit_chapter(unit_id);

DROP INDEX IF EXISTS course_id ON course_unit_chapter;
CREATE index course_id on course_unit_chapter(course_id);

DROP INDEX IF EXISTS chapter_id ON course_unit_chapter;
CREATE index chapter_id on course_unit_chapter(chapter_id);

DROP INDEX IF EXISTS published ON course_unit_chapter;
CREATE index published on course_unit_chapter(published);

DROP INDEX IF EXISTS index_document ON document;
CREATE index index_document on document(userID);

DROP INDEX IF EXISTS examID ON eattendance;
CREATE index examID on eattendance(examID);

DROP INDEX IF EXISTS classesID ON eattendance;
CREATE index classesID on eattendance(classesID);

DROP INDEX IF EXISTS schoolyearID ON eattendance;
CREATE index schoolyearID on eattendance(schoolyearID);

DROP INDEX IF EXISTS sectionID ON eattendance;
CREATE index sectionID on eattendance(sectionID);

DROP INDEX IF EXISTS subjectID ON eattendance;
CREATE index subjectID on eattendance(subjectID);

DROP INDEX IF EXISTS studentID ON eattendance;
CREATE index studentID on eattendance(studentID);
 
DROP INDEX IF EXISTS title ON event;
CREATE index title on event(title);

DROP INDEX IF EXISTS schoolYearID ON event;
CREATE index schoolYearID on event(schoolYearID);

DROP INDEX IF EXISTS create_date ON event;
CREATE index create_date on event(create_date);

DROP INDEX IF EXISTS details ON event;
CREATE index details on event(details);

DROP INDEX IF EXISTS eventID ON eventcounter;
CREATE index eventID on eventcounter(eventID);

DROP INDEX IF EXISTS status ON eventcounter;
CREATE index status on eventcounter(status);

DROP INDEX IF EXISTS username ON eventcounter;
CREATE index username on eventcounter(username);

DROP INDEX IF EXISTS eventID ON event_comment;
CREATE index eventID on event_comment(eventID);

DROP INDEX IF EXISTS schoolyearID ON event_comment;
CREATE index schoolyearID on event_comment(schoolyearID);

DROP INDEX IF EXISTS userID ON event_comment;
CREATE index userID on event_comment(userID);

DROP INDEX IF EXISTS eventID ON event_media;
CREATE index eventID on event_media(eventID);

DROP INDEX IF EXISTS examID ON examschedule;
CREATE index examID on examschedule(examID);

DROP INDEX IF EXISTS classesID ON examschedule;
CREATE index classesID on examschedule(classesID);

DROP INDEX IF EXISTS schoolyearID ON examschedule;
CREATE index schoolyearID on examschedule(schoolyearID);

DROP INDEX IF EXISTS sectionID ON examschedule;
CREATE index sectionID on examschedule(sectionID);

DROP INDEX IF EXISTS subjectID ON examschedule;
CREATE index subjectID on examschedule(subjectID);

DROP INDEX IF EXISTS classesID ON examtermsetting;
CREATE index classesID on examtermsetting(classesID);

DROP INDEX IF EXISTS schoolyearID ON examtermsetting;
CREATE index schoolyearID on examtermsetting(schoolyearID);

DROP INDEX IF EXISTS finaltermexamID ON examtermsetting;
CREATE index finaltermexamID on examtermsetting(finaltermexamID);

DROP INDEX IF EXISTS examID ON examtermsettingrelation;
CREATE index examID on examtermsettingrelation(examID);

DROP INDEX IF EXISTS examtermsettingID ON examtermsettingrelation;
CREATE index examtermsettingID on examtermsettingrelation(examtermsettingID);

DROP INDEX IF EXISTS userID ON expense;
CREATE index userID on expense(userID);

DROP INDEX IF EXISTS usertypeID ON expense;
CREATE index usertypeID on expense(usertypeID);

DROP INDEX IF EXISTS schoolyearID ON expense;
CREATE index schoolyearID on expense(schoolyearID);

DROP INDEX IF EXISTS classesID ON globalpayment;
CREATE index classesID on globalpayment(classesID);

DROP INDEX IF EXISTS schoolyearID ON globalpayment;
CREATE index schoolyearID on globalpayment(schoolyearID);

DROP INDEX IF EXISTS sectionID ON globalpayment;
CREATE index sectionID on globalpayment(sectionID);

DROP INDEX IF EXISTS studentID ON globalpayment;
CREATE index studentID on globalpayment(studentID);

DROP INDEX IF EXISTS hostelID ON hmember;
CREATE index hostelID on hmember(hostelID);

DROP INDEX IF EXISTS categoryID ON hmember;
CREATE index categoryID on hmember(categoryID);

DROP INDEX IF EXISTS studentID ON hmember;
CREATE index studentID on hmember(studentID);

DROP INDEX IF EXISTS schoolyearID ON holiday;
CREATE index schoolyearID on holiday(schoolyearID);

DROP INDEX IF EXISTS title ON holiday;
CREATE index title on holiday(title);

DROP INDEX IF EXISTS details ON holiday;
CREATE index details on holiday(details);

DROP INDEX IF EXISTS create_date ON holiday;
CREATE index create_date on holiday(create_date);

DROP INDEX IF EXISTS schoolyearID ON holiday;
CREATE index schoolyearID on holiday(schoolyearID);

DROP INDEX IF EXISTS holidayID ON holiday_comment;
CREATE index holidayID on holiday_comment(holidayID);

DROP INDEX IF EXISTS schoolyearID ON holiday_comment;
CREATE index schoolyearID on holiday_comment(schoolyearID);

DROP INDEX IF EXISTS userID ON holiday_comment;
CREATE index userID on holiday_comment(userID);

DROP INDEX IF EXISTS holidayID ON holiday_media;
CREATE index holidayID on holiday_media(holidayID);

DROP INDEX IF EXISTS userID ON homework;
CREATE index userID on homework(userID);

DROP INDEX IF EXISTS schoolyearID ON homework;
CREATE index schoolyearID on homework(schoolyearID);

DROP INDEX IF EXISTS classesID ON homework;
CREATE index classesID on homework(classesID);

DROP INDEX IF EXISTS unit_id ON homework;
CREATE index unit_id on homework(unit_id);

DROP INDEX IF EXISTS userID ON homework;
CREATE index userID on homework(userID);

DROP INDEX IF EXISTS sectionID ON homework;
CREATE index sectionID on homework(sectionID);

DROP INDEX IF EXISTS subjectID ON homework;
CREATE index subjectID on homework(subjectID);

DROP INDEX IF EXISTS is_published ON homework;
CREATE index is_published on homework(is_published);

DROP INDEX IF EXISTS homeworkID ON homeworkanswer;
CREATE index homeworkID on homeworkanswer(homeworkID);

DROP INDEX IF EXISTS schoolyearID ON homeworkanswer;
CREATE index schoolyearID on homeworkanswer(schoolyearID);

DROP INDEX IF EXISTS uploaderID ON homeworkanswer;
CREATE index uploaderID on homeworkanswer(uploaderID);

DROP INDEX IF EXISTS feetypeID ON invoice;
CREATE index feetypeID on invoice(feetypeID);

DROP INDEX IF EXISTS classesID ON invoice;
CREATE index classesID on invoice(classesID);

DROP INDEX IF EXISTS schoolyearID ON invoice;
CREATE index schoolyearID on invoice(schoolyearID);

DROP INDEX IF EXISTS studentID ON invoice;
CREATE index studentID on invoice(studentID);

DROP INDEX IF EXISTS userID ON invoice;
CREATE index userID on invoice(userID);

DROP INDEX IF EXISTS maininvoiceID ON invoice;
CREATE index maininvoiceID on invoice(maininvoiceID);

DROP INDEX IF EXISTS deleted_at ON invoice;
CREATE index deleted_at on invoice(deleted_at);

DROP INDEX IF EXISTS create_date ON invoice;
CREATE index create_date on invoice(create_date);

DROP INDEX IF EXISTS paidstatus ON invoice;
CREATE index paidstatus on invoice(paidstatus);

DROP INDEX IF EXISTS index_income ON income;
CREATE index index_income on income(userID,schoolyearID);

DROP INDEX IF EXISTS index_issue ON issue;
CREATE index index_issue on issue(bookID);

DROP INDEX IF EXISTS leavecategoryID ON leaveapplications;
CREATE index leavecategoryID on leaveapplications(leavecategoryID);

DROP INDEX IF EXISTS schoolyearID ON leaveapplications;
CREATE index schoolyearID on leaveapplications(schoolyearID);

DROP INDEX IF EXISTS leavecategoryID ON leaveassign;
CREATE index leavecategoryID on leaveassign(leavecategoryID);

DROP INDEX IF EXISTS usertypeID ON leaveassign;
CREATE index usertypeID on leaveassign(usertypeID);

DROP INDEX IF EXISTS schoolyearID ON leaveassign;
CREATE index schoolyearID on leaveassign(schoolyearID);

DROP INDEX IF EXISTS studentID ON lmember;
CREATE index studentID on lmember(studentID);

DROP INDEX IF EXISTS examID ON mark;
CREATE index examID on mark(examID);

DROP INDEX IF EXISTS classesID ON mark;
CREATE index classesID on mark(classesID);

DROP INDEX IF EXISTS schoolyearID ON mark;
CREATE index schoolyearID on mark(schoolyearID);

DROP INDEX IF EXISTS subjectID ON mark;
CREATE index subjectID on mark(subjectID);

DROP INDEX IF EXISTS studentID ON mark;
CREATE index studentID on mark(studentID);

DROP INDEX IF EXISTS year ON mark;
CREATE index year on mark(year);

DROP INDEX IF EXISTS examID ON markpercentage;
CREATE index examID on markpercentage(examID);

DROP INDEX IF EXISTS classesID ON markpercentage;
CREATE index classesID on markpercentage(classesID);

DROP INDEX IF EXISTS subjectID ON markpercentage;
CREATE index subjectID on markpercentage(subjectID);

DROP INDEX IF EXISTS markID ON markrelation;
CREATE index markID on markrelation(markID);

DROP INDEX IF EXISTS markpercentageID ON markrelation;
CREATE index markpercentageID on markrelation(markpercentageID);

DROP INDEX IF EXISTS examID ON marksetting;
CREATE index examID on marksetting(examID);

DROP INDEX IF EXISTS classesID ON marksetting;
CREATE index classesID on marksetting(classesID);

DROP INDEX IF EXISTS marktypeID ON marksetting;
CREATE index marktypeID on marksetting(marktypeID);

DROP INDEX IF EXISTS subjectID ON marksetting;
CREATE index subjectID on marksetting(subjectID);

DROP INDEX IF EXISTS index_marksettingrelation ON marksettingrelation;
CREATE index index_marksettingrelation on marksettingrelation(marktypeID);

DROP INDEX IF EXISTS marksettingID ON marksettingrelation;
CREATE index marksettingID on marksettingrelation(marksettingID);

DROP INDEX IF EXISTS markpercentageID ON marksettingrelation;
CREATE index markpercentageID on marksettingrelation(markpercentageID);

DROP INDEX IF EXISTS class_id ON marksheet;
CREATE index class_id on marksheet(class_id);

DROP INDEX IF EXISTS marksheet_id ON marksheet_details;
CREATE index marksheet_id on marksheet_details(marksheet_id);

DROP INDEX IF EXISTS terminal_id ON marksheet_details;
CREATE index terminal_id on marksheet_details(terminal_id);

DROP INDEX IF EXISTS mcategoryID ON media;
CREATE index mcategoryID on media(mcategoryID);

DROP INDEX IF EXISTS userID ON media_category;
CREATE index userID on media_category(userID);

DROP INDEX IF EXISTS classesID ON media_share;
CREATE index classesID on media_share(classesID);

DROP INDEX IF EXISTS item_id ON media_share;
CREATE index item_id on media_share(item_id);

DROP INDEX IF EXISTS schoolyearID ON notes;
CREATE index schoolyearID on notes(schoolyearID);

DROP INDEX IF EXISTS userID ON notes;
CREATE index userID on notes(userID);

DROP INDEX IF EXISTS usertypeID ON notes;
CREATE index usertypeID on notes(usertypeID);

DROP INDEX IF EXISTS title ON notice;
CREATE index title on notice(title);
DROP INDEX IF EXISTS notice ON notice;
CREATE index notice on notice(notice);
DROP INDEX IF EXISTS create_date ON notice;
CREATE index create_date on notice(create_date);

DROP INDEX IF EXISTS noticeID ON notice_comment;
CREATE index noticeID on notice_comment(noticeID);

DROP INDEX IF EXISTS schoolyearID ON notice_comment;
CREATE index schoolyearID on notice_comment(schoolyearID);

DROP INDEX IF EXISTS userID ON notice_comment;
CREATE index userID on notice_comment(userID);

DROP INDEX IF EXISTS noticeID ON notice_media;
CREATE index noticeID on notice_media(noticeID);

DROP INDEX IF EXISTS classesID ON onlineadmission;
CREATE index classesID on onlineadmission(classesID);

DROP INDEX IF EXISTS schoolyearID ON onlineadmission;
CREATE index schoolyearID on onlineadmission(schoolyearID);

DROP INDEX IF EXISTS studentID ON onlineadmission;
CREATE index studentID on onlineadmission(studentID);

DROP INDEX IF EXISTS classID ON online_exam;
CREATE index classID on online_exam(classID);

DROP INDEX IF EXISTS sectionID ON online_exam;
CREATE index sectionID on online_exam(sectionID);

DROP INDEX IF EXISTS subjectID ON online_exam;
CREATE index subjectID on online_exam(subjectID);

DROP INDEX IF EXISTS userTypeID ON online_exam;
CREATE index userTypeID on online_exam(userTypeID);

DROP INDEX IF EXISTS instructionID ON online_exam;
CREATE index instructionID on online_exam(instructionID);

DROP INDEX IF EXISTS onlineExamID ON online_exam_question;
CREATE index onlineExamID on online_exam_question(onlineExamID);

DROP INDEX IF EXISTS questionID ON online_exam_question;
CREATE index questionID on online_exam_question(questionID);

DROP INDEX IF EXISTS onlineExamQuestionID ON online_exam_user_answer;
CREATE index onlineExamQuestionID on online_exam_user_answer(onlineExamQuestionID);

DROP INDEX IF EXISTS onlineExamRegisteredUserID ON online_exam_user_answer;
CREATE index onlineExamRegisteredUserID on online_exam_user_answer(onlineExamRegisteredUserID);

DROP INDEX IF EXISTS userID ON online_exam_user_answer;
CREATE index userID on online_exam_user_answer(userID);

DROP INDEX IF EXISTS optionID ON online_exam_user_answer_option;
CREATE index optionID on online_exam_user_answer_option(optionID);

DROP INDEX IF EXISTS typeID ON online_exam_user_answer_option;
CREATE index typeID on online_exam_user_answer_option(typeID);

DROP INDEX IF EXISTS questionID ON online_exam_user_answer_option;
CREATE index questionID on online_exam_user_answer_option(questionID);

DROP INDEX IF EXISTS user_id ON online_exam_user_answer_option;
CREATE index user_id on online_exam_user_answer_option(user_id);

DROP INDEX IF EXISTS onlineExamQuestionID ON online_exam_user_answer_option;
CREATE index onlineExamQuestionID on online_exam_user_answer_option(onlineExamQuestionID);

DROP INDEX IF EXISTS onlineExamUserAnswerID ON online_exam_user_answer_option;
CREATE index onlineExamUserAnswerID on online_exam_user_answer_option(onlineExamUserAnswerID);

DROP INDEX IF EXISTS examID ON online_exam_user_answer_option;
CREATE index examID on online_exam_user_answer_option(examID);

DROP INDEX IF EXISTS onlineExamID ON online_exam_user_status;
CREATE index onlineExamID on online_exam_user_status(onlineExamID);

DROP INDEX IF EXISTS userID ON online_exam_user_status;
CREATE index userID on online_exam_user_status(userID);

DROP INDEX IF EXISTS classesID ON online_exam_user_status;
CREATE index classesID on online_exam_user_status(classesID);

DROP INDEX IF EXISTS sectionID ON online_exam_user_status;
CREATE index sectionID on online_exam_user_status(sectionID);

DROP INDEX IF EXISTS statusID ON online_exam_user_status;
CREATE index statusID on online_exam_user_status(statusID);

DROP INDEX IF EXISTS onlineExamUserAnswerID ON online_exam_user_status;
CREATE index onlineExamUserAnswerID on online_exam_user_status(onlineExamUserAnswerID);

DROP INDEX IF EXISTS status ON online_exam_user_status;
CREATE index status on online_exam_user_status(status);

DROP INDEX IF EXISTS schoolyearID ON payment;
CREATE index schoolyearID on payment(schoolyearID);

DROP INDEX IF EXISTS invoiceID ON payment;
CREATE index invoiceID on payment(invoiceID);

DROP INDEX IF EXISTS studentID ON payment;
CREATE index studentID on payment(studentID);

DROP INDEX IF EXISTS userID ON payment;
CREATE index userID on payment(userID);

DROP INDEX IF EXISTS globalpaymentID ON payment;
CREATE index globalpaymentID on payment(globalpaymentID);

DROP INDEX IF EXISTS paymentdate ON payment;
CREATE index paymentdate on payment(paymentdate);

DROP INDEX IF EXISTS user_id ON push_subscriptions;
CREATE index user_id on push_subscriptions(user_id);

DROP INDEX IF EXISTS postsID ON posts_category;
CREATE index postsID on posts_category(postsID);

DROP INDEX IF EXISTS posts_categoriesID ON posts_category;
CREATE index posts_categoriesID on posts_category(posts_categoriesID);

DROP INDEX IF EXISTS productcategoryID ON product;
CREATE index productcategoryID on product(productcategoryID);

DROP INDEX IF EXISTS schoolyearID ON productpurchase;
CREATE index schoolyearID on productpurchase(schoolyearID);

DROP INDEX IF EXISTS productsupplierID ON productpurchase;
CREATE index productsupplierID on productpurchase(productsupplierID);

DROP INDEX IF EXISTS productpurchasereferenceno ON productpurchase;
CREATE index productpurchasereferenceno on productpurchase(productpurchasereferenceno);

DROP INDEX IF EXISTS productpurchasedate ON productpurchase;
CREATE index productpurchasedate on productpurchase(productpurchasedate);

DROP INDEX IF EXISTS productwarehouseID ON productpurchase;
CREATE index productwarehouseID on productpurchase(productwarehouseID);

DROP INDEX IF EXISTS productpurchasetaxID ON productpurchase;
CREATE index productpurchasetaxID on productpurchase(productpurchasetaxID);

DROP INDEX IF EXISTS productpurchasestatus ON productpurchase;
CREATE index productpurchasestatus on productpurchase(productpurchasestatus);

DROP INDEX IF EXISTS productpurchaserefund ON productpurchase;
CREATE index productpurchaserefund on productpurchase(productpurchaserefund);

DROP INDEX IF EXISTS productpurchasepaidschoolyearID ON productpurchasepaid;
CREATE index productpurchasepaidschoolyearID on productpurchasepaid(productpurchasepaidschoolyearID);

DROP INDEX IF EXISTS schoolyearID ON productpurchasepaid;
CREATE index schoolyearID on productpurchasepaid(schoolyearID);

DROP INDEX IF EXISTS productpurchaseID ON productpurchasepaid;
CREATE index productpurchaseID on productpurchasepaid(productpurchaseID);

DROP INDEX IF EXISTS schoolyearID ON productsale;
CREATE index schoolyearID on productsale(schoolyearID);

DROP INDEX IF EXISTS productsalecustomertypeID ON productsale;
CREATE index productsalecustomertypeID on productsale(productsalecustomertypeID);

DROP INDEX IF EXISTS productsalecustomerID ON productsale;
CREATE index productsalecustomerID on productsale(productsalecustomerID);

DROP INDEX IF EXISTS productsalereferenceno ON productsale;
CREATE index productsalereferenceno on productsale(productsalereferenceno);

DROP INDEX IF EXISTS productsaledate ON productsale;
CREATE index productsaledate on productsale(productsaledate);

DROP INDEX IF EXISTS productsaletaxID ON productsale;
CREATE index productsaletaxID on productsale(productsaletaxID);

DROP INDEX IF EXISTS productsalestatus ON productsale;
CREATE index productsalestatus on productsale(productsalestatus);

DROP INDEX IF EXISTS productsalerefund ON productsale;
CREATE index productsalerefund on productsale(productsalerefund);

DROP INDEX IF EXISTS schoolyearID ON productsaleitem;
CREATE index schoolyearID on productsaleitem(schoolyearID);

DROP INDEX IF EXISTS productsaleID ON productsaleitem;
CREATE index productsaleID on productsaleitem(productsaleID);

DROP INDEX IF EXISTS productID ON productsaleitem;
CREATE index productID on productsaleitem(productID);

DROP INDEX IF EXISTS schoolyearID ON productsalepaid;
CREATE index schoolyearID on productsalepaid(schoolyearID);

DROP INDEX IF EXISTS productsalepaidschoolyearID ON productsalepaid;
CREATE index productsalepaidschoolyearID on productsalepaid(productsalepaidschoolyearID);

DROP INDEX IF EXISTS productsaleID ON productsalepaid;
CREATE index productsaleID on productsalepaid(productsaleID);

DROP INDEX IF EXISTS assetID ON purchase;
CREATE index assetID on purchase(assetID);

DROP INDEX IF EXISTS vendorID ON purchase;
CREATE index vendorID on purchase(vendorID);

DROP INDEX IF EXISTS purchased_by ON purchase;
CREATE index purchased_by on purchase(purchased_by);

DROP INDEX IF EXISTS user_id ON push_deliveries;
CREATE index user_id on push_deliveries(user_id);

DROP INDEX IF EXISTS job_id ON push_deliveries;
CREATE index job_id on push_deliveries(job_id);

DROP INDEX IF EXISTS user_id ON push_subscriptions;
CREATE index user_id on push_subscriptions(user_id);

DROP INDEX IF EXISTS questionID ON question_answer;
CREATE index questionID on question_answer(questionID);

DROP INDEX IF EXISTS optionID ON question_answer;
CREATE index optionID on question_answer(optionID);

DROP INDEX IF EXISTS levelID ON question_bank;
CREATE index levelID on question_bank(levelID);

DROP INDEX IF EXISTS groupID ON question_bank;
CREATE index groupID on question_bank(groupID);

DROP INDEX IF EXISTS chapter_id ON question_bank;
CREATE index chapter_id on question_bank(chapter_id);

DROP INDEX IF EXISTS type_id ON question_bank;
CREATE index type_id on question_bank(type_id);

DROP INDEX IF EXISTS typeNumber ON question_bank;
CREATE index typeNumber on question_bank(typeNumber);

DROP INDEX IF EXISTS create_date ON question_bank;
CREATE index create_date on question_bank(create_date);

DROP INDEX IF EXISTS questionID ON question_option;
CREATE index questionID on question_option(questionID);

DROP INDEX IF EXISTS schoolyearID ON routine;
CREATE index schoolyearID on routine(schoolyearID);

DROP INDEX IF EXISTS classesID ON routine;
CREATE index classesID on routine(classesID);

DROP INDEX IF EXISTS sectionID ON routine;
CREATE index sectionID on routine(sectionID);

DROP INDEX IF EXISTS subjectID ON routine;
CREATE index subjectID on routine(subjectID);

DROP INDEX IF EXISTS teacherID ON routine;
CREATE index teacherID on routine(teacherID);

DROP INDEX IF EXISTS salary_templateID ON salary_option;
CREATE index salary_templateID on salary_option(salary_templateID);

DROP INDEX IF EXISTS salary_optionID ON salary_option;
CREATE index salary_optionID on salary_option(salary_optionID);

DROP INDEX IF EXISTS classesID ON section;
CREATE index classesID on section(classesID);

DROP INDEX IF EXISTS teacherID ON section;
CREATE index teacherID on section(teacherID);

DROP INDEX IF EXISTS index_slider ON slider;
CREATE index index_slider on slider(pagesID);

DROP INDEX IF EXISTS index_sociallink ON sociallink;
CREATE index index_sociallink on sociallink(userID);

DROP INDEX IF EXISTS schoolyearID ON student;
CREATE index schoolyearID on student(schoolyearID);

DROP INDEX IF EXISTS classesID ON student;
CREATE index classesID on student(classesID);

DROP INDEX IF EXISTS sectionID ON student;
CREATE index sectionID on student(sectionID);

DROP INDEX IF EXISTS parentID ON student;
CREATE index parentID on student(parentID);

DROP INDEX IF EXISTS name ON student;
CREATE index name on student(name);

DROP INDEX IF EXISTS registerNO ON student;
CREATE index registerNO on student(registerNO);

DROP INDEX IF EXISTS classID ON studentattendancebyexam;
CREATE index classID on studentattendancebyexam(classID);

DROP INDEX IF EXISTS sectionID ON studentattendancebyexam;
CREATE index sectionID on studentattendancebyexam(sectionID);

DROP INDEX IF EXISTS examID ON studentattendancebyexam;
CREATE index examID on studentattendancebyexam(examID);

DROP INDEX IF EXISTS studentattendancebyexamID ON studentattendancebyexamdetails;
CREATE index studentattendancebyexamID on studentattendancebyexamdetails(studentattendancebyexamID);

DROP INDEX IF EXISTS studentID ON studentattendancebyexamdetails;
CREATE index studentID on studentattendancebyexamdetails(studentID);

DROP INDEX IF EXISTS studentgroupID ON studentextend;
CREATE index studentgroupID on studentextend(studentgroupID);

DROP INDEX IF EXISTS optionalsubjectID ON studentextend;
CREATE index optionalsubjectID on studentextend(optionalsubjectID);

DROP INDEX IF EXISTS anotheroptionalsubjectID ON studentextend;
CREATE index anotheroptionalsubjectID on studentextend(anotheroptionalsubjectID);

DROP INDEX IF EXISTS studentID ON studentextend;
CREATE index studentID on studentextend(studentID);

DROP INDEX IF EXISTS srschoolyearID ON studentrelation;
CREATE index srschoolyearID on studentrelation(srschoolyearID);

DROP INDEX IF EXISTS srstudentID ON studentrelation;
CREATE index srstudentID on studentrelation(srstudentID);

DROP INDEX IF EXISTS srclassesID ON studentrelation;
CREATE index srclassesID on studentrelation(srclassesID);

DROP INDEX IF EXISTS srsectionID ON studentrelation;
CREATE index srsectionID on studentrelation(srsectionID);

DROP INDEX IF EXISTS srstudentgroupID ON studentrelation;
CREATE index srstudentgroupID on studentrelation(srstudentgroupID);

DROP INDEX IF EXISTS sroptionalsubjectID ON studentrelation;
CREATE index sroptionalsubjectID on studentrelation(sroptionalsubjectID);

DROP INDEX IF EXISTS sranotheroptionalsubjectID ON studentrelation;
CREATE index sranotheroptionalsubjectID on studentrelation(sranotheroptionalsubjectID);

DROP INDEX IF EXISTS classID ON studentremark;
CREATE index classID on studentremark(classID);

DROP INDEX IF EXISTS examID ON studentremark;
CREATE index examID on studentremark(examID);

DROP INDEX IF EXISTS studentID ON studentremark;
CREATE index studentID on studentremark(studentID);

DROP INDEX IF EXISTS sectionID ON studentremark;
CREATE index sectionID on studentremark(sectionID);

DROP INDEX IF EXISTS classesID ON subject;
CREATE index classesID on subject(classesID);

DROP INDEX IF EXISTS coscholatics ON subject;
CREATE index coscholatics on subject(coscholatics);

DROP INDEX IF EXISTS type ON subject;
CREATE index type on subject(type);

DROP INDEX IF EXISTS finalmark ON subject;
CREATE index finalmark on subject(finalmark);

DROP INDEX IF EXISTS order_no ON subject;
CREATE index order_no on subject(order_no);

DROP INDEX IF EXISTS subjectID ON subjectteacher;
CREATE index subjectID on subjectteacher(subjectID);

DROP INDEX IF EXISTS classesID ON subjectteacher;
CREATE index classesID on subjectteacher(classesID);

DROP INDEX IF EXISTS teacherID ON subjectteacher;
CREATE index teacherID on subjectteacher(teacherID);

DROP INDEX IF EXISTS subject_id ON subject_marks;
CREATE index subject_id on subject_marks(subject_id);

DROP INDEX IF EXISTS exam_id ON subject_marks;
CREATE index exam_id on subject_marks(exam_id);

DROP INDEX IF EXISTS class_id ON subject_marks;
CREATE index class_id on subject_marks(class_id);

DROP INDEX IF EXISTS no_coscholastic ON subject_marks;
CREATE index no_coscholastic on subject_marks(no_coscholastic);

DROP INDEX IF EXISTS order_no ON subject_marks;
CREATE index order_no on subject_marks(order_no);

DROP INDEX IF EXISTS classID ON studentremark;
CREATE index classID on studentremark(classID);

DROP INDEX IF EXISTS studentID ON studentremark;
CREATE index studentID on studentremark(studentID);

DROP INDEX IF EXISTS sectionID ON studentremark;
CREATE index sectionID on studentremark(sectionID);

DROP INDEX IF EXISTS examID ON studentremark;
CREATE index examID on studentremark(examID);

DROP INDEX IF EXISTS userID ON syllabus;
CREATE index userID on syllabus(userID);

DROP INDEX IF EXISTS classesID ON syllabus;
CREATE index classesID on syllabus(classesID);

DROP INDEX IF EXISTS schoolyearID ON syllabus;
CREATE index schoolyearID on syllabus(schoolyearID);

DROP INDEX IF EXISTS schoolyearID ON tattendance;
CREATE index schoolyearID on tattendance(schoolyearID);

DROP INDEX IF EXISTS teacherID ON tattendance;
CREATE index teacherID on tattendance(teacherID);

DROP INDEX IF EXISTS usertypeID ON tattendance;
CREATE index usertypeID on tattendance(usertypeID);

DROP INDEX IF EXISTS studentID ON tmember;
CREATE index studentID on tmember(studentID);

DROP INDEX IF EXISTS transportID ON tmember;
CREATE index transportID on tmember(transportID);

DROP INDEX IF EXISTS schoolyearID ON uattendance;
CREATE index schoolyearID on uattendance(schoolyearID);

DROP INDEX IF EXISTS userID ON uattendance;
CREATE index userID on uattendance(userID);

DROP INDEX IF EXISTS usertypeID ON uattendance;
CREATE index usertypeID on uattendance(usertypeID);

DROP INDEX IF EXISTS schoolyearID ON weaverandfine;
CREATE index schoolyearID on weaverandfine(schoolyearID);

DROP INDEX IF EXISTS globalpaymentID ON weaverandfine;
CREATE index globalpaymentID on weaverandfine(globalpaymentID);

DROP INDEX IF EXISTS invoiceID ON weaverandfine;
CREATE index invoiceID on weaverandfine(invoiceID);

DROP INDEX IF EXISTS paymentID ON weaverandfine;
CREATE index paymentID on weaverandfine(paymentID);

DROP INDEX IF EXISTS studentID ON weaverandfine;
CREATE index studentID on weaverandfine(studentID);

DROP INDEX IF EXISTS subject_id ON zzz_1_chapters;
CREATE index subject_id on zzz_1_chapters(subject_id);

DROP INDEX IF EXISTS unit_id ON zzz_1_chapters;
CREATE index unit_id on zzz_1_chapters(unit_id);

DROP INDEX IF EXISTS published ON zzz_1_chapters;
CREATE index published on zzz_1_chapters(published);

DROP INDEX IF EXISTS subject_id ON zzz_2_exam_settings;
CREATE index subject_id on zzz_2_exam_settings(subject_id);

DROP INDEX IF EXISTS student_id ON zzz_3_uploaded_answers;
CREATE index student_id on zzz_3_uploaded_answers(student_id);

DROP INDEX IF EXISTS exam_id ON zzz_3_uploaded_answers;
CREATE index exam_id on zzz_3_uploaded_answers(exam_id);

DROP INDEX IF EXISTS subject_id ON zzz_4_units;
CREATE index subject_id on zzz_4_units(subject_id);

DROP INDEX IF EXISTS published ON zzz_4_units;
CREATE index published on zzz_4_units(published);

DROP INDEX IF EXISTS exam_id ON zzz_6_temp_answer;
CREATE index exam_id on zzz_6_temp_answer(exam_id);

DROP INDEX IF EXISTS question_id ON zzz_6_temp_answer;
CREATE index question_id on zzz_6_temp_answer(question_id);

DROP INDEX IF EXISTS user_id ON zzz_6_temp_answer;
CREATE index user_id on zzz_6_temp_answer(user_id);




