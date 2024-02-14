<style>

.footer-top-area {
    background: #f1f2f7;
}

footer{
    transition: background .3s,border .3s,border-radius .3s,box-shadow .3s;
    margin-top: 0;
    margin-bottom: 0;
    /* padding: 200px 0 0; */
    z-index: 2;
}

.footer-top-area.area-padding{
    background-image: url(https://viedemo.eduwise.com.au/assets/images/Home5-14.png);
    background-position: bottom center;
    background-repeat: no-repeat;
    background-size: cover;
    widows: 100%;
    padding-top:180px; 
    padding-bottom: 0px;
   
}

.footer-widget > ul > li {
    display: block;
    border-bottom: none;
}

.footer-widget > ul > li > a {
    text-transform: none;
    font-size: 14px;
}

.footer-widget h6 {
    font-size: 18px;
    font-style: normal;
    padding: 10px 0 0px;
    font-weight: 600;
}

.copyrightWidget{
    border-style: solid;
    border-width: 1px 0 0;
    border-color: #515dbb;
    transition: background .3s,border .3s,border-radius .3s,box-shadow .3s;
    margin-top: 100px;
    margin-bottom: 0;
    padding: 23px 0;
}

.copyrightWidget p{
    color: #a7b4df;
    font-size: 14px;
    line-height: 26px;
}

.col-sm-3.footerdiv1{
    padding: 0 50px 0 0;
}

.footerDiv2{
    padding: 0 15px 0 30px;
}

.elementor-inline-items{
    margin-left: 20px;
}

.footer-widget > ul > li > a:hover {
    margin-left: 0;
    color:#fff;
}





</style>


<footer id="footer">
    <div class="footer-top-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-3 footerdiv2">
                    <div class="footer-widget">
                        <!-- Start Logo -->
                        <div class="logo footer-logo text-uppercase">
                            <img src="<?=base_url("assets/images/logo.png") ?>" alt="logo" data-logowidth="145" style="width: 300px;" width="145" height="45">
                        </div>
                        <!-- End Logo -->
                        <ul class="elementor-icon-list-items elementor-inline-items">
                            <li class="elementor-icon-list-item elementor-inline-item">
                               <span class="elementor-icon-list-text">RTO ID: 45457 <br>CRICOS Provider Code: <br>03734D</span>
                            </li>
                        </ul>
                      
                    </div>
                </div>

                <div class="col-sm-3 footerDiv2">
                    <div class="footer-widget">   
                            <h6 class="elementor-heading-title elementor-size-default">Current student</h6>
                            <ul>
                                <li><a href="#">Student service</a></li>
                                <li><a href="#">Visa requirement</a></li>
                                <li><a href="#">Obligations as student</a></li>
                                <li><a href="#">Useful links</a></li>
                            </ul>

                    </div>
                    <div class="footer-widget" style="margin-top: 20px;">   
                            <h6 class="elementor-heading-title elementor-size-default">Future student</h6>
                            <ul>
                                <li><a href="#">Orientation and induction</a></li>
                                <li><a href="#">Life in Australia</a></li>
                                <li><a href="#">Living and accommodation</a></li>
                               
                            </ul>
                       
                    </div>
                </div>
               
                <div class="col-sm-3 footerDiv2">
                    <div class="footer-widget">   
                            <h6 class="elementor-heading-title elementor-size-default">Quick links</h6>
                            <ul>
                                <li><a href="#">Study in Australia - callback request</a></li>
                                <li><a href="#">Student login</a></li>
                                <li><a href="#">Terms of use</a></li>
                                <li><a href="#">Disclaimer</a></li>
                                <li><a href="#">Agent list</a></li>
                                <li><a href="#">Education agents form</a></li>
                                <li><a href="#">Privacy notice</a></li>
                                <li><a href="#">Policies_CRICOS</a></li>
                                <li><a href="#">Prospectus</a></li>
                                <li><a href="#">Student handbook</a></li>
                                <li><a href="#">Fees & charges</a></li>
                                <li><a href="#">Student application form</a></li>
                                <li><a href="#">Education Agents</a></li>
                               
                            </ul>
                       
                    </div>
                </div>


                <div class="col-sm-3 footerDiv2">
                    <div class="footer-widget">   
                            <h6 class="elementor-heading-title elementor-size-default">Australian academy</h6>
                            <ul>
                                <li><i aria-hidden="true" class="fa fa-home"></i>&nbsp;&nbsp;Head Office Address: Level 3 382 Lonsdale St Melbourne VIC 3000</li>
                                <li><i aria-hidden="true" class="fa fa-phone"></i>&nbsp;&nbsp; +61(03) 9016 0603</li>
                                <li><i aria-hidden="true" class="fa fa-envelope"></i>&nbsp;&nbsp; admissions@viedemo.eduwise.com.au</li>
                                <li><i aria-hidden="true" class="fa fa-clock-o"></i>&nbsp;&nbsp; Monday - Friday: 8:00 AM - 09:00 PM</li>
                                <li><i aria-hidden="true" class="fa fa-clock-o"></i>&nbsp;&nbsp; Saturday : 8:00 AM - 02:00 PM</li>
                            </ul>
                       
                    </div>
                </div>

                
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright text-center copyrightWidget">
                       <p>© Copyright 2022 | Australian Academy Of Business & Technology. All Rights Reserved </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</footer>