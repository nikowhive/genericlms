<?php $this->load->view("components/page_header"); ?>
<?php $this->load->view("components/page_topbar"); ?>
<?php $this->load->view("components/page_menu"); ?>

        <aside class="right-side">
        <?php $this->load->view("components/course_menu"); ?>
            <div class="course-canvas">
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php $this->load->view($subview); ?>
                        </div>
                    </div>
                </section>
                 <footer class="main-footer"  >
                    <div class="pull-right hidden-xs">
                        <a target="_blank" href="<?=base_url('frontend/index')?>" class="dropdown-toggle" data-toggle="tooltip" title="<?=$this->lang->line('menu_visit_site')?>" data-placement="top">
                                <i class="fa fa-globe"></i>
                        </a>
    
                        <b>v</b> <?=config_item('ini_version')?>
                    </div>
                    <strong><?=$siteinfos->footer?></strong>
                </footer>
            </div>
        </aside>

<style>
.add-button {
  position: absolute;
  top: 1px;
  left: 1px;
}

</style>
<?php $this->load->view("components/page_footer"); ?>
</script>


