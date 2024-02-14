<link
  href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css"
  rel="stylesheet"
/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>


<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#basicExampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="basicExampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="container container--sm">
  <!--Dropdown primary-->
<div class="dropdown">

  <!--Trigger-->
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown"
    aria-haspopup="true" aria-expanded="false">Material dropdown</button>

  <!--Menu-->
  <div class="dropdown-menu dropdown-primary">
    <a class="dropdown-item" href="#">Action</a>
    <a class="dropdown-item" href="#">Another action</a>
    <a class="dropdown-item" href="#">Something else here</a>
    <a class="dropdown-item" href="#">Something else here</a>
  </div>
</div>
<!--/Dropdown primary-->
  <header class="pg-header">
    <h1 class="pg-title">My teaching courses</h1>
  </header>
  <section class="section-course">
    <header class="section-header">
      <h3 class="section-title">Class 7</h3>
      <ul class="list-inline">
        <li><span class="pill">A</span></li>
        <li><span class="pill">B</span></li>
      </ul>

      <a href="#" class="btn btn-default js-morelink">Show More</a>
    </header>

    <div class="cards cards--coureses">
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x320/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">English</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course1"
              />
              <label class="onoffswitch-small-label" for="course1">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="http://placehold.jp/24/cccccc/ffffff/500x320.png?text=No Image"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course3"
              />
              <label class="onoffswitch-small-label" for="course3">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-course-add">
        <div class="card-body">
          <a
            href="javascript:;"
            class="card-anchor-absolute"
            data-toggle="modal"
            data-target="#addCourse"
          >
            <i class="fa fa-plus"></i>
            <span>Add Course</span>
          </a>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x320/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course4"
              />
              <label class="onoffswitch-small-label" for="course4">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x820/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course5"
              />
              <label class="onoffswitch-small-label" for="course5">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x320/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course4"
              />
              <label class="onoffswitch-small-label" for="course4">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x820/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course5"
              />
              <label class="onoffswitch-small-label" for="course5">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x320/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course4"
              />
              <label class="onoffswitch-small-label" for="course4">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x820/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course5"
              />
              <label class="onoffswitch-small-label" for="course5">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-course">
    <header class="section-header">
      <h3 class="section-title">Class 7</h3>
      <ul class="list-inline">
        <li><span class="pill">A</span></li>
        <li><span class="pill">B</span></li>
      </ul>

      <a href="#" class="btn btn-default js-morelink">Show More</a>
    </header>

    <div class="cards cards--coureses">
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x320/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">English</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course1"
              />
              <label class="onoffswitch-small-label" for="course1">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="http://placehold.jp/24/cccccc/ffffff/500x320.png?text=No Image"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course3"
              />
              <label class="onoffswitch-small-label" for="course3">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card card-course-add">
        <div class="card-body">
          <a
            href="javascript:;"
            class="card-anchor-absolute"
            data-toggle="modal"
            data-target="#addCourse"
          >
            <i class="fa fa-plus"></i>
            <span>Add Course</span>
          </a>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x320/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course4"
              />
              <label class="onoffswitch-small-label" for="course4">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="embed-responsive embed-responsive-16by9">
          <img
            src="https://source.unsplash.com/500x820/?Webinar"
            class="embed-responsive-item"
            alt=""
          />
        </div>
        <div class="card-body">
          <h3 class="card-title">Science</h3>
          <ul class="list-inline list-inline--course-lists">
            <li data-toggle="tooltip" data-placement="top" title="Units">
              <span class="counter">1</span>
              <i class="fa fa-book"></i>
            </li>
            <li data-toggle="tooltip" data-placement="top" title="Chapters">
              <span class="counter">15</span>
              <i class="fa fa-th-list"></i>
            </li>
          </ul>
          <div class="card-footer">
            <div class="onoffswitch-small">
              <input
                type="checkbox"
                name="course"
                class="onoffswitch-small-checkbox"
                id="course5"
              />
              <label class="onoffswitch-small-label" for="course5">
                <span class="onoffswitch-small-inner"></span>
                <span class="onoffswitch-small-switch"></span>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- add course modal starts -->
<div class="modal fade" tabindex="-1" role="dialog" id="addCourse">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="close"
          data-dismiss="modal"
          aria-label="Close"
        >
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Add new course</h3>
      </div>
      <div class="modal-body">
        <form action="">
          <div class="mdb-form-group">
            <select class="mdb-select md-form" searchable="Search here..">
              <option value="" disabled selected>Choose class</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="3">Four</option>
              <option value="3">Five</option>
            </select>
            <label class="mdb-main-label">Select Class</label>
          </div>
 

          <div class="mdb-form-group">
            <select class="mdb-select md-form mb-0" searchable="Search here..">
              <option value="" disabled selected>Choose Subject</option>
              <option value="1">One</option>
              <option value="2">Two</option>
              <option value="3">Three</option>
              <option value="3">Four</option>
              <option value="3">Five</option>
            </select>
            <label class="mdb-main-label">Select Subject</label>
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-primary">Add Course Now</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- add course modal ends -->

<script>
  $(document).ready(function () {
    $(".select2").select2({
      dropdownParent: $("#addCourse"),
      containerCssClass: "form-control",
    });
  });
</script>
