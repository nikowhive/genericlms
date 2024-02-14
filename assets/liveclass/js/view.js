ZoomMtg.preLoadWasm();
ZoomMtg.prepareJssdk();

var meetConfig = {
    apiKey: API_KEY,
    apiSecret: API_SECRET,
    meetingNumber: parseInt(METTING_NUMBER),
    userName: USER_NAME,
    passWord: PASSWORD,
    leaveUrl: "https://zoom.us",
    role: parseInt(ROLE)
};
	        
var signature = ZoomMtg.generateSignature({
    meetingNumber: meetConfig.meetingNumber,
    apiKey: meetConfig.apiKey,
    apiSecret: meetConfig.apiSecret,
    role: meetConfig.role,
    success: function(res){
        console.log(res.result);
    }
});

ZoomMtg.init({
    leaveUrl: LEAVE_URL,
    success: function () {
        ZoomMtg.join(
            {
                meetingNumber: meetConfig.meetingNumber,
                userName: meetConfig.userName,
                signature: signature,
                apiKey: meetConfig.apiKey,
                passWord: meetConfig.passWord,
                success: function(res){
                    console.log('join meeting success');
                },
                error: function(res) {
                    console.log(res);
                }
            }
        );
    },
    error: function(res) {
        console.log(res);
    }
});