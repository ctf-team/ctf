<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="c-card">
                <span class="c-icon c-icon--info u-mb-small">
                  <i class="feather icon-message-circle"></i>
                </span>

                <h3 class="c-text--subtitle">Messages</h3>
                <?php if($this->auth->has_permission('admin') && $this->auth->is_impersonating()) {?>
                    <h3><b>You have one unread message!</b></h3>
                    <textarea readonly="true" rows="5" cols="75"><?php echo FLAG_1; ?></textarea>
                <?php } else if(!$this->auth->is_impersonating() && $this->auth->has_permission('admin') && $site_title == "Hacked by ".$user['firstname']) { ?>
                    <h3><b>You have one unread message!</b></h3>
                    <textarea readonly="true" rows="5" cols="75"><?php echo FLAG_4; ?></textarea>
                <?php } else if(!$this->auth->is_impersonating() && $this->auth->has_permission('admin')) {
                    ?><h3><b>You have one unread message!</b></h3>
                    <textarea readonly="true" rows="5" cols="75"><?php echo FLAG_3; ?></textarea><?php
                } else if($this->auth->has_permission('regular_user')) {
                    ?><h3>You have no unread messages.</h3><?php
                } ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <footer class="c-footer">
                <p>© 2018 Neat, Inc</p>
                <span class="c-footer__divider">|</span>
                <nav>
                    <a class="c-footer__link" href="#">Terms</a>
                    <a class="c-footer__link" href="#">Privacy</a>
                    <a class="c-footer__link" href="#">FAQ</a>
                    <a class="c-footer__link" href="#">Help</a>
                </nav>
            </footer>
        </div>
    </div>
</div>
