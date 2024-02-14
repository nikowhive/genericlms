<div class="container container--md">
  <header class="pg-header">
    <h1 class="pg-title">Add quiz question</h1>
  </header>
  <div class="row row--quiz">
    <div class="col-lg-4 order-lg-2 mb-3 mb-lg-0">
      <div class="card card--quiz card--quiz-Sidebar js-affix-top affix-top">
        <div class="card-body">
          <a class="quiz-sidebar-title " role="button" data-toggle="collapse" href="#quizFilters"
          aria-expanded="false">
          <span>Quiz Settings</span>
          <i class="fa fa-caret-down"></i>
      </a>
          <h3 class="card-title">Quiz Settings</h3>
          <div id="quizFilters" class="collapse">
            <div class="md-form--select md-form">
              <select class="mdb-select">
                <option value="1" selected>Class 7 A</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
                <option value="5">Option 5</option>
              </select>
              <label class="mdb-main-label">Class</label>
            </div>
  
            <div class="md-form--select md-form">
              <select class="mdb-select">
                <option value="1" selected>Science - Physics</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
                <option value="5">Option 5</option>
              </select>
              <label class="mdb-main-label">Subject</label>
            </div>
  
            <div class="md-form--select md-form">
              <select class="mdb-select">
                <option value="1" selected>Simple Machine</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
                <option value="5">Option 5</option>
              </select>
              <label class="mdb-main-label">Unit</label>
            </div>
  
            <div class="md-form--select md-form">
              <select class="mdb-select">
                <option value="1" selected>Types of simple machine</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
                <option value="5">Option 5</option>
              </select>
              <label class="mdb-main-label">Chapter</label>
            </div>
          </div>

        </div>
      </div>
    </div>
    <div class="col-lg-8 order-lg-1 ">
      <div class="card card--quiz">
        <div class="card-list card-list--title">
          <span class="icon-round icon-round--primary">
            <i class="fa fa-puzzle-piece"></i>
          </span>
          <div class="md-form mt-2 mb-0">
            <textarea
              id="form7"
              placeholder="Your Question here..."
              class="md-textarea form-control"
              rows="2"
            ></textarea>
            <label for="form7">Question Title</label>
          </div>
        </div>
        <div class="card-list card-list--item">
          <a
            class="icon-round collapsed"
            role="button"
            data-toggle="collapse"
            href="#questions"
            aria-expanded="false"
          >
            <i class="fa fa-caret-down"></i>
          </a>
          <h4 class="text-primary mt-2"><b>2 Questions added</b></h4>
        </div>
        <div id="questions" class="collapse">
          <div class="card-list card-list--item">
            <a
              class="icon-round collapsed"
              role="button"
              data-toggle="collapse"
              href="#answer1"
              aria-expanded="false"
            >
              <i class="fa fa-caret-down"></i>
            </a>
            <div class="quiz-display">
              <div class="quiz-display-header">
                <div class="quiz-display-section">
                  <div class="quiz-display-type">True/False</div>
                  <h4 class="quiz-display-title">
                    Q1 Software development cycle consist of
                  </h4>
                </div>
                <div class="dropdown">
                  <a
                    href="#"
                    class="icon-round"
                    role="button"
                    data-toggle="dropdown"
                  >
                    ⋮</a
                  >
                  <ul
                    id="menu2"
                    class="dropdown-menu right"
                    aria-labelledby="drop5"
                  >
                    <li>
                      <a href="#">Action</a>
                    </li>
                    <li>
                      <a href="#">Another action</a>
                    </li>
                    <li>
                      <a href="#">Something else here</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                      <a href="#">Separated link</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div id="answer1" class="collapse">
                <ol type="A" class="quiz-display-answers">
                  <li><b>True</b></li>
                  <li>False</li>
                </ol>
              </div>
            </div>
          </div>
          <div class="card-list card-list--item">
            <a
              class="icon-round collapsed"
              role="button"
              data-toggle="collapse"
              href="#answer2"
              aria-expanded="false"
            >
              <i class="fa fa-caret-down"></i>
            </a>
            <div class="quiz-display">
              <div class="quiz-display-header">
                <div class="quiz-display-section">
                  <div class="quiz-display-type">Single Choice</div>
                  <h4 class="quiz-display-title">
                    Q1 Software development cycle consist of
                    <span class="pill pill--sm bg-danger"> New </span>
                  </h4>
                </div>
                <div class="dropdown">
                  <a
                    href="#"
                    class="icon-round"
                    role="button"
                    data-toggle="dropdown"
                  >
                    ⋮</a
                  >
                  <ul
                    id="menu2"
                    class="dropdown-menu right"
                    aria-labelledby="drop5"
                  >
                    <li>
                      <a href="#">Action</a>
                    </li>
                    <li>
                      <a href="#">Another action</a>
                    </li>
                    <li>
                      <a href="#">Something else here</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                      <a href="#">Separated link</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div id="answer2" class="collapse">
                <ol type="A" class="quiz-display-answers">
                  <li><b>aasfd</b></li>
                  <li>aasfdadf</li>
                  <li>aasfdadfasdfaf</li>
                  <li>aasfdadfasdfafadsfsaf</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

      
        <div class="card-body">
           <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs--buttons" role="tablist">
          <li role="presentation" class=" active">
            <a
              href="#import"
              aria-controls="profile"
              role="tab"
              data-toggle="tab"
              
              >Import Questions from library</a
            >
          </li>
          <li role="presentation" >
            <a href="#create" aria-controls="home" role="tab" data-toggle="tab"  
              >Create New Question</a
            >
          </li>
        </ul>

        
          <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="import">
              <div class="row">
                <div class="col-md-3">
                  <div class="md-form--select md-form">
                    <select class="mdb-select">
                      <option value="1" selected>All Library</option>
                      <option value="2">Option 2</option>
                      <option value="3">Option 3</option>
                      <option value="4">Option 4</option>
                      <option value="5">Option 5</option>
                    </select>
                    <label class="mdb-main-label">Question Bank</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="md-form--select md-form">
                    <select class="mdb-select">
                      <option value="1">Single Choice</option>
                      <option value="2">True/False</option>
                      <option value="3">Option 3</option>
                      <option value="4">Option 4</option>
                      <option value="5">Option 5</option>
                    </select>
                    <label class="mdb-main-label">Question Type</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="md-form--select md-form">
                    <select class="mdb-select">
                      <option value="1">Option 1</option>
                      <option value="2">Option 2</option>
                      <option value="3">Option 3</option>
                      <option value="4">Option 4</option>
                      <option value="5">Option 5</option>
                    </select>
                    <label class="mdb-main-label">Question Group</label>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="md-form--select md-form">
                    <select class="mdb-select">
                      <option value="1">Option 1</option>
                      <option value="2">Option 2</option>
                      <option value="3">Option 3</option>
                      <option value="4">Option 4</option>
                      <option value="5">Option 5</option>
                    </select>
                    <label class="mdb-main-label">Difficult Level</label>
                  </div>
                </div>
              </div>
    
              <h4 class="text-primary mt-2 mb-3"><b>12 Questions found</b></h4>
              <div class="form-check mb-2">
                <input
                  class="form-check-input"
                  type="checkbox"
                  value=""
                  id="flexCheckDefault"
                />
                <label class="form-check-label" for="flexCheckDefault">
                  Bulk Import
                </label>
              </div>
    
              <div class="card-list card-list--questions added">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="1" />
                  <label class="form-check-label" for="1"> </label>
                </div>
                <div class="quiz-display">
                  <div class="quiz-display-header">
                    <div class="quiz-display-section">
                      <div class="quiz-display-type">Single Choice</div>
                      <h4 class="quiz-display-title" data-prefix="Q">
                        Software development cycle consist of
                      </h4>
                      <ol type="A" class="quiz-display-answers" data-prefix="A">
                        <li><b>aasfd</b></li>
                        <li>aasfdadf</li>
                        <li>aasfdadfasdfaf</li>
                        <li>aasfdadfasdfafadsfsaf</li>
                      </ol>
                    </div>
                    <button type="button" class="btn btn-default" disabled>
                      <i class="fa fa-check"></i> Add
                    </button>
                  </div>
                  <div></div>
                </div>
              </div>
              <div class="card-list card-list--questions">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="1" />
                  <label class="form-check-label" for="1"> </label>
                </div>
                <div class="quiz-display">
                  <div class="quiz-display-header">
                    <div class="quiz-display-section">
                      <div class="quiz-display-type">Single Choice</div>
                      <h4 class="quiz-display-title" data-prefix="Q">
                        Software development cycle consist of
                      </h4>
                      <ol type="A" class="quiz-display-answers" data-prefix="A">
                        <li><b>aasfd</b></li>
                        <li>aasfdadf</li>
                        <li>aasfdadfasdfaf</li>
                        <li>aasfdadfasdfafadsfsaf</li>
                      </ol>
                    </div>
                    <button type="button" class="btn btn-success">Add</button>
                  </div>
                  <div></div>
                </div>
              </div>
    
              <div class="text-center mt-4">
                <a href="#"> See More ...</a>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="create">
              <div class="row">
              
                <div class="col-md-3">
                  <div class="md-form--select md-form">
                    <select class="mdb-select">
                      <option value="1">Single Choice</option>
                      <option value="2">True/False</option>
                      <option value="3">Option 3</option>
                      <option value="4">Option 4</option>
                      <option value="5">Option 5</option>
                    </select>
                    <label class="mdb-main-label">Question Type</label>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="md-form--select md-form">
                    <select class="mdb-select">
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4"> 4</option>
                      <option value="5">5</option>
                    </select>
                    <label class="mdb-main-label">Options</label>
                  </div>
                </div>
                
                
              </div>

              <div class="md-form mt-1">
                <textarea  type="text" placeholder="Type new question here" id="queestion" class=" md-textarea form-control" rows="3" ></textarea>
                <label for="queestion">Question</label>
              </div>
            <h5 class="text-primary"><b>Answer Options</b></h5>
            <div class="row">
              
              <div class="col-md-6">
                <div class="answer-option-check">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="flexRadioDisabled"
                      id="flexRadioDisabled"
                       
                    />
                    <label class="form-check-label" for="flexRadioDisabled"> </label>
                  </div>
                  
                  <div class="md-form mb-md-3 mb-0">
                    <input  type="text"  value="anser opton"   id="queestion" class="  form-control"  ></input>
                    <label for="queestion">Option 1</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 ">
                  <div class="file-field nomargin">
                      <div class="btn" data-toggle="tooltip" data-placement="top"
                          title="Upload file">
                          <span><i class="fa fa-paperclip"></i> </span>
                          <input type="file" />
                      </div>
                      <div class="file-path-wrapper">
                          <input class="file-path validate form-control" type="text"
                              placeholder="Upload your file" />
                      </div>
                  </div>
              </div>
              </div>
            </div>
            <div class="row">
              
              <div class="col-md-6">
                <div class="answer-option-check">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="flexRadioDisabled"
                      id="optoin2"
                       
                    />
                    <label class="form-check-label" for="optoin2"> </label>
                  </div>
                  
                  <div class="md-form mb-md-3 mb-0">
                    <input  type="text"  value="anser opton"   id="queestion" class="  form-control"  ></input>
                    <label for="queestion">Option 2</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 ">
                  <div class="file-field nomargin">
                      <div class="btn" data-toggle="tooltip" data-placement="top"
                          title="Upload file">
                          <span><i class="fa fa-paperclip"></i> </span>
                          <input type="file" />
                      </div>
                      <div class="file-path-wrapper">
                          <input class="file-path validate form-control" type="text"
                              placeholder="Upload your file" />
                      </div>
                  </div>
              </div>
              </div>
            </div>
            <div class="row">
              
              <div class="col-md-6">
                <div class="answer-option-check">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="flexRadioDisabled"
                      id="optoin3"
                       
                    />
                    <label class="form-check-label" for="optoin3"> </label>
                  </div>
                  
                  <div class="md-form mb-md-3 mb-0">
                    <input  type="text"  value="anser opton"   id="queestion" class="  form-control"  ></input>
                    <label for="queestion">Option 3</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 ">
                  <div class="file-field nomargin">
                      <div class="btn" data-toggle="tooltip" data-placement="top"
                          title="Upload file">
                          <span><i class="fa fa-paperclip"></i> </span>
                          <input type="file" />
                      </div>
                      <div class="file-path-wrapper">
                          <input class="file-path validate form-control" type="text"
                              placeholder="Upload your file" />
                      </div>
                  </div>
              </div>
              </div>
            </div>
            <div class="row">
              
              <div class="col-md-6">
                <div class="answer-option-check">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="flexRadioDisabled"
                      id="optoin4"
                       
                    />
                    <label class="form-check-label" for="optoin4"> </label>
                  </div>
                  
                  <div class="md-form mb-md-3 mb-0">
                    <input  type="text"  value="anser opton"   id="queestion" class="  form-control"  ></input>
                    <label for="queestion">Option 4</label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 ">
                  <div class="file-field nomargin">
                      <div class="btn" data-toggle="tooltip" data-placement="top"
                          title="Upload file">
                          <span><i class="fa fa-paperclip"></i> </span>
                          <input type="file" />
                      </div>
                      <div class="file-path-wrapper">
                          <input class="file-path validate form-control" type="text"
                              placeholder="Upload your file" />
                      </div>
                  </div>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="md-form mb-md-3 mb-0">
                  <input  type="number"  value="anser opton"   id="mark" class="  form-control"  ></input>
                  <label for="mark">Mark</label>
                </div>
              </div>
              <div class="col-md-8">
                <div class="md-form md-form--file  mt-md-4 mt-0 mb-md-3 mb-0 ">
                  <div class="file-field nomargin">
                      <div class="btn" data-toggle="tooltip" data-placement="top"
                          title="Upload file">
                          <span><i class="fa fa-paperclip"></i> </span>
                          <input type="file" />
                      </div>
                      <div class="file-path-wrapper">
                          <input class="file-path validate form-control" type="text"
                              placeholder="Upload your file" />
                      </div>
                  </div>
              </div>
              </div>
            </div>

            <div class="md-form ">
              <textarea  type="text" placeholder="Write instruction if any" id="instruction" class=" md-textarea form-control" rows="3" ></textarea>
              <label for="instruction">Instruction (Optional)</label>
            </div>

            <div class="md-form ">
              <textarea  type="text" placeholder="Write hints if any" id="Hints" class=" md-textarea form-control" rows="3" ></textarea>
              <label for="Hints">Hints (Optional)</label>
            </div>

              <div class="button-wrapper">
                  <button type="submit" class="btn btn-success">Add Question</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>
