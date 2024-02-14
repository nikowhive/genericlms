<div class="container container--sm">
  <header class="pg-header mt-4">
    <h1 class="pg-title">Users</h1>
  </header>

  <div class="row">
    <div class="col-md-6">
      <div class="md-form-block">
        <div class="md-form input-with-post-icon datepicker">
          <input
            placeholder="Search"
            type="text"
            name = "text"
            id="searchText"
            class="form-control"
            value="<?php echo isset($_REQUEST['text'])?$_REQUEST['text']:''; ?>"
          />
          <br>
          <button type="submit" id="searchBtn" class="btn btn-success">Search</button>
        
           </div>
      </div>
    </div>
  </div>

  <section class="mt-4 mb-5 pb-5">
    
    <div class="mt-4 mb-4 pb-3">
    <?php if(count($results) > 0){ ?>
       <div class="leftContent" style="float: left;">
          <h4><b>Search Result</b></h4>
       </div>
       <div class="rightContent" style="float: right;">
          <a class="btn btn-sm btn-default" target="_blank" href="<?=base_url()?>search/exportExcel?text=<?=$_REQUEST['text']?>" title="Excel"><i class="fa fa-file-excel-o"></i> XLSX</a>
          <a class="btn btn-sm btn-default" target="_blank" href="<?=base_url()?>search/exportPDF?text=<?=$_REQUEST['text']?>" title="PDF"><i class="fa fa-file-pdf-o"> PDF Preview</i></a>
       </div>
       <?php } ?>
    </div>
    <div class="card mt-3 card--attendance">
     
      <div class="card-body p-0">
        <div class="attendee-lists" id="searchData">
          <?php if(count($results) > 0){
            foreach($results as $result){
            ?>
            <div class="attendee-lists-item">
                <div class="media-block">
                  <figure class="avatar__figure">
                    <span class="avatar__image">
                      <img
                        src="<?php echo $result['icon']; ?>"
                        alt=""
                      />
                    </span>
                  </figure>
                  <div class="media-block-body">
                    <div class="media-content">
                      <h4 class="title">
                        <a target="_blank" href="<?php echo $result['url']; ?>" ><b class=""><?php echo $result['name']; ?></b></a>
                      </h4>
                      <?php if($result['designation']): ?>
                         <span class="" style="font-size: 14px;">
                            <?php echo $result['designation']; ?>
                         </span>
                         <br>
                      <?php endif ?>
                      <?php if($result['registerNO']): ?>
                         <span class="" style="font-size: 14px;">
                            <?php echo 'Regno:'.$result['registerNO'].'&nbsp;&nbsp;&nbsp;Class:'.$result['class'].'&nbsp;&nbsp;&nbsp;Section:'.$result['section'].'&nbsp;&nbsp;&nbsp;Roll:'.$result['roll'] ?>
                         </span>
                         <br>
                      <?php endif ?>
                     
                      <span class="pill pill--sm bg-success">
                         <?php echo $result['usertype']; ?>
                      </span>
                     
                    </div>
                   
                  </div>
                </div>
              </div>

          <?php }}else{
             echo '<p style="padding:10px;">No result found.</p>';
          } ?>    
        </div>
      </div>
    </div>
  </section>
</div>

<script>
   $(document).ready(function(){
    
                $("#searchBtn").on("click", function() {
                  
                    var text = $('#searchText').val();
                    if(text == ''){
                      alert('Please type search text.');
                      return false;
                    }
                    var url = "<?=base_url() ?>search/index?text="+text;
                      window.location.href = url;

                });
            });
</script>

<script type="application/javascript">

    $(document).ready(function(){
        // alert('hi');

    var pageValue = 0;
    var hasData = true;
    $('body').scroll(function () {
        if ($('body').scrollTop() + $('body').height() >= $(document).height()) {
            pageValue += 7;
            var text = $('#searchText').val();
            var url = "<?=base_url() ?>search/loadMoreResult?text="+text+'&p='+ pageValue;
            if(hasData) {
                $.ajax({
                    url: url,
                    type: "get",
                    success: function (response) {
                        console.log(response);
                        $('#searchData').append(response);
                    },
                    error: function (response) {
                        hasData = false;
                    }
                });
            }
        }
    });

    });


 
</script>
