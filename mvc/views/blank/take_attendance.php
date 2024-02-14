<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Student Attendance</h1>
  </header>

  <div class="row">
    <div class="col-md-6">
      <div class="md-form-block">
        <div class="md-form input-with-post-icon">
          <input
            placeholder="Select date"
            type="text"
            id="datetime"
            class="form-control"
          />

          <i class="fa fa-calendar input-prefix" tabindex="0"></i>
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

  <section class="mt-4 mb-5 pb-5">
    <h4><b>Class Attendance</b></h4>
    <div class="card mt-3 card--attendance">
      <div class="card-header">
        <div class="row row-md-flex">
          <div class="col-md-4">
            <h3 class="card-title mb-3 mb-lg-0">
              Class 7 <span class="pill pill--flat pill--sm">A</span>
            </h3>
            <div class="mt-2">Science | Physics</div>
          </div>
          <div class="col-md-4 attendance-stats">
            <div>34 Studends</div>
            <span class="text-success">All present</span>
          </div>
          <div class="col-md-4 attendance-action">
            <time>Monday, 10 Oct 2020</time>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="attendee-lists">
            <div class="attendee-lists-item">
                <div class="media-block">
                  <figure class="avatar__figure">
                    <span class="avatar__image">
                      <img
                        src="https://randomuser.me/api/portraits/men/85.jpg"
                        alt=""
                      />
                    </span>
                  </figure>
                  <div class="media-block-body">
                    <div class="media-content">
                      <h4 class="title">
                        <b class="">Samjhana Mahat</b>
                        <em class="rollnumber">Roll # <b>01</b></em>
                      </h4>
                      <span class="pill pill--sm bg-success">Present</span>
                     
                    </div>
                    <div class="action">
                      <label
                        class="switch"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Toggle Prsent/Absent"
                      >
                        <input
                          type="checkbox"
                          class="switch__input"
                          name=""
                          id=""
                        />
                        <span class="switch--unchecked">
                          <i class="fa fa-ban"></i>
                        </span>
                        <span class="switch--checked">
                          <i class="fa fa-check-circle"></i>
                        </span>
                      </label>
                      <div class="dropdown">
                        <a href="#" class=" " data-toggle="dropdown">
                          Action <i class="fa fa-caret-down"></i
                        ></a>
                        <ul class="dropdown-menu right" aria-labelledby="drop5">
                          <li>
                            <a href="#">Present </a>
                          </li>
                          <li>
                            <a href="#">Late Present With Excuse</a>
                          </li>
                          <li>
                            <a href="#">Late Present</a>
                          </li>
                          <li>
                            <a href="#">Absent </a>
                          </li>
                          <li>
                            <a href="javascript:;" data-toggle="modal"  data-target="#addNote">Absent With Note </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="attendee-lists-item">
                <div class="media-block">
                  <figure class="avatar__figure">
                    <span class="avatar__image">
                      <img
                        src="https://randomuser.me/api/portraits/men/85.jpg"
                        alt=""
                      />
                    </span>
                  </figure>
                  <div class="media-block-body">
                    <div class="media-content">
                      <h4 class="title">
                        <b class="">Samjhana Mahat</b>
                        <em class="rollnumber">Roll # <b>01</b></em>
                      </h4>
                      <span class="pill pill--sm bg-success">Present</span>
                     
                    </div>
                    <div class="action">
                      <label
                        class="switch"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Toggle Prsent/Absent"
                      >
                        <input
                          type="checkbox"
                          class="switch__input"
                          name=""
                          id=""
                        />
                        <span class="switch--unchecked">
                          <i class="fa fa-ban"></i>
                        </span>
                        <span class="switch--checked">
                          <i class="fa fa-check-circle"></i>
                        </span>
                      </label>
                      <div class="dropdown">
                        <a href="#" class=" " data-toggle="dropdown">
                          Action <i class="fa fa-caret-down"></i
                        ></a>
                        <ul class="dropdown-menu right" aria-labelledby="drop5">
                          <li>
                            <a href="#">Present </a>
                          </li>
                          <li>
                            <a href="#">Late Present With Excuse</a>
                          </li>
                          <li>
                            <a href="#">Late Present</a>
                          </li>
                          <li>
                            <a href="#">Absent </a>
                          </li>
                          <li>
                            <a href="javascript:;" data-toggle="modal"  data-target="#addNote">Absent With Note </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="attendee-lists-item">
                <div class="media-block">
                  <figure class="avatar__figure">
                    <span class="avatar__image">
                      <img
                        src="https://randomuser.me/api/portraits/men/85.jpg"
                        alt=""
                      />
                    </span>
                  </figure>
                  <div class="media-block-body">
                    <div class="media-content">
                      <h4 class="title">
                        <b class="">Samjhana Mahat</b>
                        <em class="rollnumber">Roll # <b>01</b></em>
                      </h4>
                      <span class="pill pill--sm bg-danger">Absent</span>
                     
                    </div>
                    <div class="action">
                      <label
                        class="switch"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Toggle Prsent/Absent"
                      >
                        <input
                          type="checkbox"
                          class="switch__input"
                          name=""
                          id=""
                        />
                        <span class="switch--unchecked">
                          <i class="fa fa-ban"></i>
                        </span>
                        <span class="switch--checked">
                          <i class="fa fa-check-circle"></i>
                        </span>
                      </label>
                      <div class="dropdown">
                        <a href="#" class=" " data-toggle="dropdown">
                          Action <i class="fa fa-caret-down"></i
                        ></a>
                        <ul class="dropdown-menu right" aria-labelledby="drop5">
                          <li>
                            <a href="#">Present </a>
                          </li>
                          <li>
                            <a href="#">Late Present With Excuse</a>
                          </li>
                          <li>
                            <a href="#">Late Present</a>
                          </li>
                          <li>
                            <a href="#">Absent </a>
                          </li>
                          <li>
                            <a href="javascript:;" data-toggle="modal"  data-target="#addNote">Absent With Note </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

          <div class="attendee-lists-item">
            <div class="media-block">
              <figure class="avatar__figure">
                <span class="avatar__image">
                  <img
                    src="https://randomuser.me/api/portraits/men/85.jpg"
                    alt=""
                  />
                </span>
              </figure>
              <div class="media-block-body">
                <div class="media-content">
                  <h4 class="title">
                    <b class="">Samjhana Mahat</b>
                    <em class="rollnumber">Roll # <b>01</b></em>
                  </h4>
                  <span class="pill pill--sm bg-danger">Absent</span>
                  <div class="blockquote mt-2">
                    He's not feeling well and will be unvailable next 3 days.
                  </div>
                </div>
                <div class="action">
                  <label
                    class="switch"
                    data-toggle="tooltip"
                    data-placement="bottom"
                    title="Toggle Prsent/Absent"
                  >
                    <input
                      type="checkbox"
                      class="switch__input"
                      name=""
                      id=""
                    />
                    <span class="switch--unchecked">
                      <i class="fa fa-ban"></i>
                    </span>
                    <span class="switch--checked">
                      <i class="fa fa-check-circle"></i>
                    </span>
                  </label>
                  <div class="dropdown">
                    <a href="#" class=" " data-toggle="dropdown">
                      Action <i class="fa fa-caret-down"></i
                    ></a>
                    <ul class="dropdown-menu right" aria-labelledby="drop5">
                      <li>
                        <a href="#">Present </a>
                      </li>
                      <li>
                        <a href="#">Late Present With Excuse</a>
                      </li>
                      <li>
                        <a href="#">Late Present</a>
                      </li>
                      <li>
                        <a href="#">Absent </a>
                      </li>
                      <li>
                        <a href="javascript:;" data-toggle="modal"  data-target="#addNote">Absent With Note </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </section>
