<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Alert extends Api_Controller
{

    private $_alert = [];
    private $_userAlert = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model("alert_m");
        $this->load->model('notice_m');
        $this->load->model('event_m');
        $this->load->model('holiday_m');
        $this->load->model('conversation_m');
    }

    public function index_get()
    {

        $schoolYearID = $this->session->userdata('defaultschoolyearID');
        $this->_userAlert();
        $this->_alertNotice($schoolYearID);
        $this->_alertEvent($schoolYearID);
        $this->_alertHoliday($schoolYearID);
        $alerts = $this->_alertOrder($this->_alert);
        $result = $this->_alertMarkup($alerts);

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $result
        ], REST_Controller::HTTP_OK);
    }

    private function _alertOrder($alerts)
    {
        $i          = 0;
        $alertOrder = [];
        if (customCompute($alerts)) {
            foreach ($alerts as $alert) {
                foreach ($alert as $alt) {
                    $alertOrder[$i] = (array) $alt;
                    $i++;
                }
            }
            array_multisort(array_column($alertOrder, "create_date"), SORT_DESC, $alertOrder);
        }
        return $alertOrder;
    }

    private function _userAlert()
    {
        $this->load->model('alert_m');
        $alerts = $this->alert_m->get_order_by_alert([
            'userID'     => $this->session->userdata('loginuserID'),
            'usertypeID' => $this->session->userdata('usertypeID')
        ]);
        if (customCompute($alerts)) {
            foreach ($alerts as $alert) {
                $this->_userAlert[$alert->itemID][$alert->itemname] = $alert;
            }
        }
        $this->_userAlert;
    }

        private function _alertNotice( $schoolYearID )
        {
            if ( permissionChecker('notice_view') ) {
                $notices = $this->notice_m->get_my_notices($schoolYearID);
                if ( customCompute($notices) ) {
                    foreach ( $notices as $notice ) {
                        if ( !isset($this->_userAlert[ $notice->noticeID ]['notice']) ) {
                                $this->_alert['notice'][] = $notice;

                        }
                    }
                }
            }
        }

        private function _alertEvent( $schoolYearID )
        {
            if ( permissionChecker('event_view') ) {
                $isNotAdmin = $this->session->userdata('usertypeID') != 1;
                $events = $this->event_m->get_my_events($schoolYearID,$isNotAdmin ? $this->session->userdata('username') : null);
                if ( customCompute($events) ) {
                    foreach ( $events as $event ) {
                        if ( !isset($this->_userAlert[ $event->eventID ]['event']) ) {
                                $this->_alert['event'][] = $event;

                        }
                    }
                }
            }
        }

    private function _alertHoliday($schoolYearID)
    {
        if (permissionChecker('holiday_view')) {
            $holiday = $this->holiday_m->get_order_by_holiday(['schoolyearID' => $schoolYearID]);
            if (customCompute($holiday)) {
                foreach ($holiday as $day) {
                    if (!isset($this->_userAlert[$day->holidayID]['holiday'])) {
                        $this->_alert['holiday'][] = $day;
                    }
                }
            }
        }
    }

    public function _alertMessage()
    {
        if (permissionChecker('conversation')) {
            $messages         = $this->conversation_m->get_my_conversations();
            $flagConversation = [];
            $flagSubject      = [];
            $mergeMessages    = [];

            if (customCompute($messages)) {
                foreach ($messages as $messageKey => $message) {
                    if (!array_key_exists($message->conversation_id, $flagSubject)) {
                        $flagSubject[$message->conversation_id] = $message->subject;
                    }

                    if (!isset($this->_userAlert[$message->msg_id]['message'])) {
                        if (!in_array($message->conversation_id, $flagConversation)) {
                            $flagConversation[] = $message->conversation_id;
                        }

                        if (in_array($message->conversation_id, $flagConversation)) {
                            $mergeMessages[$message->conversation_id] = $message;
                        }
                    }
                }
            }

            if (customCompute($mergeMessages)) {
                foreach ($mergeMessages as $messageKey => $message) {
                    if (empty($message->subject)) {
                        if (isset($flagSubject[$message->conversation_id])) {
                            $mergeMessages[$message->conversation_id]->subject = $flagSubject[$message->conversation_id];
                        }
                    }
                }
            }
            $this->_alert['message'] = $mergeMessages;
        }
    }

    private function _alertMarkup($alerts)
    {
        $result = [];
        if (customCompute($alerts) > 0) {
            foreach ($alerts as $alert) {
                $pusher = $this->_pusher($alert);
                $result[] = [
                    'itemID'               => $pusher->ID,
                    'name'             => $pusher->name,
                    'title'            => strip_tags($pusher->title),
                    'details'      => strip_tags($pusher->description),
                    'photo'            => $pusher->photo,
                    'itemname'             => $pusher->itemname,
                    'create_date'             => $pusher->date
                ];
            }
        }
        return $result;
    }

    private function _pusher($alert)
    {
        $ID          = '';
        $title       = '';
        $description = '';
        $link        = '';
        $date        = '';
        $photo       = '';
        $name        = '';
        $itemname    = '';

        if (customCompute($alert)) {
            if (isset($alert['noticeID'])) {
                $ID         = $alert['noticeID'];
                $link        = "notice/" . $alert['noticeID'];
                $itemname    = "notice";
                // $date        = $this->_timer($alert['create_date']);
                $date        = $alert['create_date'];
                $title       = $alert['title'];
                $description = $alert['notice'];
                $photo       = (customCompute(userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )) ? userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )->photo : 'default.png');
                $name      = (customCompute(userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )) ? userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )->name : '');
            } elseif (isset($alert['msg_id'])) {
                $ID         = $alert['conversation_id'];
                $link        = "message/" . $alert['conversation_id'];
                $itemname    = "message";
                $date        = $alert['create_date'];
                $title       = $alert['subject'];
                $description = $alert['msg'];
                $photo       = (customCompute(userInfo($alert['usertypeID'], $alert['user_id'])) ? userInfo(
                    $alert['usertypeID'],
                    $alert['user_id']
                )->photo : 'default.png');
                $name       = (customCompute(userInfo($alert['usertypeID'], $alert['user_id'])) ? userInfo(
                    $alert['usertypeID'],
                    $alert['user_id']
                )->name : '');
            } elseif (isset($alert['eventID'])) {
                $ID         = $alert['eventID'];
                $link        = "event/" . $alert['eventID'];
                $itemname    = "event";
                $date        = $alert['create_date'];
                $title       = $alert['title'];
                $description = $alert['details'];
                $photo       = (customCompute(userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )) ? userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )->photo : 'default.png');
                $name      = (customCompute(userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )) ? userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )->name : '');
            } elseif (isset($alert['holidayID'])) {
                $ID         = $alert['holidayID'];
                $link        = "holiday/" . $alert['holidayID'];
                $itemname    = "holiday";
                $date        = $alert['create_date'];
                $title       = $alert['title'];
                $description = $alert['details'];
                $photo       = (customCompute(userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )) ? userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )->photo : 'default.png');
                $name      = (customCompute(userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )) ? userInfo(
                    $alert['create_usertypeID'],
                    $alert['create_userID']
                )->name : '');
            }
        }
        $array = (object) [
            'ID'          => $ID,
            'title'       => $title,
            'description' => $description,
            'link'        => $link,
            'photo'       => $photo,
            'date'        => $date,
            'name'        => $name,
            'itemname'   => $itemname
        ];
        return $array;
    }

    private function _timer($createDate)
    {
        $date        = date('Y-m-d H:i:s');
        $presentDate = date("Y-m-d H:i:s");
        $firstDate   = new DateTime($createDate);
        $secondDate  = new DateTime($presentDate);
        $difference  = $firstDate->diff($secondDate);
        if ($difference->y >= 1) {
            $format = 'Y-m-d H:i:s';
            $date   = DateTime::createFromFormat($format, $createDate);
            $date   = $date->format('M d Y');
        } elseif ($difference->m == 1 && $difference->m != 0) {
            $date = $difference->m . " month";
        } elseif ($difference->m <= 12 && $difference->m != 0) {
            $date = $difference->m . " months";
        } elseif ($difference->d == 1 && $difference->d != 0) {
            $date = "Yesterday";
        } elseif ($difference->d <= 31 && $difference->d != 0) {
            $date = $difference->d . " days";
        } else {
            if ($difference->h == 1 && $difference->h != 0) {
                $date = $difference->h . " hr";
            } else {
                if ($difference->h <= 24 && $difference->h != 0) {
                    $date = $difference->h . " hrs";
                } elseif ($difference->i <= 60 && $difference->i != 0) {
                    $date = $difference->i . " mins";
                } elseif ($difference->s <= 10) {
                    $date = "Just Now";
                } elseif ($difference->s <= 60 && $difference->s != 0) {
                    $date = $difference->s . " sec";
                }
            }
        }
        return $date;
    }


    public function index1_get()
    {
        $schoolYearID = $this->session->userdata('defaultschoolyearID');
        $array = [
            'userID'     => $this->session->userdata('loginuserID'),
            'usertypeID' => $this->session->userdata('usertypeID')
        ];

        $notices = [];
        $events = [];
        $holidays = [];
        $notices = $this->alert_m->getUserNoticeAlerts($array, $schoolYearID);
        $events = $this->alert_m->getUserEventAlerts($array, $schoolYearID);
        $holidays = $this->alert_m->getUserHolidayAlerts($array, $schoolYearID);
        $mergeData = array_merge($notices, $holidays, $events);
        usort($mergeData, 'sortDateArray1');

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $mergeData
        ], REST_Controller::HTTP_OK);
    }
}
