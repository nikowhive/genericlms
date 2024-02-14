<?php

use Kreait\Firebase\JWT\Error\IdTokenVerificationFailed;
use Kreait\Firebase\JWT\IdTokenVerifier;

function escapeString($val)
{
    $ci = &get_instance();
    $driver = $ci->db->dbdriver;

    if ($driver == 'mysql') {
        $val = mysql_real_escape_string($val);
    } elseif ($driver == 'mysqli') {
        $db = get_instance()->db->conn_id;
        $val = mysqli_real_escape_string($db, $val);
    }

    return $val;
}

function btn_extra($uri, $name, $permission)
{
    if (permissionChecker($permission)) {
        return anchor(
            $uri,
            "<i class='fa fa-plus'></i>",
            "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
        );
    }

    return '';
}

function btn_add($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor(
            $uri,
            "<i class='fa fa-plus'></i>",
            "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
        );
    }

    return '';
}

function btn_add_show($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-plus'></i>",
        "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_view($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor(
            $uri,
            "<i class='fa fa-check-square-o'></i>",
            "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
        );
    }

    return '';
}

function btn_view_show($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-check-square-o'></i>",
        "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_edit($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor(
            $uri,
            "<i class='fa fa-edit'></i>",
            "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
        );
    }
    return '';
}

function btn_edit_show($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-edit'></i>",
        "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_status($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor(
            $uri,
            "<i class='fa fa-check'></i>",
            "class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
        );
    }
    return '';
}

function btn_status_show($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-check'></i>",
        "class='btn btn-info btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_not_status($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor(
            $uri,
            "<i class='fa fa-close'></i>",
            "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
        );
    }
    return '';
}

function btn_not_status_show($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-close'></i>",
        "class='btn btn-warning btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_delete($uri, $name)
{
    if (visibleButton($uri)) {
        return anchor(
            $uri,
            "<i class='fa fa-trash-o'></i>",
            [
                'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
                'class' => 'btn btn-danger btn-xs mrg',
                'data-placement' => 'top',
                'data-toggle' => 'tooltip',
                'data-original-title' => $name,
            ]
        );
    }
    return '';
}

function btn_delete_show($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-trash-o'></i>",
        [
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-danger btn-xs mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name,
        ]
    );
}

function btn_cancel($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-close'></i>",
        [
            'onclick' => "return confirm('you are about to cancel the record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-danger btn-xs mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name,
        ]
    );
}

function delete_file($uri, $id)
{
    return anchor(
        $uri,
        "<i class='fa fa-times '></i>",
        [
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'id' => $id,
            'class' => "close pull-right",
        ]
    );
}

function share_file($uri, $id)
{
    return anchor(
        $uri,
        "<i class='fa fa-globe'></i>",
        [
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'id' => $id,
            'class' => "pull-right",
        ]
    );
}

function btn_dash_view($uri, $name, $class = "btn-success")
{
    return anchor(
        $uri,
        "<span class='fa fa-check-square-o'></span>",
        "class='btn " . $class . " btn-xs mrg' style='background-color:#00bcd4;color:#fff;' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_invoice($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-credit-card'></i>",
        "class='btn btn-primary btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_return($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-mail-forward'></i>",
        [
            "onclick" => "return confirm('you are return the book . This cannot be undone. are you sure?')",
            "class" => 'btn btn-danger btn-xs mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name,

        ]
    );
}

function btn_renew($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-refresh'></i>",
        [
            "onclick" => "return confirm('you are return the book . This cannot be undone. are you sure?')",
            "class" => 'btn btn-danger btn-xs mrg',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name,

        ]
    );
}

function btn_attendance($id, $method, $class, $name)
{
    return "<input type='checkbox' class='" . $class . "' $method id='" . $id . "' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "' >  ";
}

function btn_attendance_radio($id, $method, $class, $name, $title, $value)
{
    return "<input type='radio' class='" . $class . "' $method id='" . $id . "' value='" . $value . "' name='" . $name . "'>  " . "<label style='vertical-align:  middle;display: inline;' for='" . $id . "'>" . $title . "</label> ";
}

function btn_promotion($id, $class, $name)
{
    return "<input type='checkbox' class='" . $class . "' id='" . $id . "' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "' >  ";
}

if (!function_exists('dump')) {
    function dump($var, $label = 'Dump', $echo = true)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        if ($echo == true) {
            echo $output;
        } else {
            return $output;
        }
    }
}

if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = true)
    {
        dump($var, $label, $echo);
        exit;
    }
}

if (!function_exists('dd')) {
    function dd($var = "", $label = 'Dump', $echo = true)
    {
        dump($var, $label, $echo);
        exit;
    }
}

// infinite coding starts here..
function btn_add_pdf($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-file'></i> " . $name,
        "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button' target='_blank'"
    );
}

function btn_sm_edit($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-edit'></i> " . $name,
        "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button'"
    );
}

function btn_sm_delete($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-trash-o'></i> " . $name,
        [
            'onclick' => "return confirm('you are about to delete a record. This cannot be undone. are you sure?')",
            'class' => 'btn btn-maroon btn-sm mrg bg-maroon-light',
            'data-placement' => 'top',
            'data-toggle' => 'tooltip',
            'data-original-title' => $name,
        ]
    );
}

function btn_sm_add($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-plus'></i> " . $name,
        "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button'"
    );
}

function btn_sm_accept_and_denied_leave($uri, $name, $icon)
{
    return anchor(
        $uri,
        "<i class='fa fa-" . $icon . "'></i> " . $name,
        "class='btn-cs btn-sm-cs' style='text-decoration: none;' role='button'"
    );
}

