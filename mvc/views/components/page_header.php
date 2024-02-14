<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <title><?= $this->lang->line('panel_title') ?></title>

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="white" />
    <link rel="apple-touch-icon" href="<?= base_url("uploads/images/$siteinfos->photo") ?>">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Eduwise ERP">
    <meta name="msapplication-TileImage" content="<?= base_url("uploads/images/$siteinfos->photo") ?>">
    <meta name="msapplication-TileColor" content="#FFFFFF">


    <link rel="SHORTCUT ICON" href="<?= base_url("uploads/images/$siteinfos->photo") ?>" />

    <link rel="stylesheet" href="<?= base_url('assets/pace/pace.css') ?>">
    <style>
        .wrs_modal_overlay {
            position: fixed;
            font-family: arial, sans-serif;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 999998;
            opacity: 0.65;
            pointer-events: auto;
        }

        .wrs_modal_overlay.wrs_modal_ios {
            visibility: hidden;
            display: none;
        }

        .wrs_modal_overlay.wrs_modal_android {
            visibility: hidden;
            display: none;
        }

        .wrs_modal_overlay.wrs_modal_ios.moodle {
            position: fixed;
        }

        .wrs_modal_overlay.wrs_modal_desktop.wrs_stack {
            background: rgba(0, 0, 0, 0);
            display: none;
        }

        .wrs_modal_overlay.wrs_modal_desktop.wrs_maximized {
            background: rgba(0, 0, 0, 0.8);
        }

        .wrs_modal_overlay.wrs_modal_desktop.wrs_minimized {
            background: rgba(0, 0, 0, 0);
            display: none;
        }

        .wrs_modal_overlay.wrs_modal_desktop.wrs_closed {
            background: rgba(0, 0, 0, 0);
            display: none;
        }

        .wrs_modal_title {
            color: #fff;
            padding: 5px 0 5px 10px;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            text-align: left;
        }

        .wrs_modal_close_button {
            float: right;
            cursor: pointer;
            color: #fff;
            padding: 5px 10px 5px 0;
            margin: 10px 7px 0 0;
            background-repeat: no-repeat;
        }

        .wrs_modal_minimize_button {
            float: right;
            cursor: pointer;
            color: #fff;
            padding: 5px 10px 5px 0;
            top: inherit;
            margin: 10px 7px 0 0;
        }

        .wrs_modal_stack_button {
            float: right;
            cursor: pointer;
            color: #fff;
            margin: 10px 7px 0 0;
            padding: 5px 10px 5px 0;
            top: inherit;
        }

        .wrs_modal_stack_button.wrs_stack {
            visibility: hidden;
            margin: 0;
            padding: 0;
        }

        .wrs_modal_stack_button.wrs_minimized {
            visibility: hidden;
            margin: 0;
            padding: 0;
        }

        .wrs_modal_maximize_button {
            float: right;
            cursor: pointer;
            color: #fff;
            margin: 10px 7px 0 0;
            padding: 5px 10px 5px 0;
            top: inherit;
        }

        .wrs_modal_maximize_button.wrs_maximized {
            visibility: hidden;
            margin: 0;
            padding: 0;
        }

        .wrs_modal_wrapper {
            display: block;
            margin: 6px;
        }

        .wrs_modal_title_bar {
            display: block;
            background-color: #778e9a;
        }

        .wrs_modal_dialogContainer {
            border: none;
            background: #fafafa;
            z-index: 999999;
        }

        .wrs_modal_dialogContainer.wrs_modal_desktop {
            font-size: 14px;
        }

        .wrs_modal_dialogContainer.wrs_modal_desktop.wrs_maximized {
            position: fixed;
        }

        .wrs_modal_dialogContainer.wrs_modal_desktop.wrs_minimized {
            position: fixed;
            top: inherit;
            margin: 0;
            margin-right: 10px;
        }

        .wrs_modal_dialogContainer.wrs_closed {
            visibility: hidden;
            display: none;
            opacity: 0;
        }


        /* Class that exists but hasn't got css properties defined
.wrs_modal_dialogContainer.wrs_modal_desktop.wrs_minimized.wrs_drag {} */

        .wrs_modal_dialogContainer.wrs_modal_desktop.wrs_stack {
            position: fixed;
            bottom: 0;
            right: 0;
            box-shadow: rgba(0, 0, 0, 0.5) 0 2px 8px;
        }

        .wrs_modal_dialogContainer.wrs_drag {
            box-shadow: rgba(0, 0, 0, 0.5) 0 2px 8px;
        }

        .wrs_modal_dialogContainer.wrs_modal_desktop.wrs_drag {
            box-shadow: rgba(0, 0, 0, 0.5) 0 2px 8px;
        }

        .wrs_modal_dialogContainer.wrs_modal_android {
            margin: auto;
            position: fixed;
            width: 99%;
            height: 99%;
            overflow: hidden;
            transform: translate(50%, -50%);
            top: 50%;
            right: 50% !important;
            position: fixed;
        }

        .wrs_modal_dialogContainer.wrs_modal_ios {
            margin: auto;
            position: fixed;
            width: 100%;
            height: 100%;
            overflow: hidden;
            transform: translate(50%, -50%);
            top: 50%;
            right: 50% !important;
            position: fixed;
        }


        /* Class that exists but hasn't got css properties defined
.wrs_content_container.wrs_maximized {} */

        .wrs_content_container.wrs_minimized {
            display: none;
        }

        /* .wrs_editor {
    flex-grow: 1;
} */

        .wrs_content_container.wrs_modal_android {
            width: 100%;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .wrs_content_container.wrs_modal_android>div:first-child {
            flex-grow: 1;
        }

        .wrs_content_container.wrs_modal_ios>div:first-child {
            flex-grow: 1;
        }

        .wrs_content_container.wrs_modal_desktop>div:first-child {
            flex-grow: 1;
        }

        .wrs_modal_wrapper.wrs_modal_android {
            margin: auto;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
        }

        .wrs_content_container.wrs_modal_desktop {
            width: 100%;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .wrs_content_container.wrs_modal_ios {
            width: 100%;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .wrs_modal_wrapper.wrs_modal_ios {
            margin: auto;
            display: flex;
            flex-direction: column;
            height: 100%;
            width: 100%;
        }

        .wrs_virtual_keyboard {
            height: 100%;
            width: 100%;
            top: 0;
            left: 50%;
            transform: translate(-50%, 0%);
        }

        @media all and (orientation: portrait) {
            .wrs_modal_dialogContainer.wrs_modal_mobile {
                width: 100vmin;
                height: 100vmin;
                margin: auto;
                border-width: 0;
            }

            .wrs_modal_wrapper.wrs_modal_mobile {
                width: 100vmin;
                height: 100vmin;
                margin: auto;
            }
        }

        @media all and (orientation: landscape) {
            .wrs_modal_dialogContainer.wrs_modal_mobile {
                width: 100vmin;
                height: 100vmin;
                margin: auto;
                border-width: 0;
            }

            .wrs_modal_wrapper.wrs_modal_mobile {
                width: 100vmin;
                height: 100vmin;
                margin: auto;
            }
        }

        .wrs_modal_dialogContainer.wrs_modal_badStock {
            width: 100%;
            height: 280px;
            margin: 0 auto;
            border-width: 0;
        }

        .wrs_modal_wrapper.wrs_modal_badStock {
            width: 100%;
            height: 280px;
            margin: 0 auto;
            border-width: 0;
        }

        .wrs_noselect {
            -moz-user-select: none;
            -khtml-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .wrs_bottom_right_resizer {
            width: 10px;
            height: 10px;
            color: #778e9a;
            position: absolute;
            right: 4px;
            bottom: 8px;
            cursor: se-resize;
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .wrs_bottom_left_resizer {
            width: 15px;
            height: 15px;
            color: #778e9a;
            position: absolute;
            left: 0;
            top: 0;
            cursor: se-resize;
        }

        .wrs_modal_controls {
            height: 42px;
            margin: 3px 0;
            overflow: hidden;
            line-height: normal;
        }

        .wrs_modal_links {
            margin: 10px auto;
            margin-bottom: 0;
            font-family: arial, sans-serif;
            padding: 6px;
            display: inline;
            float: right;
            text-align: right;
        }

        .wrs_modal_links>a {
            text-decoration: none;
            color: #778e9a;
            font-size: 16px;
        }

        .wrs_modal_button_cancel,
        .wrs_modal_button_cancel:hover,
        .wrs_modal_button_cancel:visited,
        .wrs_modal_button_cancel:active,
        .wrs_modal_button_cancel:focus {
            min-width: 80px;
            font-size: 14px;
            border-radius: 3px;
            border: 1px solid #778e9a;
            padding: 6px 8px;
            margin: 10px auto;
            margin-left: 5px;
            margin-bottom: 0;
            cursor: pointer;
            font-family: arial, sans-serif;
            background-color: #DDDDDD;
            height: 32px;
        }

        .wrs_modal_button_accept,
        .wrs_modal_button_accept:hover,
        .wrs_modal_button_accept:visited,
        .wrs_modal_button_accept:active,
        .wrs_modal_button_accept:focus {
            min-width: 80px;
            font-size: 14px;
            border-radius: 3px;
            border: 1px solid #778e9a;
            padding: 6px 8px;
            margin: 10px auto;
            margin-right: 5px;
            margin-bottom: 0;
            color: #fff;
            background: #778e9a;
            cursor: pointer;
            font-family: arial, sans-serif;
            height: 32px;
        }

        .wrs_editor_vertical_bar {
            height: 20px;
            float: right;
            background: none;
            width: 20px;
            cursor: pointer;
        }

        .wrs_modal_buttons_container {
            display: inline;
            float: left;
        }

        .wrs_modal_buttons_container.wrs_modalAndroid {
            padding-left: 6px;
        }

        .wrs_modal_buttons_container.wrs_modalDesktop {
            padding-left: 0;
        }

        .wrs_modal_buttons_container>button {
            line-height: normal;
            background-image: none;
        }

        .wrs_modal_wrapper {
            margin: 6px;
            display: flex;
            flex-direction: column;
        }

        .wrs_modal_wrapper.wrs_modal_desktop.wrs_minimized {
            display: none;
        }

        @media only screen and (max-device-width: 480px) and (orientation: portrait) {
            #wrs_modal_wrapper {
                width: 140%;
            }
        }

        .wrs_popupmessage_overlay_envolture {
            display: none;
            width: 100%;
        }

        .wrs_popupmessage_overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 4;
            cursor: pointer;
        }

        .wrs_popupmessage_panel {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            position: absolute;
            background: white;
            max-width: 500px;
            width: 75%;
            border-radius: 2px;
            padding: 20px;
            font-family: sans-serif;
            font-size: 15px;
            text-align: left;
            color: #2e2e2e;
            z-index: 5;
            max-height: 75%;
            overflow: auto;
        }

        .wrs_popupmessage_button_area {
            margin: 10px 0 0 0;
        }

        .wrs_panelContainer * {
            border: 0;
        }

        .wrs_button_cancel,
        .wrs_button_cancel:hover,
        .wrs_button_cancel:visited,
        .wrs_button_cancel:active,
        .wrs_button_cancel:focus {
            min-width: 80px;
            font-size: 14px;
            border-radius: 3px;
            border: 1px solid #778e9a;
            padding: 6px 8px;
            margin: 10px auto;
            margin-left: 5px;
            margin-bottom: 0;
            cursor: pointer;
            font-family: arial, sans-serif;
            background-color: #DDDDDD;
            background-image: none;
            height: 32px;
        }

        .wrs_button_accept,
        .wrs_button_accept:hover,
        .wrs_button_accept:visited,
        .wrs_button_accept:active,
        .wrs_button_accept:focus {
            min-width: 80px;
            font-size: 14px;
            border-radius: 3px;
            border: 1px solid #778e9a;
            padding: 6px 8px;
            margin: 10px auto;
            margin-right: 5px;
            margin-bottom: 0;
            color: #fff;
            background: #778e9a;
            cursor: pointer;
            font-family: arial, sans-serif;
            height: 32px;
        }

        .wrs_editor button {
            box-shadow: none;
        }

        .wrs_editor .wrs_header button {
            border-bottom: none;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        .wrs_modal_overlay.wrs_modal_desktop.wrs_stack.wrs_overlay_active {
            display: block;
        }

        /* Fix selection in drupal style */
        .wrs_toolbar tr:focus {
            background: none;
        }

        .wrs_toolbar tr:hover {
            background: none;
        }

        /* End of fix drupal */
        .wrs_modal_rtl .wrs_modal_button_cancel {
            margin-right: 5px;
            margin-left: 0;
        }

        .wrs_modal_rtl .wrs_modal_button_accept {
            margin-right: 0;
            margin-left: 5px;
        }

        .wrs_modal_rtl .wrs_button_cancel {
            margin-right: 5px;
            margin-left: 0;
        }

        .wrs_modal_rtl .wrs_button_accept {
            margin-right: 0;
            margin-left: 5px;
        }

        .sortable-title:hover {
            text-decoration: underline;
        }
    </style>

    <style>
        .treeview-animated.w-20 {
            width: 20rem;
            font-size: 14px;
        }

        .treeview-animated h6 {
            font-size: 0.9em;
        }

        .treeview-animated ul {
            position: relative;
            list-style: none;
            padding-left: 1em;
        }

        .treeview-animated-list li {
            padding: 0.2em 0 0 0.2em;
        }

        .treeview-animated-element {
            padding: 0.2em 0.2em 0.2em 0.6em;
            cursor: pointer;
            transition: all .1s linear;
            border-bottom-left-radius: 4px;
            border-top-left-radius: 4px;
        }

        .treeview-animated-element:hover {
            /* background-color: rgb(140, 185, 255); */
        }

        .treeview-animated-element.opened {
            /* color: #f8f9fa; */
            /* background-color: rgb(50, 160, 255); */
        }

        .treeview-animated-element.opened:hover {
            color: #f8f9fa;
            /* background-color: rgb(50, 160, 255); */
        }

        .treeview-animated-items .nested::before {
            content: "";
            display: block;
            position: absolute;
            background-color: grey;
            left: 5px;
            width: 5px;
            height: 100%;
        }

        .treeview-animated-items .closed {
            display: block;
            padding: 0.2em 0.2em 0.2em 0.4em;
            margin-right: 0;
            border-top-left-radius: 0.3em;
            border-bottom-left-radius: 0.3em;
        }


        .treeview-animated-items .closed:hover {
            /* background-color: rgb(140, 185, 255); */
        }

        .treeview-animated-items .open {
            transition: all .1s linear;
            /* background-color: rgb(50, 160, 255); */
        }

        .treeview-animated-items .open span {
            color: #000000;
        }

        .treeview-animated-items .open:hover {

            color: #f8f9fa;
            /* background-color: rgb(50, 160, 255); */
        }

        .treeview-animated ul li .open div:hover {
            /* background-color: rgb(50, 160, 255); */
        }

        .treeview-animated-items .closed .fa-angle-right {
            transition: all .1s linear;
            font-size: .8rem;
        }

        .closed .fa-angle-right.down {
            position: relative;
            color: #f8f9fa;
            transform: rotate(90deg);
        }
    </style>

 <!-- read more/less css -->
<style>
.description.full-text {
    max-height: 100%;
}

.description {
    max-height: 145px;
    position: relative;
    overflow: hidden;
    transition: max-height 0.3s linear;
}

.read-more a.active:before {
    content: "LESS";
}

.read-more a.active span {
    display: none;
}

.card--media .read-more {
    clear: both;
    padding-top: 20px;
    padding-bottom: 20px;
    text-align: right;
    padding-right: 30px
}

.card--media .read-more a {
    color: #236d37;
    font-size: 13px;
    font-weight: bold;
}
</style>

<style>

.myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

.myImg:hover {opacity: 0.7;}

/* The Modal (background) */
#feedModal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1040; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
#feedModal .modal-content {
  margin: auto;
  display: block;  
  width:auto;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ffffff;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
#feedModal.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.closeBtn {
  position: absolute;
  top: 6px;
  right: 26px;
  color: #ffffff;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.closeBtn:hover,
.closeBtn:focus {
  color: #ffffff;
  text-decoration: none;
  cursor: pointer;
}

#feedModal #img01{
    max-height: 80%!important;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
    #feedModal.modal-content {
    width: 100%;
  }
}

.sortable-title{
    cursor: pointer;
    
}

.logintimer{
    padding-top: 13px;
    padding-right: 10px;
    color: red;
}

.sortable-list{
    margin-bottom: 180px;
}

</style>

    <script type="text/javascript" src="<?php echo base_url('assets/inilabs/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/slimScroll/jquery.slimscroll.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/toastr/toastr.min.js'); ?>"></script>

    <script type="text/javascript" src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
    <script src="<?php echo base_url('assets/ckeditor/plugins/ckeditor_wiris/integration/WIRISplugins.js?viewer=image'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/tinymce/tinymce.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image'); ?>"></script>


    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/fonts/icomoon.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/fonts/ini-icon.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/datatables/dataTables.bootstrap.css'); ?>" rel="stylesheet">

    <link id="headStyleCSSLink" href="<?php echo base_url($backendThemePath . '/style.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/inilabs/hidetable.css'); ?>" rel="stylesheet">

    <link id="headInilabsCSSLink" href="<?php echo base_url($backendThemePath . '/inilabs.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/inilabs/responsive.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/toastr/toastr.min.css'); ?>" rel="stylesheet">

    <link href="<?php echo base_url('assets/inilabs/mailandmedia.css'); ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/datatables/buttons.dataTables.min.css'); ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/combined.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/ajaxloder/ajaxloder.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/easyautocomplete/easy-autocomplete.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/app.min.css'); ?>">

    <link href="<?php echo base_url('assets/lightbox2-2.11.3/dist/css/lightbox.css'); ?>" rel="stylesheet" />

    <script src="https://unpkg.com/nepali-date-picker@2.0.1/dist/jquery.nepaliDatePicker.min.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/nepali-date-picker@2.0.1/dist/nepaliDatePicker.min.css" crossorigin="anonymous" />
    <script src="https://unpkg.com/markerjs2/markerjs2.js"></script>

    <?php

    $cont = $this->uri->segment(1);
    $method = $this->uri->segment(2);

    if(($cont == 'event' AND ($method == '' OR $method == 'index')) OR ($cont == 'holiday' AND ($method == '' OR $method == 'index')) OR ($cont == 'notice' AND ($method == '' OR $method == 'index')) OR ($cont == 'feed' AND ($method == '' OR $method == 'index'))){ ?>
        <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/fb-img-grid/images-grid.css'); ?>"/>

   <?php }

    ?>

    <meta name="theme-color" content="#db4938" />

    <?php
    if (isset($headerassets)) {
        foreach ($headerassets as $assetstype => $headerasset) {
            if ($assetstype == 'css') {
                if (customCompute($headerasset)) {
                    foreach ($headerasset as $keycss => $css) {
                        echo '<link rel="stylesheet" href="' . base_url($css) . '">' . "\n";
                    }
                }
            } elseif ($assetstype == 'js') {
                if (customCompute($headerasset)) {
                    foreach ($headerasset as $keyjs => $js) {
                        echo '<script type="text/javascript" src="' . base_url($js) . '"></script>' . "\n";
                    }
                }
            }
        }
    }
    ?>
    <script src="<?php echo base_url('assets/Sortable.js'); ?>"></script>
    <link href="<?php echo base_url('assets/inilabs/app.min.css'); ?>" rel="stylesheet">
    <script type="text/javascript">
        $(window).load(function() {
            $(".se-pre-con").fadeOut("slow");;
        });
    </script>

    <script type="text/javascript">
        var BASE_URL = "<?php echo base_url(); ?>";
    </script>





</head>

<body class="skin-blue fuelux theme-<?= $siteinfos->backend_theme ?> <?= htmlentities(escapeString($this->uri->segment(1))) == 'courses' && (htmlentities(escapeString($this->uri->segment(2))) != 'index' && htmlentities(escapeString($this->uri->segment(2))) != '') ? 'course-module' : '' ?>">
    <div class="se-pre-con"></div>
    <div id="loading">
        <img src="<?= base_url('assets/ajaxloder/loader.gif') ?>" width="150" height="150" />
    </div>