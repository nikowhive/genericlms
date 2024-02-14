      <?php if(customCompute($user)) { ?>
        <section class="panel">
          <div class="small-box " style="height: 320px; ">
              <a class="small-box-footer bg-purple-light" href="<?=base_url('studentresult')?>" style="height: 320px; padding-top: 90px;">
                  <div class="icon bg-purple-light" style="padding: 9.5px 18px 6px 18px;">
                    <h3 class="text-white" style="font-size: 200px; padding-bottom: 36px;"><?php echo $dashboardWidget['results'] ?></h3>
                  </div>
                  <div class="inner ">
                  <i class="fa icon-student" style="font-size: 100px;"></i>
                      <p class="text-white" style="font-size: 40px;">Result <span id="result">(<?php echo $dashboardWidget['results'] ?>)</span></p>
                  </div>
              </a>
          </div>
        </section>
      <?php } ?>
