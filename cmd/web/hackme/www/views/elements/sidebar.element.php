<?php
$view = $this->view;
?>
<div class="o-page__sidebar js-page-sidebar">
    <aside class="c-sidebar">
        <br>
        <center>
            <h4><?php echo $site_title; ?></h4>
        </center>
        <br>

        <!-- Scrollable -->
        <div class="c-sidebar__body">
            <span class="c-sidebar__title">Dashboards</span>
            <ul class="c-sidebar__list">
                <li>
                    <a class="c-sidebar__link <?php if ($view == "dashboard") echo "is-active"; ?>" href="<?php echo $this->getURL('/'); ?>">
                        <i class="c-sidebar__icon feather icon-home"></i>Dashboard
                    </a>
                </li>
            </ul>

            <?php if($this->auth->has_permission('admin')) { ?>
            <span class="c-sidebar__title">Admin</span>
            <ul class="c-sidebar__list">
                <li>
                    <a class="c-sidebar__link  <?php if ($view == "userlist") echo "is-active"; ?>" href="<?php echo $this->getURL('/userlist'); ?>">
                        <i class="c-sidebar__icon feather icon-user"></i>User List
                    </a>
                </li>
                <?php if(!$this->auth->is_impersonating()) { ?>
                <li>
                    <a class="c-sidebar__link  <?php if ($view == "options") echo "is-active"; ?>" href="<?php echo $this->getURL('/options'); ?>">
                        <i class="c-sidebar__icon feather icon-globe"></i>Site Options
                    </a>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </div>

        <a class="c-sidebar__footer" href="<?php echo $this->getURL('/logout'); ?>">
            Logout <i class="c-sidebar__footer-icon feather icon-power"></i>
        </a>
    </aside>
</div>