<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Student</h1>
  </header>

  <div class="row">
    <div class="col-md-6">
      <div class="md-form-block">
        <div class="md-form">
          <input
            type="search"
            id="form-autocomplete"
            placeholder="Search Student"
            class="form-control mdb-autocomplete"
          />
          <button class="mdb-autocomplete-clear">
            <svg
              fill="#000000"
              height="24"
              viewBox="0 0 24 24"
              width="24"
              xmlns="https://www.w3.org/2000/svg"
            >
              <path
                d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"
              />
              <path d="M0 0h24v24H0z" fill="none" />
            </svg>
          </button>
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
 
    <div class="card mt-3 card--attendance">
      <div class="card-header">
        <div class="row row-md-flex">
          <div class="col-md-4">
            <h3 class="card-title mb-3 mb-lg-0">
              Class 7 <span class="pill pill--flat pill--sm">A</span>
            </h3>
           
          </div>
    
          <div class="col-md-4 attendance-action">
            <a href="#" class="btn-link btn">
               34 Students
              <i class="fa fa-2x fa-angle-right ml-3"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="card mt-3 card--attendance">
      <div class="card-header">
        <div class="row row-md-flex">
          <div class="col-md-4">
            <h3 class="card-title mb-3 mb-lg-0">
              Class 7 <span class="pill pill--flat pill--sm">A</span>
            </h3>
           
          </div>
    
          <div class="col-md-4 attendance-action">
            <a href="#" class="btn-link btn">
               34 Students
              <i class="fa fa-2x fa-angle-right ml-3"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="card mt-3 card--attendance">
      <div class="card-header">
        <div class="row row-md-flex">
          <div class="col-md-4">
            <h3 class="card-title mb-3 mb-lg-0">
              Class 7 <span class="pill pill--flat pill--sm">A</span>
            </h3>
           
          </div>
    
          <div class="col-md-4 attendance-action">
            <a href="#" class="btn-link btn">
               34 Students
              <i class="fa fa-2x fa-angle-right ml-3"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
 
  </section>
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
  });
</script>