</div>

<!--Modal-->
<div class="modal fade" tabindex="-1" role="dialog" id="addNote">
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
          <h3 class="modal-title">Write Note</h3>
        </div>
        <div class="modal-body">
          <form action="">
            <div class="md-form">                                    
                <textarea class="md-textarea form-control"  id="notes" name="notes" rows="4"></textarea>
                <label for="notes" class="active">Absent with Note </label>
               
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Close
          </button>
          <button type="button" class="btn btn-primary">Add Note</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<script>
  $(function () {
    var states = [
      "Alabama",
      "Alaska",
      "Arizona",
      "Arkansas",
      "California",
      "Colorado",
      "Connecticut",
      "Delaware",
      "Florida",
      "Georgia",
      "Hawaii",
      "Idaho",
      "Illnois",
      "Indiana",
      "Iowa",
      "Kansas",
      "Kentucky",
      "Louisiana",
      "Maine",
      "Maryland",
      "Massachusetts",
      "Michigan",
      "Minnesota",
      "Mississippi",
      "Missouri",
      "Montana",
      "Nebraska",
      "Nevada",
      "New Hampshire",
      "New Jersey",
      "New Mexico",
      "New York",
      "North Carolina",
      "North Dakota",
      "Ohio",
      "Oklahoma",
      "Oregon",
      "Pennsylvania",
      "Rhode Island",
      "South Carolina",
      "South Dakota",
      "Tennessee",
      "Texas",
      "Utah",
      "Vermont",
      "Virginia",
      "Washington",
      "West Virginia",
      "Wisconsin",
      "Wyoming",
    ];

    $("#form-autocomplete").mdb_autocomplete({
      data: states,
    });
    $("#datetime").pickadate();
  });
</script>