function btn_sm_global($uri, $name, $icon, $color = null)
{
    if (!$color) {
        $color = "btn-primary";
    }
    return anchor(
        $uri,
        "<i class='" . $icon . "'></i>",
        "class='btn " . $color . " btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_sm_global_new_tab($uri, $name, $icon, $color = null)
{
    if (!$color) {
        $color = "btn-primary";
    }
    return anchor(
        $uri,
        "<i class='" . $icon . "'></i>",
        "class='btn " . $color . " btn-xs mrg' data-placement='top' data-toggle='tooltip' target='_blank' data-original-title='" . $name . "'"
    );
}

function btn_md_global($uri, $name, $icon, $class = null)
{
    if (!$class) {
        $class = "btn-primary";
    }
    return anchor(
        $uri,
        $icon,
        "class='" . $class . "' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_payment($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-credit-card'></i> " . $name,
        "class='btn-cs btn-sm-cs'style='text-decoration: none;' role='button'"
    );
}

// infinite coding end here..

function permissionChecker($data)
{
    $CI = &get_instance();
    $sessionPermission = $CI->session->userdata('master_permission_set');
    if (isset($sessionPermission[$data]) && $sessionPermission[$data] == 'yes') {
        return true;
    }
    return false;
}

function visibleButton($uri)
{
    $explodeUri = explode('/', $uri);
    $permission = $explodeUri[0] . '_' . $explodeUri[1];
    if (permissionChecker($permission)) {
        return true;
    }
    return false;
}

function actionChecker($arrays)
{
    if ($arrays) {
        foreach ($arrays as $key => $array) {
            if (permissionChecker($array)) {
                return true;
            }
        }
    }
}

function pluck($array, $value, $key = null)
{
    $returnArray = [];
    if (customCompute($array)) {
        foreach ($array as $item) {
            if ($key != null) {
                $returnArray[$item->$key] = strtolower($value) == 'obj' ? $item : $item->$value;
            } else {
                if ($value == 'obj') {
                    $returnArray[] = $item;
                } else {
                    $returnArray[] = $item->$value;
                }
            }
        }
    }
    return $returnArray;
}

function pluck_bind($array, $value, $concatFirst, $concatLast, $key = null)
{
    $returnArray = [];
    if (customCompute($array)) {
        foreach ($array as $item) {
            if ($key != null) {
                $returnArray[$item->$key] = $concatFirst . $item->$value . $concatLast;
            } else {
                if ($value != null) {
                    $returnArray[] = $concatFirst . $item->$value . $concatLast;
                } else {
                    $returnArray[] = $concatFirst . $item . $concatLast;
                }
            }
        }
    }

    return $returnArray;
}

function pluck_multi_array($arrays, $val, $key = null)
{
    $retArray = [];
    if (customCompute($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if (!empty($key)) {
                if (strtolower($val) == 'obj') {
                    $retArray[$array->$key][] = $array;
                } else {
                    $retArray[$array->$key][] = $array->$val;
                }
            } else {
                if (strtolower($val) == 'obj') {
                    $retArray[$i][] = $array;
                } else {
                    $retArray[$i][] = $array->$val;
                }
                $i++;
            }
        }
    }
    return $retArray;
}

function pluck_multi_array_key($arrays, $val, $fstKey = null, $sndKey = null)
{
    $retArray = [];
    if (customCompute($arrays)) {
        $i = 0;
        foreach ($arrays as $array) {
            if (!empty($fstKey)) {
                if (strtolower($val) == 'obj') {
                    if (!empty($sndKey)) {
                        $retArray[$array->$fstKey][$array->$sndKey] = $array;
                    } else {
                        $retArray[$array->$fstKey][] = $array;
                    }
                } else {
                    if (!empty($sndKey)) {
                        $retArray[$array->$fstKey][$array->$sndKey] = $array->$val;
                    } else {
                        $retArray[$array->$fstKey][] = $array->$val;
                    }
                }
            } else {
                if (strtolower($val) == 'obj') {
                    if (!empty($sndKey)) {
                        $retArray[$i][$array->$sndKey] = $array;
                    } else {
                        $retArray[$i][] = $array;
                    }
                } else {
                    if (!empty($sndKey)) {
                        $retArray[$i][$array->$sndKey] = $array->$val;
                    } else {
                        $retArray[$i][] = $array->$val;
                    }
                }
                $i++;
            }
        }
    }
    return $retArray;
}

function funtopbarschoolyear($siteinfos, $topbarschoolyears)
{
    $CI = &get_instance();
    echo '<li class="dropdown messages-menu">';
    echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
    echo '<i class="fa fa-calendar-plus-o"></i>';
    if (customCompute($topbarschoolyears)) {
        echo "<span class='label label-success'>";
        echo "<lable class='alert-image'>" . customCompute($topbarschoolyears) . "</lable>";
        echo "</span>";
    }
    echo '</a>';
    echo '<ul class="dropdown-menu">';
    if (customCompute($topbarschoolyears)) {
        echo '<li class="header">';
        if (customCompute($topbarschoolyears) > 1) {
            echo $CI->lang->line("la_fs") . " " . customCompute($topbarschoolyears) . " " . $CI->lang->line("ya_yer_two");
        } else {
            echo $CI->lang->line("la_fs") . " " . customCompute($topbarschoolyears) . " " . $CI->lang->line("ya_yer_one");
        }
        echo '</li>';
        echo '<li>';
        echo '<ul class="menu">';
        foreach ($topbarschoolyears as $key => $topbarschoolyear) {
            echo '<li>';
            echo '<a href="' . base_url("schoolyear/toggleschoolyear/$topbarschoolyear->schoolyearID") . '">';
            echo '<h4>';
            echo $topbarschoolyear->schoolyear;
            if ($siteinfos->school_year == $topbarschoolyear->schoolyearID) {
                echo ' - (' . $CI->lang->line('default') . ')';
            }

            if ($CI->session->userdata('defaultschoolyearID') == $topbarschoolyear->schoolyearID) {
                echo " <i class='glyphicon glyphicon-ok'></i>";
            }
            echo '</h4>';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</li>';
    }
    echo '</ul>';
    echo '</li>';
}

function getNameByUsertypeIDAndUserID($usertypeID, $userID)
{ /* DD OK */
    $CI = &get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('student_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');

    $findUserName = '';
    if ($usertypeID == 1) {
        $user = $CI->db->get_where(
            'systemadmin',
            ["usertypeID" => $usertypeID, 'systemadminID' => $userID]
        );
        $alluserdata = $user->row();
        if (customCompute($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } elseif ($usertypeID == 2) {
        $user = $CI->db->get_where('teacher', ["usertypeID" => $usertypeID, 'teacherID' => $userID]);
        $alluserdata = $user->row();
        if (customCompute($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } elseif ($usertypeID == 3) {
        $user = $CI->db->get_where('student', ["usertypeID" => $usertypeID, 'studentID' => $userID]);
        $alluserdata = $user->row();
        if (customCompute($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } elseif ($usertypeID == 4) {
        $user = $CI->db->get_where('parents', ["usertypeID" => $usertypeID, 'parentsID' => $userID]);
        $alluserdata = $user->row();
        if (customCompute($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    } else {
        $user = $CI->db->get_where('user', ["usertypeID" => $usertypeID, 'userID' => $userID]);
        $alluserdata = $user->row();
        if (customCompute($alluserdata)) {
            $findUserName = $alluserdata->name;
        }
        return $findUserName;
    }
    return $findUserName;
}

function getObjectByUserTypeIDAndUserID($usertypeID, $userID, $schoolyearID = null)
{ /* DD OK */
    $CI = &get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('student_m');
    $CI->load->model('studentrelation_m');
    $CI->load->model('classes_m');
    $CI->load->model('section_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');
    $CI->load->model('usertype_m');
    $user = [];
    $usertype = $CI->db->get_where('usertype', ["usertypeID" => $usertypeID]);
    if ($usertypeID == 1) {
        $user = $CI->systemadmin_m->get_single_systemadmin([
            "usertypeID" => $usertypeID,
            'systemadminID' => $userID,
        ]);
    } elseif ($usertypeID == 2) {
        $user = $CI->teacher_m->general_get_single_teacher(["usertypeID" => $usertypeID, 'teacherID' => $userID]);
    } elseif ($usertypeID == 3) {
        if ($schoolyearID) {
            $user = $CI->studentrelation_m->get_studentrelation_join_student([
                'srstudentID' => $userID,
                'srschoolyearID' => $schoolyearID,
            ], true);
            if (customCompute($user)) {
                $class = $CI->classes_m->general_get_single_classes(["classesID" => $user->srclassesID]);
                if (customCompute($class)) {
                    $user->classes = $class->classes;
                }

                $section = $CI->section_m->general_get_single_section(["sectionID" => $user->srsectionID]);
                if (customCompute($section)) {
                    $user->section = $section->section;
                }
            }
        } else {
            $user = $CI->student_m->general_get_single_student([
                "usertypeID" => $usertypeID,
                'studentID' => $userID,
            ]);
            if (customCompute($user)) {
                $class = $CI->classes_m->get_single_classes(["classesID" => $user->classesID]);
                if (customCompute($class)) {
                    $user->classes = $class->classes;
                }

                $section = $CI->section_m->get_single_section(["sectionID" => $user->sectionID]);
                if (customCompute($section)) {
                    $user->section = $section->section;
                }
            }
        }
    } elseif ($usertypeID == 4) {
        $user = $CI->parents_m->get_single_parents(["usertypeID" => $usertypeID, 'parentsID' => $userID]);
    } else {
        $user = $CI->user_m->get_single_user(["usertypeID" => $usertypeID, 'userID' => $userID]);
    }

    if (customCompute($usertype) && customCompute($user)) {
        $user->usertype = $usertype->row()->usertype;
    }
    return $user;
}

function getAllUserObjectWithStudentRelation($arrays, $studentJoin = false, $studentExtendJoin = false)
{ /* DD OK */
    $CI = &get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('student_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');
    $CI->load->model('studentrelation_m');
    $returnArray = [];

    if (!is_array($arrays)) {
        if (is_int($arrays)) {
            $intSchoolYearID = $arrays;
            $arrays = [];
            $arrays['srschoolyearID'] = $intSchoolYearID;
        } elseif (!empty($arrays) && is_numeric($arrays)) {
            $intSchoolYearID = $arrays;
            $arrays = [];
            $arrays['srschoolyearID'] = $intSchoolYearID;
        } else {
            throw new Exception("School YearID is required");
        }
    } else {
        if (!isset($arrays['srschoolyearID']) && !isset($arrays['schoolyearID'])) {
            throw new Exception("School YearID is required");
        } elseif (isset($arrays['schoolyearID'])) {
            $arrays['srschoolyearID'] = $arrays['schoolyearID'];
            unset($arrays['schoolyearID']);
        }
    }

    $systemadmin = $CI->systemadmin_m->get_systemadmin();
    if (customCompute($systemadmin)) {
        $returnArray[1] = pluck($systemadmin, 'obj', 'systemadminID');
    }

    $teacher = $CI->teacher_m->get_teacher();
    if (customCompute($teacher)) {
        $returnArray[2] = pluck($teacher, 'obj', 'teacherID');
    }

    $student = [];
    if ($studentJoin && $studentExtendJoin) {
        $student = $CI->studentrelation_m->general_get_order_by_student($arrays, $studentExtendJoin);
    } elseif ($studentJoin) {
        $student = $CI->studentrelation_m->general_get_order_by_student($arrays);
    } elseif ($studentExtendJoin) {
        $student = $CI->studentrelation_m->general_get_order_by_student($arrays, $studentExtendJoin);
    } elseif (customCompute($arrays)) {
        $student = $CI->studentrelation_m->general_get_order_by_student($arrays);
    } else {
        $student = $CI->studentrelation_m->get_studentrelation();
    }

    if (customCompute($student)) {
        $returnArray[3] = pluck($student, 'obj', 'srstudentID');
    }

    $parent = $CI->parents_m->get_parents();
    if (customCompute($parent)) {
        $returnArray[4] = pluck($parent, 'obj', 'parentsID');
    }

    $users = $CI->user_m->get_user();
    if (customCompute($users)) {
        foreach ($users as $user) {
            $returnArray[$user->usertypeID][$user->userID] = $user;
        }
    }

    return $returnArray;
}

function getAllUserObjectWithoutStudent()
{ /* DD OK */
    $CI = &get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');
    $CI->load->model('studentrelation_m');
    $returnArray = [];

    $systemadmin = $CI->systemadmin_m->get_systemadmin();
    if (customCompute($systemadmin)) {
        $returnArray[1] = pluck($systemadmin, 'obj', 'systemadminID');
    }

    $teacher = $CI->teacher_m->general_get_teacher();
    if (customCompute($teacher)) {
        $returnArray[2] = pluck($teacher, 'obj', 'teacherID');
    }

    $users = $CI->user_m->get_user();
    if (customCompute($users)) {
        foreach ($users as $user) {
            $returnArray[$user->usertypeID][$user->userID] = $user;
        }
    }

    return $returnArray;
}

function getAllSelectUser($schoolYearID = null)
{ /* DD OK */
    $CI = &get_instance();
    $CI->load->model('systemadmin_m');
    $CI->load->model('teacher_m');
    $CI->load->model('parents_m');
    $CI->load->model('user_m');
    $CI->load->model('student_m');
    $returnArray = [];

    $systemadmin = $CI->systemadmin_m->get_select_systemadmin();
    if (customCompute($systemadmin)) {
        $returnArray[1] = pluck($systemadmin, 'obj', 'systemadminID');
    }

    $teacher = $CI->teacher_m->get_select_teacher();
    if (customCompute($teacher)) {
        $returnArray[2] = pluck($teacher, 'obj', 'teacherID');
    }

    if ($schoolYearID == null) {
        $student = $CI->student_m->get_select_student();
    } else {
        $student = $CI->student_m->get_select_student(null, ['schoolyearID' => $schoolYearID]);
    }

    if (customCompute($student)) {
        $returnArray[3] = pluck($student, 'obj', 'studentID');
    }

    $parent = $CI->parents_m->get_select_parents();
    if (customCompute($parent)) {
        $returnArray[4] = pluck($parent, 'obj', 'parentsID');
    }

    $users = $CI->user_m->get_select_user();
    if (customCompute($users)) {
        foreach ($users as $user) {
            $returnArray[$user->usertypeID][$user->userID] = $user;
        }
    }

    return $returnArray;
}

function userInfo($userTypeID, $userID, $field = 'name, photo')
{
    $CI = &get_instance();
    if ($userTypeID == 1) {
        $table = "systemadmin";
    } elseif ($userTypeID == 2) {
        $table = "teacher";
    } elseif ($userTypeID == 3) {
        $table = 'student';
    } elseif ($userTypeID == 4) {
        $table = 'parents';
    } else {
        $table = 'user';
    }
    $CI->db->select($field);
    $CI->db->from($table);
    $CI->db->where([$table . 'ID' => $userID]);
    $query = $CI->db->get();
    return $query->row();
}

function btn_download($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-download'></i>",
        "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function btn_download_file($uri, $name, $lang)
{
    return anchor(
        $uri,
        $name,
        "class='btn btn-success btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $lang . "'"
    );
}

function btn_download_link($uri, $name)
{
    return anchor($uri, $name, "style='text-decoration:underline;color:#00c0ef'");
}

function btn_upload($uri, $name)
{
    return anchor(
        $uri,
        "<i class='fa fa-upload'></i>",
        "class='btn bg-maroon-light btn-xs mrg' data-placement='top' data-toggle='tooltip' data-original-title='" . $name . "'"
    );
}

function display_menu($nodes, &$menu, $showMoreMenuBtn)
{
    $subUrl = ['/add', '/edit', '/view', '/index'];

    $CI = &get_instance();

    $counter = 0;
    foreach ($nodes as $key => $node) {

        $leftIcon = '<i class="fa fa-angle-left pull-right"></i>';

        $f = 0;
        if (isset($node['child'])) {
            $f = 1;
        }

        if (permissionChecker($node['link']) || ($node['link'] == '#' && $f)) {
            if ($f && customCompute($node['child']) == 1) {
                $f = 0;
                $node = current($node['child']);
            }
            $treeView = 'treeview ';
            $active = '';

            $current_url = current_url();

            if (strpos($current_url, 'courses') || strpos($current_url, 'homework') || strpos($current_url, 'classwork') || strpos($current_url, 'assignment')) {
                $u = true;
                $subUrl = ['/add', '/edit', '/view', '/index', '/show', '/contents', '/assignment', '/classwork', '/homework', '/link', '/attachments', '/quizzes', '/student_view'];
            }

            foreach ($subUrl as $value) {
                $newUrl = substr($current_url, 0, strpos($current_url, $value));
                if ($newUrl != "") {
                    $current_url = $newUrl;
                }
            }

            if (base_url($node['link']) == $current_url) {
                $active = 'active';
            }
            $hidden = $counter > 6 ? ' hidden-menu-item' : '';
            if ($showMoreMenuBtn) {
                $menu .= '<li class="' . ($f ? $treeView : '') . $active . $hidden . '">';
            } else {
                $menu .= '<li class="' . ($f ? $treeView : '') . $active . '">';
            }
            if ($showMoreMenuBtn) {
                $menu .= anchor(
                    $node['link'],
                    '<i class="fa ' . ($node['icon'] != null ? $node['icon'] : 'fa-home') . '"></i><span>' . ($CI->lang->line('menu_' . $node['menuName']) != null ? strtoupper($CI->lang->line('menu_' . $node['menuName'])) : strtoupper($node['menuName'])) . '</span> ' . ($f ? $leftIcon : ''),
                    ' data-container="body"  data-placement="right" data-toggle="tooltip" data-original-title=' . ($CI->lang->line('menu_' . $node['menuName']) != null ? strtoupper($CI->lang->line('menu_' . $node['menuName'])) : strtoupper($node['menuName']))
                );
            } else {
                $menu .= anchor(
                    $node['link'],
                    '<i class="fa ' . ($node['icon'] != null ? $node['icon'] : 'fa-home') . '"></i><span>' . ($CI->lang->line('menu_' . $node['menuName']) != null ? $CI->lang->line('menu_' . $node['menuName']) : $node['menuName']) . '</span> ' . ($f ? $leftIcon : ''),
                    ' data-container="body"  data-placement="right" data-original-title="' . ($CI->lang->line('menu_' . $node['menuName']) != null ? strtoupper($CI->lang->line('menu_' . $node['menuName']))  : strtoupper($node['menuName'])) . '" data-toggle="tooltip".'
                );
            }
            if ($f) {
                $menu .= '<ul class="treeview-menu"><li class="toggleDiv"><a href="javascript:;"><i class="fa fa-bars"></i><span>' . ($CI->lang->line('menu_' . $node['menuName']) != null ? strtoupper($CI->lang->line('menu_' . $node['menuName'])) : strtoupper($node['menuName'])) . '</span></a></li>';
                display_menu($node['child'], $menu, false);
                $menu .= "</ul>";
            }
            $menu .= "</li>";
            $counter++;
        }
    }

    if ($showMoreMenuBtn && $counter > 6) {

        $menu .= '<li class="show-less-menu"><a title="SHOW LESS" onclick="showHiddenMenus()"><i class="fa fa-th"></i><span>SHOW LESS</span> </a></li>';
        $menu .= '<li class="show-more-menu"><a title="SHOW MORE" onclick="showHiddenMenus()"><i class="fa fa-th"></i><span>SHOW MORE</span> </a></li>';
    }
}

function namesorting($string, $len = 14)
{
    $return = $string;
    if (isset($string) && $len) {
        if (strlen($string) > $len) {
            $return = substr($string, 0, $len - 2) . '..';
        } else {
            $return = $string;
        }
    }

    return $return;
}

function frontendColorStyle($string)
{
    $setStr = '';
    $bgColor = ['#86bc42', '#222222', '#D9504E', '#1A4027', '#6F008D', '#E09622'];
    if (!empty($string)) {
        $exps = explode(' ', $string);
        foreach ($exps as $expKey => $exp) {
            $setStr .= "<span style='color:" . $bgColor[$expKey] . "'> " . $exp . "</span>";
        }
    }
    return $setStr;
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function spClean($string)
{
    $string = strtolower($string);
    $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function pageStatus($data, $flag = true)
{
    if ($flag) {
        $array = [
            'published' => 1,
            'draft' => 2,
            'trash' => 3,
            'review' => 4,
        ];

        if (isset($array[$data])) {
            return $array[$data];
        } else {
            return 1;
        }
    }

    if ($flag == false) {
        $array = [
            1 => 'published',
            2 => 'draft',
            3 => 'trash',
            4 => 'review',
        ];

        if (isset($array[$data])) {
            return $array[$data];
        } else {
            return 'publish';
        }
    }
}

function pageVisibility($visibility, $flag = true, $send = 1)
{
    $CI = &get_instance();
    $language = $CI->session->userdata('lang');
    $CI->lang->load('pages', $language);

    if ($flag) {
        $status = false;
        if ($visibility == 1 && $send == 1) {
            $status = true;
        } elseif ($visibility == 2 && $send == 2) {
            $status = true;
        } elseif ($visibility == 3 && $send == 3) {
            $status = true;
        }
        return $status;
    }

    if ($flag == false) {
        if ($visibility == 1) {
            echo $CI->lang->line('pages_public');
        } elseif ($visibility == 2) {
            echo $CI->lang->line('pages_password_protected');
        } elseif ($visibility == 3) {
            echo $CI->lang->line('pages_private');
        }
    }
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function sentenceMap($string, $numberOFWord, $startTag, $closeTag)
{
    $exp = explode(' ', $string);
    $len = 0;
    $expEnd = end($exp);
    $f = true;
    $stringWarp = '';
    foreach ($exp as $key => $sn) {
        $len += strlen($sn);
        $len++;

        if ($len >= $numberOFWord) {
            if ($f) {
                $stringWarp .= $startTag;
                $f = false;
            }
        }

        $stringWarp .= $sn . ' ';

        if ($sn == $expEnd) {
            if ($f == false) {
                $stringWarp .= $closeTag;
            }
            return $stringWarp;
        }
    }
}

function xssRemove($data)
{
    $string = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $data);
    return $string;
}

function addOrdinalNumberSuffix($num)
{
    if (!in_array(($num % 100), [11, 12, 13])) {
        switch ($num % 10) {
            case 1:
                return $num . 'st';
            case 2:
                return $num . 'nd';
            case 3:
                return $num . 'rd';
        }
    }
    return $num . 'th';
}

function btn_printReport($permission, $name, $DivID = 'printablediv')
{
    if (permissionChecker($permission)) {
        return '<button class="btn btn-default" onclick="javascript:printDiv' . "('" . $DivID . "')" . '"><span class="fa fa-print"></span> ' . $name . '</button>';
    }
    return '';
}

function btn_sentToMailReport($permission, $name)
{
    if (permissionChecker($permission)) {
        return '<button class="btn btn-default" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> ' . $name . '</button>';
    }
    return '';
}

function btn_pdfPreviewReport($permission, $uri, $name)
{
    if (permissionChecker($permission)) {
        return anchor(
            $uri,
            "<i class='fa fa-file'></i> " . $name,
            'class="btn btn-default pdfurl" target="_blank"'
        );
    }
    return '';
}

function btn_xmlReport($permission, $uri, $name)
{
    if (permissionChecker($permission)) {
        return anchor(
            $uri,
            "<i class='fa fa-file'></i> " . $name,
            'class="btn btn-default xmlurl" target="_blank"'
        );
    }
    return '';
}

function btn_flat_printReport($permission, $name, $DivID = 'printablediv')
{
    if (permissionChecker($permission)) {
        return '<button style="margin: 0 3px;" class="btn btn-default" onclick="javascript:printDiv' . "('" . $DivID . "')" . '"><span class="fa fa-print"></span> ' . $name . '</button>';
    }
    return '';
}

function btn_flat_xmlReport($permission, $name)
{
    if (permissionChecker($permission)) {
        return '<button style="margin: 0 3px;" class="btn btn-default"><span class="fa fa-file-excel-o"></span> ' . $name . '</button>';
    }
    return '';
}

function btn_flat_sentToMailReport($permission, $name)
{
    if (permissionChecker($permission)) {
        return '<button style="margin: 0 3px;" class="btn btn-default"><span class="fa fa-envelope-o"></span> ' . $name . '</button>';
    }
    return '';
}

function btn_flat_pdfPreviewReport($permission, $uri, $name)
{
    if (permissionChecker($permission)) {
        return anchor(
            $uri,
            "<i class='fa fa-file'></i> " . $name,
            ' style="margin: 0 3px;" class="btn btn-default" target="_blank"'
        );
    }
    return '';
}

function callDesignCss()
{
    $file = file_get_contents('http://localhost/school4/assets/bootstrap/bootstrap.min.css');
    $file2 = file_get_contents('http://localhost/school4/assets/inilabs/themes/default/style.css');
    $file3 = file_get_contents('http://localhost/school4/assets/inilabs/themes/default/inilabs.css');
    $file4 = file_get_contents('http://localhost/school4/assets/inilabs/combined.css');
    echo '<style type="text/css">' . $file . $file2 . $file3 . $file4 . '</style>';
}

function get_month_and_year_using_two_date($startdate, $enddate)
{
    $start = new DateTime($startdate);
    $start->modify('first day of this month');
    $end = new DateTime($enddate);
    $end->modify('first day of next month');
    $interval = DateInterval::createFromDateString('1 month');
    $period = new DatePeriod($start, $interval, $end);

    $monthAndYear = [];
    if (customCompute($period)) {
        foreach ($period as $dt) {
            $monthAndYear[$dt->format("Y")][] = $dt->format("m");
        }
    }
    return $monthAndYear;
}

function generate_qrcode($text = "Hi", $filename = "default", $folder = "idQRcode")
{
    $CI = &get_instance();
    $CI->load->library('qrcodegenerator');
    $CI->qrcodegenerator->generate_qrcode($text, $filename, $folder);
}

function lzero($num)
{
    $numPadded = sprintf("%02d", $num);
    return $numPadded;
}

function reportheader($setting, $schoolyear, $pdf = false)
{
    $data = '';
    $CI = &get_instance();
    if (customCompute($setting) && customCompute($schoolyear)) {
        $data .= '<div class="reportPage-header">';
        if ($pdf) {
            $data .= '<span class="header"><img class="logo" src="' . base_url('uploads/images/' . $setting->photo) . '"></span>';
        } else {
            $data .= '<span class="header" id="headerImage"><p class="bannerLogo"><img src="' . base_url('uploads/images/' . $setting->photo) . '"></p></span>';
        }
        $data .= '<p class="title">' . $setting->sname . '</p>';
        $data .= '<p class="title-desc">' . $setting->address . '</p>';
        $data .= '<p class="title-desc">' . $CI->lang->line('topbar_academic_year') . ' : ' . $schoolyear->schoolyear . '</p>';
        $data .= '</div>';
    }
    return $data;
}

function reportfooter($setting, $schoolyear, $pdf = false)
{
    $data = '';
    $CI = &get_instance();
    if (customCompute($setting) && customCompute($schoolyear)) {
        $data .= '<div class="footer">';
        $data .= '<img class="flogo" style="width:30px" src="' . base_url("uploads/images/$setting->photo") . '">';
        $data .= '<p class="copyright">' . $setting->footer . ' | ' . $CI->lang->line('topbar_hotline') . ' : ' . $setting->phone . '</p>';
        $data .= '</div>';
    }
    return $data;
}

function featureheader($siteinfos)
{
    $CI = &get_instance(); ?>
    <div class="headerArea">
        <div class="siteLogo">
            <img class="siteLogoimg" src="<?= base_url('uploads/images/' . $siteinfos->photo) ?>" alt="">
        </div>
        <div class="siteTitle">
            <h2><?= $siteinfos->sname ?></h2>
            <address>
                <?= $siteinfos->address ?><br />
                <b><?= $CI->lang->line('topbar_email') ?>:</b> <?= $siteinfos->email ?><br />
                <b><?= $CI->lang->line('topbar_phone') ?>:</b> <?= $siteinfos->phone ?>
            </address>
        </div>
    </div>
<?php
}

function featurefooter($siteinfos)
{
    $CI = &get_instance(); ?>
    <div class="footerArea">
        <img class="flogo" src="<?= base_url('uploads/images/' . $siteinfos->photo) ?>" alt="">
        <p class="copyright"><?= $siteinfos->footer ?> | <?= $CI->lang->line('topbar_hotline') ?>
            <b> : </b><?= $siteinfos->phone ?>
        </p>
    </div>
<?php
}

function lang($line, $for = '', $attributes = [])
{
    $line = get_instance()->lang->line($line);

    if ($for !== '') {
        $line = '<label for="' . $for . '"' . _stringify_attributes($attributes) . '>' . $line . '</label>';
    }

    return $line;
}

if (!function_exists('redirect_back')) {
    function redirect_back()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME']);
        }
        exit;
    }
}

function imageLinkWithDefatulImage($photoName, $defaultPhotoName = 'default.png', $srcpath = null)
{
    if ($srcpath == null) {
        if ($photoName != null) {
            if (file_exists(FCPATH . 'uploads/images/' . $photoName)) {
                $src = base_url('uploads/images/' . $photoName);
            } else {
                $src = base_url('uploads/images/' . $defaultPhotoName);
            }
        } else {
            $src = base_url('uploads/images/' . $defaultPhotoName);
        }
    } else {
        if ($photoName != null) {
            if (file_exists(FCPATH . $srcpath . '/' . $photoName)) {
                $src = base_url($srcpath . '/' . $photoName);
            } else {
                $src = base_url('uploads/images/' . $defaultPhotoName);
            }
        } else {
            $src = base_url('uploads/images/' . $defaultPhotoName);
        }
    }
    return $src;
}

function imageLinkWithEventImage($photoName, $defaultPhotoName = 'default.png', $srcpath = null)
{
    if ($srcpath == null) {
        if ($photoName != null) {
            if (file_exists(FCPATH . 'uploads/events/' . $photoName)) {
                $src = base_url('uploads/events/' . $photoName);
            } else {
                $src = base_url('uploads/events/' . $defaultPhotoName);
            }
        } else {
            $src = base_url('uploads/events/' . $defaultPhotoName);
        }
    } else {
        if ($photoName != null) {
            if (file_exists(FCPATH . $srcpath . '/' . $photoName)) {
                $src = base_url($srcpath . '/' . $photoName);
            } else {
                $src = base_url('uploads/events/' . $defaultPhotoName);
            }
        } else {
            $src = base_url('uploads/events/' . $defaultPhotoName);
        }
    }
    return $src;
}

function imagelink($photoname, $reso = null, $srcpath = null)
{
    $src = '';
    if ($srcpath == null) {
        if ($photoname != null) {
            if($reso != null) {
                if (file_exists(FCPATH . 'uploads/images/'.$reso.'/' . $photoname)) {
                    $src = base_url('uploads/images/'.$reso.'/' . $photoname);
                } elseif(file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                    $src = base_url('uploads/images/'. $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                }
            } else {
                if (file_exists(FCPATH . 'uploads/images/'. $photoname)) {
                    $src = base_url('uploads/images/'. $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                }
            }   
            
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if ($photoname != null) {
            if($reso != null) {
                if (file_exists(FCPATH .$srcpath . '/'. $reso.'/' . $photoname)) {
                    $src = base_url($srcpath . '/'. $reso . '/' . $photoname);
                } elseif(file_exists(FCPATH . $srcpath. '/' . $photoname)) {
                    $src = base_url($srcpath . '/' . $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                }
            }else{
                if (file_exists(FCPATH . $srcpath . '/' . $photoname)) {
                    $src = base_url($srcpath . '/' . $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                } 
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }
    return $src;
}

function imagelink1($photoname, $reso = null, $srcpath = null)
{
    $src = '';
    if ($srcpath == null) {
        if ($photoname != null) {
            if($reso != null) {
                if (file_exists(FCPATH . 'uploads/images/'.$reso.'/' . $photoname)) {
                    $src = base_url('uploads/images/'.$reso.'/' . $photoname);
                } elseif(file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                    $src = base_url('uploads/images/'. $photoname);
                } else {
                    $src = base_url('assets/images/no-image-preview.png');
                }
            } else {
                if (file_exists(FCPATH . 'uploads/images/'. $photoname)) {
                    $src = base_url('uploads/images/'. $photoname);
                } else {
                    $src = base_url('assets/images/no-image-preview.png');
                }
            }   
            
        } else {
            $src = base_url('assets/images/no-image-preview.png');
        }
    } else {
        if ($photoname != null) {
            if($reso != null) {
                if (file_exists(FCPATH .$srcpath . '/'. $reso.'/' . $photoname)) {
                    $src = base_url($srcpath . '/'. $reso . '/' . $photoname);
                } elseif(file_exists(FCPATH . $srcpath. '/' . $photoname)) {
                    $src = base_url($srcpath . '/' . $photoname);
                } else {
                    $src = base_url('assets/images/no-image-preview.png');
                }
            }else{
                if (file_exists(FCPATH . $srcpath . '/' . $photoname)) {
                    $src = base_url($srcpath . '/' . $photoname);
                } else {
                    $src = base_url('assets/images/no-image-preview.png');
                } 
            }
        } else {
            $src = base_url('assets/images/no-image-preview.png');
        }
    }
    return $src;
}

function pdfimagelink($photoname, $srcpath = null,$reso)
{
    if ($srcpath == null) {
        if ($photoname != null) {
            if($resp != null){
                if (file_exists(FCPATH . 'uploads/images/' . $reso . '/' . $photoname)) {
                    $src = base_url('uploads/images/' . $reso .'/'. $photoname);
                }elseif(file_exists(FCPATH . 'uploads/images/' . $photoname)){
                    $src = base_url('uploads/images/' . $photoname);
                }
                 else {
                    $src = base_url('uploads/images/default.png');
                }
            }else{
                if (file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                    $src = base_url('uploads/images/' . $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                }
            }
            
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if ($photoname != null) {
            if (file_exists(FCPATH . $srcpath . '/' . $photoname)) {
                $src = base_url($srcpath . '/' . $photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }
    return $src;
}

function profileimage($photoname,$reso = null, $srcpath = null)
{
    if ($srcpath == null) {
        if ($photoname != null) {
            if($reso != null) {
                if (file_exists(FCPATH . 'uploads/images/'.$reso.'/' . $photoname)) {
                    $src = base_url('uploads/images/'.$reso.'/' . $photoname);
                } elseif(file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                    $src = base_url('uploads/images/'. $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                }
            } else {
                if (file_exists(FCPATH . 'uploads/images/'. $photoname)) {
                    $src = base_url('uploads/images/'. $photoname);
                } else {
                    $src = base_url('uploads/images/default.png');
                }
            }   
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if ($photoname != null) {
            if (file_exists(FCPATH . $srcpath . '/' . $photoname)) {
                $src = base_url($srcpath . '/' . $photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }

    $array = [
        "src" => $src,
        'width' => '35px',
        'height' => '35px',
        'class' => 'img-rounded',
    ];
    return img($array);
}

function profileviewimage($photoname, $srcpath = null)
{
    if ($srcpath == null) {
        if ($photoname != null) {
            if (file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                $src = base_url('uploads/images/' . $photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if ($photoname != null) {
            if (file_exists(FCPATH . $srcpath . '/' . $photoname)) {
                $src = base_url($srcpath . '/' . $photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }

    $array = [
        "src" => $src,
        'class' => 'profile-user-img img-responsive img-circle',
    ];
    return img($array);
}

function profileproimage($photoname, $srcpath = null)
{
    if ($srcpath == null) {
        if ($photoname != null) {
            if (file_exists(FCPATH . 'uploads/images/' . $photoname)) {
                $src = base_url('uploads/images/' . $photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    } else {
        if ($photoname != null) {
            if (file_exists(FCPATH . $srcpath . '/' . $photoname)) {
                $src = base_url($srcpath . '/' . $photoname);
            } else {
                $src = base_url('uploads/images/default.png');
            }
        } else {
            $src = base_url('uploads/images/default.png');
        }
    }

    $string = '<a width="35px" height="35px" class="card-image img-rounded" href="#" style="background-image: url(' . base_url("uploads/images/default.png") . ');" data-image-full="' . $src . '"><img class="img-rounded" width="35px" height="35px" src="' . $src . '" alt="Psychopomp" /></a>';
    return $string;

    // $array = array(
    //     "src" => $src,
    //     'width' => '35px',
    //     'height' => '35px',
    //     'class' => 'img-rounded'
    // );
    // return img($array);
}

function profiledeleted($id, $lang)
{
    if (!$id) {
        return '<span class="text-red">(' . $lang . ')';
    }
}

function get_day_using_two_date($fromDate, $toDate)
{
    $oneDay = 60 * 60 * 24;

    $day = [];
    for ($i = $fromDate; $i <= $toDate; $i = $i + $oneDay) {
        $day[] = date('d-m-Y', $i);
    }
    return $day;
}

function random19()
{
    $number = "";
    for ($i = 0; $i < 19; $i++) {
        $min = ($i == 0) ? 1 : 0;
        $number .= mt_rand($min, 9);
    }
    return $number;
}

function timelefter($dafstdate)
{
    $pdate = date("Y-m-d H:i:s");
    $first_date = new DateTime($dafstdate);
    $second_date = new DateTime($pdate);
    $difference = $first_date->diff($second_date);
    if ($difference->y >= 1) {
        $format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, $dafstdate);
        return $date->format('M d Y');
    } elseif ($difference->m == 1 && $difference->m != 0) {
        return $difference->m . " month ago";
    } elseif ($difference->m <= 12 && $difference->m != 0) {
        return $difference->m . " months ago";
    } elseif ($difference->d == 1 && $difference->d != 0) {
        return "Yesterday";
    } elseif ($difference->d <= 31 && $difference->d != 0) {
        return $difference->d . " days ago";
    } else {
        if ($difference->h == 1 && $difference->h != 0) {
            return $difference->h . " hr ago";
        } else {
            if ($difference->h <= 24 && $difference->h != 0) {
                return $difference->h . " hrs ago";
            } elseif ($difference->i <= 60 && $difference->i != 0) {
                return $difference->i . " mins ago";
            } elseif ($difference->s <= 10) {
                return "Just Now";
            } elseif ($difference->s <= 60 && $difference->s != 0) {
                return $difference->s . " sec ago";
            }
        }
    }
}

function markCalculation($getmark, $subjectFinalmark, $percentageMark = 100)
{
    $retMark = (($getmark * $percentageMark) / $subjectFinalmark);
    return floor($retMark);
}

function markCalculationView($getmark, $subjectFinalmark, $percentageMark = 100)
{
    if ($getmark == 0 || $getmark == '') {
        return 0;
    } else {
        $retMark = (($getmark * 100) / $subjectFinalmark);
        if ($retMark == 0) {
            return 0;
        } else {
            if ($percentageMark != 0) {
                $retMark = (($retMark * 100) / $percentageMark);
            }
            return floor($retMark);
        }
    }
}

function convertMarkpercentage($getMark, $subjectFinalMark, $percentage = 100)
{
    $retMarkpercentage = (($getMark * $subjectFinalMark) / $percentage);
    // return floor($retMarkpercentage);
    return $retMarkpercentage;
}

function inputCall($name = null)
{
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata, true);
    if ($request == null) {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        } else {
            return $_POST;
        }
        return;
    } else {
        if (isset($request[$name])) {
            return $request[$name];
        } else {
            return $request;
        }
        return;
    }
}

function ini_round($number, $digit = 2)
{
    $number = round($number, $digit, PHP_ROUND_HALF_EVEN);
    return sprintf("%." . $digit . "f", $number);
}

function iniArrayToString($arrays)
{
    $retString = "";
    if (customCompute($arrays)) {
        foreach ($arrays as $value) {
            $retString .= $value . ".<br/>";
        }
    }
    return $retString;
}

function checkFileExtension($file_name = '')
{
    $file_parts = pathinfo($file_name);
    if (isset($file_parts['extension'])) {
        switch ($file_parts['extension']) {
            case "jpg":
            case "png":
            case "jpeg":
            case "svg":
                return "fa-picture-o";
            case "pdf":
                return "fa-file-pdf-o";
            case "docx":
            case "doc":
                return "fa-file-word-o";
            case "csv":
            case "xlsx":
            case "xls":
                return "fa-file-excel-o";
            case "mp4":
                return "fa-video-camera";
            case "": // Handle file extension for files ending in '.'
            case null: // Handle no file extension
                return "fa-question-circle";
                break;
        }
    }else{
        return "fa-question-circle";
    }
}

function checkIfPhoto($file_name)
{
    $file_parts = pathinfo($file_name);
    switch ($file_parts['extension']) {
        case "jpg":
        case "png":
        case "jpeg":
        case "svg":
            return True;
        case "pdf":
        case "docs":
        case "mp4":
        case "": // Handle file extension for files ending in '.'
        case null: // Handle no file extension
            return False;
            break;
    }
}

function checkLinkType($type)
{
    switch ($type) {
        case "Youtube":
            return "fa-youtube-play";
        case "Image":
            return "fa-picture-o";
        case "Drive":
            return "fa-google";
        case "Pdf":
            return "fa-file-pdf-o";
            break;
        case "Others":
        case "": // Handle file extension for files ending in '.'
        case null: // Handle no file extension
            return "fa-link";
            break;
    }
}

function checkIfPhotoLink($type)
{
    switch ($type) {
        case "Image":
            return True;
        case "Youtube":
        case "Drive":
        case "Pdf":
        case "Others":
        case "": // Handle file extension for files ending in '.'
        case null: // Handle no file extension
            return False;
            break;
    }
}

function showBadRequest($headerResponse = null, $message = null)
{
    $ci = &get_instance();
    $ci->output
        ->set_status_header($headerResponse ? $headerResponse : 400)
        ->set_content_type('application/json', 'utf-8')
        ->set_output($message ? $message : 'Invalid request!');
}

function calculatePercentage($mainValue, $actualValue)
{
    if ($actualValue != 0) {
        return ceil(($mainValue / $actualValue) * 100);
    } else {
        return 0;
    }
}

function sortDateArrayExam($a, $b)
{
    return strtotime($b->date) - strtotime($a->date);
}

function sortDateArray($a, $b)
{
    return strtotime($b->create_date) - strtotime($a->create_date);
}

function sortDateArray1($a, $b)
{
    return strtotime($b['create_date']) - strtotime($a['create_date']);
}

function getUserType($type)
{
    switch ($type) {
        case "1":
        default:
            return "Admin";
        case "2":
            return "Teacher";
        case "3":
            return "Student";
        case "4":
            return "Parents";
        case "5":
            return "Accountant";
        case "6":
            return "Librarian";
        case "7":
            return "Receptionist";
        case "8":
            return "Moderator";
    }
}

function getRangeDateString($timestamp)
{
    if ($timestamp) {
        $currentTime = strtotime('today');
        // Reset time to 00:00:00
        $timestamp = strtotime(date('Y-m-d 00:00:00', $timestamp));
        $days = round(($timestamp - $currentTime) / 86400);
        switch ($days) {
            case '0';
                return 'Today';
                break;
            case '-1';
                return 'Yesterday';
                break;
            case '-2';
                return 'Day before yesterday';
                break;
            case '1';
                return 'Tomorrow';
                break;
            case '2';
                return 'Day after tomorrow';
                break;
            default:
                if ($days > 0) {
                    return 'In ' . $days . ' days';
                } else {
                    return ($days * -1) . ' days ago';
                }
                break;
        }
    }
}

function verify_token($projectId, $idToken)
{
    $verifier = IdTokenVerifier::createWithProjectId($projectId);

    try {
        $token = $verifier->verifyIdToken($idToken);
    } catch (IdTokenVerificationFailed $e) {
        // echo $e->getMessage();
        dd($e->getMessage());
        return false;
    }

    try {
        $token = $verifier->verifyIdTokenWithLeeway($idToken, $leewayInSeconds = 10000000);
    } catch (IdTokenVerificationFailed $e) {
        print $e->getMessage();
        return false;
    }

    return true;
}

function create_notification()
{
    $arrNotification = array();
    $arrData = array();
    $arrNotification["body"] = "Test by Ruman.";
    $arrNotification["title"] = "PHP SEND NOTIFICATIONS";
    $arrNotification["sound"] = "default";
    $arrNotification["type"] = 1;
    return $arrNotification;
}

function sendPushNotification($fields)
{
    // Set POST variables
    $url = 'https://fcm.googleapis.com/fcm/send';

    $headers = array(
        'Authorization: key=' . 'AAAAQoRPS-s:APA91bF-KAmyygK85lOowjUsOjIeEpVewymBYSegy9yEil22Elw7Y6CyGOVjRDy0x72UnXrEofIGiDBydUUD1SOmmw8yYCjTOml2hlGAm3Y9UNKqI9n-nG8mIYjs9Bd-o2D7DVsJn3bG',
        'Content-Type: application/json',
    );
    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    // Execute post
    $result = curl_exec($ch);

    if ($result === false) {

        die('Curl failed: ' . curl_error($ch));
    }

    curl_close($ch);

    return ($result);
}

function sendToTopic($to, $message)
{
    $fields = array(
        'to' => '/topics/' . $to,
        'notification' => [
            'body' => $message['data']['message'],
            'title' => $message['data']['title'],
        ],
        'data' => $message,
    );

    return sendPushNotification($fields);
}
function sendNotification($to, $message)
{
    $message['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
    $message['data']['sound'] = 'default';
    $fields = array(
        'registration_ids' => $to,
        'notification' => [
            'body' => strip_tags($message['data']['message']),
            'title' => $message['data']['title'],
            'image' => isset($message['data']['photo']) ? $message['data']['photo'] : '',
            'action' => $message['data']['action'],
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ],
        'data' => $message['data'],
    );
    return sendPushNotification($fields);
}

function getGradeFromPoint($point)
{
    if ($point > 3.6) {
        return 'A+';
    } else if ($point > 3.2) {
        return 'A';
    } else if ($point > 2.8) {
        return 'B+';
    } else if ($point > 2.4) {
        return 'B';
    } else if ($point > 2.0) {
        return 'C+';
    } else if ($point > 1.6) {
        return 'C';
    } else if ($point > 1.2) {
        return 'D+';
    } else if ($point > 0.8) {
        return 'D';
    } else if ($point > 0) {
        return 'E';
    }
}

function getGradeFromMark($mark)
{
    if ($mark >= 90) {
        return 'A+';
    } else if ($mark >= 80) {
        return 'A';
    } else if ($mark >= 70) {
        return 'B+';
    } else if ($mark >= 60) {
        return 'B';
    } else if ($mark >= 50) {
        return 'C+';
    } else if ($mark >= 40) {
        return 'C';
    } else if ($mark >= 30) {
        return 'D+';
    } else if ($mark >= 20) {
        return 'D';
    } else if ($mark > 1) {
        return 'E';
    } else if ($mark == 0) {
        return '';
    }
}

function getMarkFromGrade($grade)
{
    if ($grade == 'A+') {
        return 95;
    } else if ($grade == 'A') {
        return 85;
    } else if ($grade == 'B+') {
        return 75;
    } else if ($grade == 'B') {
        return 65;
    } else if ($grade == 'C+') {
        return 55;
    } else if ($grade == 'C') {
        return 45;
    } else if ($grade == 'D+') {
        return 35;
    } else if ($grade == 'D') {
        return 25;
    } else if ($grade == 'E') {
        return 15;
    } else if ($grade == 'F') {
        return 0;
    }
}

function checkIfFromGrade($value)
{
    $grades = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D+', 'D', 'E', 'F', ''];
    if (in_array($value, $grades)) {
        return true;
    }
    return false;
}



function getVerifiedSignature($class_numeric)
{
    if ($class_numeric == 1 || $class_numeric == 2 || $class_numeric == 3 || $class_numeric == 4 || $class_numeric == 5) {
        return base_url('/assets/signature/Mrs_En_Kumari_Ghale.png');
    } else if ($class_numeric == 6 || $class_numeric == 7 || $class_numeric == 8) {
        return base_url('/assets/signature/Mrs_Bina_Tamang.png');
    } else if ($class_numeric == 9 || $class_numeric == 10) {
        return base_url('/assets/signature/Mrs_En_Kumari_Ghale.png');
    }
}

function getVerifiedBy($class_numeric)
{
    return '<tr><td align="center" class="dot-border-top"></td></tr>';
    // if ($class_numeric == 1 || $class_numeric == 2 || $class_numeric == 3 || $class_numeric == 4 || $class_numeric == 5) {
    //     return '<tr><td align="center" class="dot-border-top">Mrs. En Kumari Ghale</td></tr>';
    // } else if ($class_numeric == 6 || $class_numeric == 7 || $class_numeric == 8) {
    //     return '<tr><td align="center" class="dot-border-top">Mrs. Bina Tamang</td></tr>';
    // } else if ($class_numeric == 9 || $class_numeric == 10) {
    //     return '<tr><td align="center" class="dot-border-top">Mr. Kamal Mohan Sharma</td></tr>';
    // }
}

function getVerifiedBy1($class_numeric)
{
    return '';
    // if ($class_numeric == 1 || $class_numeric == 2 || $class_numeric == 3 || $class_numeric == 4 || $class_numeric == 5) {
    //     return 'Mrs. En Kumari Ghale';
    // } else if ($class_numeric == 6 || $class_numeric == 7 || $class_numeric == 8) {
    //     return 'Mrs. Bina Tamang';
    // } else if ($class_numeric == 9 || $class_numeric == 10) {
    //     return 'Mr. Kamal Mohan Sharma';
    // }
}

function getClassTeacherSignature($class_numeric, $section)
{
    if ($class_numeric == 2) {
        if ($section == 'A') {
            return base_url('/assets/signature/2B_chandrakala_basnet.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/2A_Bhagwati_Sharma.png');
        }
    } else if ($class_numeric == 3) {
        if ($section == 'A') {
            return base_url('/assets/signature/3A_Sunita_Mam.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/3B_Anjuli_mam.png');
        }
    } else if ($class_numeric == 4) {
        if ($section == 'A') {
            return base_url('/assets/signature/4A_ambika_mam.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/4B_Anju_Gurung.png');
        } else if ($section == 'C') {
            return base_url('/assets/signature/4C_Ramesh_Kumar_Bhandari.png');
        }
    } else if ($class_numeric == 5) {
        if ($section == 'A') {
            return base_url('/assets/signature/5A_Sabina_Mam.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/5B_Rita_Mam_copy.png');
        }
    } else if ($class_numeric == 6) {
        if ($section == 'A') {
            return base_url('/assets/signature/6A_Sabita_Singh_Thakuri.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/6B_Junu_Manandhar.png');
        }
    } else if ($class_numeric == 7) {
        if ($section == 'A') {
            return base_url('/assets/signature/7A_Srijana_KC.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/7B_Yani_Ram_Dahal.png');
        }
    } else if ($class_numeric == 8) {
        if ($section == 'A') {
            return base_url('/assets/signature/8A_kalpana_copy.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/8B_Tara.PNG');
        }
    } else if ($class_numeric == 9) {
        if ($section == 'A') {
            return base_url('/assets/signature/9A_Laxmi_Mam_copy.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/9B_Hum_Sir.png');
        }
    } else if ($class_numeric == 10) {
        if ($section == 'A') {
            return base_url('/assets/signature/10A_Arjun.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/10B_dpn_sign11.png');
        }
    } else if ($class_numeric == 11) {
        if ($section == 'A') {
            return base_url('/assets/signature/prasant.png');
        } else if ($section == 'B') {
            return base_url('/assets/signature/sanjev.png');
        }
    }
}

function getSchoolDays($class_numeric)
{
    if ($class_numeric == 2) {
        return 126;
    } else if ($class_numeric == 3) {
        return 127;
    } else if ($class_numeric == 4 || $class_numeric == 5) {
        return 131;
    } else if ($class_numeric == 6 || $class_numeric == 7 || $class_numeric == 8) {
        return 140;
    } else if ($class_numeric == 9 || $class_numeric == 10) {
        return 134;
    }
}

function convertAdToBs($date)
{
    $dateObj = new NepaliCalenderHelper();
    $nepaliDate = $dateObj->convertDateToNepali($date);
    return $nepaliDate['year'] . '-' . $nepaliDate['month'] . '-' . $nepaliDate['date'];
}

function mergeArray($array1, $array2, $key)
{
    $retArray = [];
    $flag     = false;
    if (customCompute($array1)) {
        $flag = true;
        foreach ($array1 as $array) {
            $retArray[$array->$key] = $array->$key;
        }
    }

    if (customCompute($array2)) {
        $flag = true;
        foreach ($array2 as $array) {
            $retArray[$array->$key] = $array->$key;
        }
    }
    if ($flag) {
        ksort($retArray);
    }
    return $retArray;
}
function updateUserPermissions()
{

    $CI = &get_instance();
    $CI->load->model('permission_m');
    $userdata        = $CI->session->userdata;
    $user_permission = $CI->permission_m->get_modules_with_permission($userdata['usertypeID']);

    foreach ($user_permission as $value) {
        $permissionArray['master_permission_set'][$value->name] = $value->active;
    }

    if ($userdata['usertypeID'] == 3 || $userdata['usertypeID'] == 1) {
        $permissionArray['master_permission_set']['take_exam'] = 'yes';
    }

    $CI->session->set_userdata(['get_permission' => true]);
    $CI->session->set_userdata($permissionArray);
}

function chunk_push_notification($push_users, $push_message)
{
    
    $new_push_users = [];
   
    if (customCompute($push_users)) {
        $new_push_users = array_chunk($push_users, 200, true);
    }

    if (customCompute($new_push_users)) {
        foreach ($new_push_users as $new_push_user) {
            sendNotification($new_push_user, $push_message);
        }
    }
}

function getEmbedUrl($url)
{
    // function for generating an embed link
    $finalUrl = '';

    if (strpos($url, 'facebook.com/') !== false) {
        // Facebook Video
        $finalUrl .= 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($url) . '&show_text=1&width=200';
    } else if (strpos($url, 'vimeo.com/') !== false) {
        // Vimeo video
        $videoId = isset(explode("vimeo.com/", $url)[1]) ? explode("vimeo.com/", $url)[1] : null;
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://player.vimeo.com/video/' . $videoId;
    } else if (strpos($url, 'youtube.com/') !== false) {
        // Youtube video
        $videoId = isset(explode("v=", $url)[1]) ? explode("v=", $url)[1] : null;
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://www.youtube.com/embed/' . $videoId;
    } else if (strpos($url, 'youtu.be/') !== false) {
        // Youtube  video
        $videoId = isset(explode("youtu.be/", $url)[1]) ? explode("youtu.be/", $url)[1] : null;
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://www.youtube.com/embed/' . $videoId;
    } else if (strpos($url, 'dailymotion.com/') !== false) {
        // Dailymotion Video
        $videoId = isset(explode("dailymotion.com/", $url)[1]) ? explode("dailymotion.com/", $url)[1] : null;
        if (strpos($videoId, '&') !== false) {
            $videoId = explode("&", $videoId)[0];
        }
        $finalUrl .= 'https://www.dailymotion.com/embed/' . $videoId;
    } else {
        $finalUrl .= $url;
    }

    return $finalUrl;
}

function create_slug($string){
    $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return $slug;
 }

 function compressImage($source, $destination, $quality) { 
    // Get image info 
    $imgInfo = getimagesize($source); 
    $mime = $imgInfo['mime']; 
     
    // Create a new image from file 
    switch($mime){ 
        case 'image/jpeg': 
            $image = imagecreatefromjpeg($source); 
            break; 
        case 'image/png': 
            $image = imagecreatefrompng($source); 
            break; 
        case 'image/gif': 
            $image = imagecreatefromgif($source); 
            break; 
        default: 
            $image = imagecreatefromjpeg($source); 
    } 
     
    // Save image 
    imagejpeg($image, $destination, $quality); 
     
    // Return compressed image 
    return $destination; 
} 

function resizeImage($filename,$uploadPath)
{

   $source_path = $uploadPath .'/'. $filename;
   $target_path = $uploadPath;

   $config_manip = array(
       'image_library' => 'gd2',
       'source_image' => $source_path,
       'new_image' => $target_path,
       'maintain_ratio' => TRUE,
       'width' => 1800,
       'height' => 1800,
       'quality'   => 80

   );

   $CI = &get_instance();
   $CI->load->library('image_lib');
   $CI->image_lib->initialize($config_manip);  
   if (!$CI->image_lib->resize()) {
       echo $CI->image_lib->display_errors();
   }

   $CI->image_lib->clear();
}

function resizeImageDifferentSize($filename,$uploadPath,$w,$h)
{

  $resolutions  = ['1800','1500','1024','768','512','256','128','56'];

  $i = 0;
  foreach($resolutions as $resolution){

    if($w > $resolution || $h > $resolution){

       $source_path = $uploadPath .'/'. $filename;
       if($i == 0){
         $target_path = $uploadPath;
       }else{
            $target_path = $uploadPath.'/'.$resolution;
            if (!file_exists($target_path)) {
                mkdir($target_path, 0777, true);
            }
       }
 
        $config_manip = array(
            'image_library' => 'gd2',
            'source_image' => $source_path,
            'new_image' => $target_path,
            'maintain_ratio' => TRUE,
            'width' => $resolution,
            'height' => $resolution,
            'quality'   => 80
        );
 
        $CI = &get_instance();
        $CI->load->library('image_lib');
        $CI->image_lib->initialize($config_manip);  
        if (!$CI->image_lib->resize()) {
            echo $CI->image_lib->display_errors();
            exit;
        }
    
        $CI->image_lib->clear();

    }

    $i++;

  }
   
}


function thumbnail($filename,$uploadPath)
{

   $source_path = $uploadPath .'/'. $filename;
   $target_path = $uploadPath.'/thumb';

   $config_manip = array(
       'image_library' => 'gd2',
       'source_image' => $source_path,
       'create_thumb' => TRUE,
       'new_image' => $target_path,
       'maintain_ratio' => true,
       'width' => 50,
       'height' => 50,
       'quality'   => 100

   );

   $CI = &get_instance();
   $CI->load->library('image_lib');
   $CI->image_lib->initialize($config_manip);  
   if (!$CI->image_lib->resize()) {
       echo $CI->image_lib->display_errors();
   }

   $CI->image_lib->clear();
}

function resizeImageFromFolder($source_path,$target_path,$resolution)
{

   $config_manip = array(
       'image_library' => 'gd2',
       'source_image' => $source_path,
       'new_image' => $target_path,
       'maintain_ratio' => TRUE,
       'width' => $resolution,
       'height' => $resolution,
       'quality'   => 80

   );

   $CI = &get_instance();
   $CI->load->library('image_lib');
   $CI->image_lib->initialize($config_manip);  
   if (!$CI->image_lib->resize()) {
       echo $CI->image_lib->display_errors();
   }

   $CI->image_lib->clear();
}

function sendSMS($mobileNumbers,$smsContent){

    $CI = &get_instance();
    $CI->load->model('smssettings_m');

    $settingDatas = array();
    $smsSettings = $CI->smssettings_m->get_order_by_sparrow();
	foreach ($smsSettings as $key => $smsSetting) {
		$settingDatas[$smsSetting->field_names] = $smsSetting->field_values;
	}

    $mobileNumbers = implode(',', $mobileNumbers);
    

    if(count($settingDatas) > 0){
        if($settingDatas['sparrow_url'] != '' && $settingDatas['sparrow_from'] != '' && $settingDatas['sparrow_token'] != ''){
            
            $args = http_build_query(array(

                'token' => $settingDatas['sparrow_token'],
        
                'from'  => $settingDatas['sparrow_from'],
        
                'to'    => $mobileNumbers,
        
                'text'  => $smsContent
            ));
        
        
        
            $url = $settingDatas['sparrow_url'];
        
            # Make the call using API.
        
            $ch = curl_init();
        
            curl_setopt($ch, CURLOPT_URL, $url);
        
            curl_setopt($ch, CURLOPT_POST, 1);
        
            curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
            // Response
        
            $response = curl_exec($ch);
        
            //var_dump(json_decode($response)->response);exit;
        
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
            curl_close($ch);
        
            return json_decode($response);

        }
    }

}

function getSMSCredit(){

    $CI = &get_instance();
    $CI->load->model('smssettings_m');

    $settingDatas = array();
    $smsSettings = $CI->smssettings_m->get_order_by_sparrow();
	foreach ($smsSettings as $key => $smsSetting) {
		$settingDatas[$smsSetting->field_names] = $smsSetting->field_values;
	}

    if(count($settingDatas) > 0){
        if($settingDatas['sparrow_url'] != '' && $settingDatas['sparrow_from'] != '' && $settingDatas['sparrow_token'] != ''){

                $api_url = "http://api.sparrowsms.com/v2/credit/?".
                http_build_query(array(
                    'token' => $settingDatas['sparrow_token']
                ));    

                $response = file_get_contents($api_url);

                return json_decode($response);
            
        }
    }

}

function createLoginLog(){

    $CI = &get_instance();
    $CI->load->model('log_m');

    if($CI->session->userdata("usertypeID") == 3){
        $logData = [
            'studentID'         =>  $CI->session->userdata("loginuserID"),
            'event'             => 'login-logout',
            'remarks'           => 'logged in by '.$CI->session->userdata("name"),
            'start_datetime'    =>  date('Y-m-d H:i:s'),
            'schoolyearID'      =>  $CI->session->userdata('defaultschoolyearID')
        ];
        $CI->log_m->insert_course_log($logData);
    }

}


function createLogoutLog(){

    $CI = &get_instance();
    $CI->load->model('log_m');

    if($CI->session->userdata("usertypeID") == 3){

        $lastLog = $CI->log_m->get_single_course_log(
            [
                'studentID' => $CI->session->userdata("loginuserID"),
                'event'     => 'login-logout',
            ]
        );

        list($seconds,$timeSpend) = calculate_timeSpend($lastLog->start_datetime);

        $updatelogData = [
            'end_datetime' => date('Y-m-d H:i:s'),
            'time_spent'   => $timeSpend,
            'second_spent' => $seconds
        ];
        $CI->log_m->update_course_log($updatelogData,$lastLog->id);

        latestCourseLog();
    }

}


function createCourseLog($event,$remarks){

    $CI = &get_instance();
    $CI->load->model('log_m');

    if($CI->session->userdata("usertypeID") == 3){

       latestCourseLog();
      
        $logData = [
            'studentID'         => $CI->session->userdata("loginuserID"),
            'event'             => $event,
            'remarks'           => $remarks,
            'start_datetime'    => date('Y-m-d H:i:s'),
            'schoolyearID'      =>  $CI->session->userdata('defaultschoolyearID')
        ];
        $CI->log_m->insert_course_log($logData);
    }

}


function  latestCourseLog(){

    $CI = &get_instance();
    $CI->load->model('log_m');

    $lastLog = $CI->log_m->get_single_course_log([
        'studentID' => $CI->session->userdata("loginuserID")
    ]);
   if($lastLog){
        if($lastLog->event != 'login-logout'){
            list($seconds,$timeSpend) = calculate_timeSpend($lastLog->start_datetime);
            $CI->log_m->update_course_log([
                'end_datetime' => date('Y-m-d H:i:s'),
                'time_spent'   => $timeSpend,
                'second_spent' => $seconds
            ],$lastLog->id);
        }
   }

}



function calculate_timeSpend($start_datetime){

    $datetime1 = new DateTime($start_datetime);
    $datetime2 = new DateTime();
    $interval = $datetime1->diff($datetime2);
   
    // $elapsed = $interval->format('%y years %m months %a days %h hours %i minutes %s seconds');
    $timeSpend = $interval->format('%a days %h hours %i minutes %s seconds');

    $daysInSecs = $interval->format('%r%a') * 24 * 60 * 60;
    $hoursInSecs = $interval->h * 60 * 60;
    $minsInSecs = $interval->i * 60;
    
    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $interval->s;


    return [$seconds,$timeSpend];

}

function secondConversion($totaltimespent){

    $secs = $totaltimespent % 60;
    $hrs = $totaltimespent / 60;
    $mins = $hrs % 60;
    $hrs = $hrs / 60;
    $t = (int)$hrs.':'.(int)$mins.':'.(int)$secs;   

    return $t;

}




