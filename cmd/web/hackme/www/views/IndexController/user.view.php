<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="c-card">
                <span class="c-icon c-icon--info u-mb-small">
                  <i class="feather icon-user"></i>
                </span>

                <h3 class="c-text--subtitle">Viewing Profile of <?php echo $data['firstname'].' '.$data['lastname'].' ('.$data['email'].')'; ?></h3>

                <p></p>
                <table>
                    <tr>
                        <td>Email:</td>
                        <td><?php echo $data['email']; ?></td>
                    </tr>
                    <tr>
                        <td>Rank:</td>
                        <td><?php echo ($data['rank'] == 0 ? "User" : "Admin"); ?></td>
                    </tr>
                    <?php if($data['id'] != $user['id']) { ?>
                    <tr>
                        <td>Impersonate:</td>
                        <td><a href="<?php echo $this->getURL("/impersonate/".$data['id']); ?>">Impersonate</a></td>
                    </tr>
                    <?php } ?>
                </table>
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