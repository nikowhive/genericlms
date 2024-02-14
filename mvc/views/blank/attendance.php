<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">
        Studend Attendance
    </h1>
  </header>

  <div class="row">
      <div class="col-md-6">
        <div class="md-form-block">
            <div class="md-form  input-with-post-icon datepicker">
                <input placeholder="Select date" type="text" id="example" class="form-control datepicker">
               
                <i class="fa fa-calendar input-prefix" tabindex=0></i>
              </div>
 
        </div>
      </div>

      <div class="col-md-6 mt-3 mt-lg-0">
        <div class="md-form-block">
            <div class="md-form--select md-form">
                <select class="mdb-select">
                    <option value="1" selected>Select Class</option>
                    <option value="2">My Classes</option>
                    <option value="3">Class 7 A</option>
                    <option value="3">Class 8 A</option>
                    <option value="3">Class 8 B</option>
                  
                </select>
               
            </div>
 
        </div>
      </div>
  </div>

  <section class="mt-4">
    <h4><b>Class Attendance</b></h4>
    <div class="card mt-3 card--attendance">
        <div class="card-header ">
            <div class="row row-md-flex">
                <div class="col-md-4 ">
                    <h3 class="card-title mb-3 mb-lg-0">Class 7 <span class="pill pill--flat pill--sm">A</span></h3>
                    <div class="mt-2">
                        Science | Physics
                    </div>
                </div>
                <div class="col-md-4 attendance-stats">
                    <div>34 Studends</div>
                    <span class="text-success">All present</span>
                </div>
                <div class="col-md-4 attendance-action">
                    <a href="#" class="btn-link btn">
                        Take Attendance 
                        <i class="fa fa-2x fa-angle-right ml-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
  </section>

  <section class="mt-4">
    <h4><b>Subject Attendance</b></h4>
    <div class="card mt-3 card--attendance">
        <div class="card-header ">
            <div class="row row-md-flex">
                <div class="col-md-4 ">
                    <h3 class="card-title mb-3 mb-lg-0">Class 7 <span class="pill pill--flat pill--sm">A</span></h3>
                    <div class="mt-2">
                        Science | Physics
                    </div>
                </div>
                <div class="col-md-4 attendance-stats">
                    <div>34 Studends</div>
                    <span class="text-danger">4 Absent</span>
                </div>
                <div class="col-md-4 attendance-action">
                    <a href="#" class="btn-link btn">
                        Take Attendance 
                        <i class="fa fa-2x fa-angle-right ml-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3 card--attendance">
        <div class="card-header ">
            <div class="row row-md-flex">
                <div class="col-md-4 ">
                    <h3 class="card-title mb-3 mb-lg-0">Class 7 <span class="pill pill--flat pill--sm">A</span></h3>
                    <div class="mt-2">
                        Science | Physics
                    </div>
                </div>
                <div class="col-md-4 attendance-stats">
                    <div>34 Studends</div>
                    <span class="text-danger">4 Absent</span>
                </div>
                <div class="col-md-4 attendance-action">
                    <a href="#" class="btn-link btn">
                        Take Attendance 
                        <i class="fa fa-2x fa-angle-right ml-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3 card--attendance">
        <div class="card-header ">
            <div class="row row-md-flex">
                <div class="col-md-4 ">
                    <h3 class="card-title mb-3 mb-lg-0">Class 7 <span class="pill pill--flat pill--sm">A</span></h3>
                    <div class="mt-2">
                        Science | Physics
                    </div>
                </div>
                <div class="col-md-4 attendance-stats">
                    <div>34 Studends</div>
                    <span class="text-danger">4 Absent</span>
                </div>
                <div class="col-md-4 attendance-action">
                    <a href="#" class="btn-link btn">
                        Take Attendance 
                        <i class="fa fa-2x fa-angle-right ml-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-3 card--attendance">
        <div class="card-header ">
            <div class="row row-md-flex">
                <div class="col-md-4 ">
                    <h3 class="card-title mb-3 mb-lg-0">Class 7 <span class="pill pill--flat pill--sm">A</span></h3>
                    <div class="mt-2">
                        Science | Physics
                    </div>
                </div>
                <div class="col-md-4 attendance-stats">
                    <div>34 Studends</div>
                    <span class="text-danger">4 Absent</span>
                </div>
                <div class="col-md-4 attendance-action">
                    <a href="#" class="btn-link btn">
                        Take Attendance 
                        <i class="fa fa-2x fa-angle-right ml-3"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
  </section>
</div>


<script>
$(function(){
    $('.datepicker').pickadate();
})
</script>