<!DOCTYPE html>
	<head>
	    <title><?=$this->lang->line('panel_title')?></title>
	    <meta charset="utf-8" />
	    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.7.8/css/bootstrap.css" />
	    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/1.7.8/css/react-select.css" />
	    <meta name="format-detection" content="telephone=no">
	    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	</head>

	<body>
		<?php 
			$role = 0;
			if(($liveclass->user_id == $this->session->userdata('loginuserID') && $liveclass->usertype_id == $this->session->userdata('usertypeID')) || ($this->session->userdata('usertypeID') == 1) || ($liveclass->usertype_id == 1 && $this->session->userdata('loginuserID') == 2)) {
				$role = 1;
			} else {
				$role = 0;
			}
		?>
		<script>
	        var API_KEY 		= '<?=$zoomsetting->api_key?>';
	        var API_SECRET 		= '<?=$zoomsetting->api_secret?>';
	        var METTING_NUMBER 	= '<?=$liveclass->metting_id?>';
	        var USER_NAME 		= '<?=$this->session->userdata('name')?>';
	        var PASSWORD 		= '<?=$liveclass->password?>';
	        var LEAVE_URL		= '<?=base_url('liveclass/index')?>';
	        var ROLE 			= '<?=$role?>';
		</script>
		<script src="https://source.zoom.us/1.7.8/lib/vendor/react.min.js"></script>
		<script src="https://source.zoom.us/1.7.8/lib/vendor/react-dom.min.js"></script>
		<script src="https://source.zoom.us/1.7.8/lib/vendor/redux.min.js"></script>
		<script src="https://source.zoom.us/1.7.8/lib/vendor/redux-thunk.min.js"></script>
		<script src="https://source.zoom.us/1.7.8/lib/vendor/jquery.min.js"></script>
		<script src="https://source.zoom.us/1.7.8/lib/vendor/lodash.min.js"></script>
		<script src="https://source.zoom.us/zoom-meeting-1.8.6.min.js"></script>
		<script src="<?=base_url('assets/liveclass/js/view.js')?>"></script>
	</body>
</html>
	   