<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="c-card">
                <form id="updateProfile">
                    <div class="c-field">
                        <label class="c-field__label">First Name</label>
                        <input class="c-input u-mb-small" type="text" name="firstname" value="<?php echo $user['firstname']; ?>">
                    </div>
                    <div class="c-field">
                        <label class="c-field__label">Last Name</label>
                        <input class="c-input u-mb-small" type="text" name="lastname" value="<?php echo $user['lastname']; ?>">
                    </div>
                    <div class="c-field">
                        <label class="c-field__label">Email</label>
                        <input class="c-input u-mb-small" type="text" name="email" value="<?php echo $user['email']; ?>">
                    </div>
                    <button class="c-btn c-btn--fullwidth c-btn--info">Save</button>
                </form>
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