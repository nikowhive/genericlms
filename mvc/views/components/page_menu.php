    <div class="wrapper row-offcanvas row-offcanvas-left">
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="left-side sidebar-offcanvas">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- Sidebar user panel -->
                <!-- <div class="user-panel">
                    <div class="pull-left image">
                        <img style="display:block" src="<?= imagelink($this->session->userdata('photo'),56) ?>" class="img-circle" alt="" />
                    </div>

                    <div class="pull-left info">
                        <?php
                        $name = $this->session->userdata("name");
                        if (strlen($name) > 18) {
                            $name = substr($name, 0, 18);
                        }
                        echo "<p>" . $name . "</p>";
                        ?>
                        <a href="<?= base_url("profile/index") ?>">
                            <i class="fa fa-hand-o-right color-green"></i>
                            <?= $this->session->userdata("usertype") ?>
                        </a>
                    </div>
                </div> -->

                <ul class="sidebar-menu">
                    <?php
                    if (customCompute($dbMenus)) {
                        $menuDesign = '';
                        display_menu($dbMenus, $menuDesign, true);
                        echo $menuDesign;
                    }
                    ?>
                </ul>
            </section>
            <!-- /.sidebar -->
        </aside>
        <div class="menu-overlay js-menu-overlay"></div>

        <script>
            let isHidden = true;
            let hiddenMenuItem = $('.hidden-menu-item');
            let showLessMenu = $('.show-less-menu');
            let showMoreMenu = $('.show-more-menu');
            hiddenMenuItem.hide();
            showLessMenu.hide();
           

            

            function showActiveMenu() {

                if (hiddenMenuItem.hasClass('active')) {
                    $('.active').css('display', 'list-item');
                }
            }

            $(function() {
                     showActiveMenu();
            });

            function showHiddenMenus() {
                if (isHidden) {
                    isHidden = false;
                    showMoreMenu.hide();
                    showLessMenu.show();
                    hiddenMenuItem.show();
                    showActiveMenu();
                    localStorage.setItem("hidden_menu", 1);


                } else {
                    isHidden = true;
                    showLessMenu.hide();
                    showMoreMenu.show();
                    hiddenMenuItem.hide();
                    showActiveMenu();
                    localStorage.removeItem("hidden_menu");
                }
            }

            if (localStorage.hidden_menu == 1) {
                showMoreMenu.hide();
                    showLessMenu.show();
                    hiddenMenuItem.show();
                    showActiveMenu();
                } else {
                    showLessMenu.hide();
                    showMoreMenu.show();
                    hiddenMenuItem.hide();
                    showActiveMenu();
                }
        </script>