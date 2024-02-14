
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
                            <?php echo 'Regno: '.$result['registerNO'].'  Class: '.$result['class'].'  Section: '.$result['section'].'  Roll: '.$result['roll'] ?>
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

          <?php }} ?>    

      