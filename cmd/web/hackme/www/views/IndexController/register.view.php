<div class="o-page o-page--center">
    <div class="o-page__card">
        <div class="c-card c-card--center">
          <span class="c-icon c-icon--large u-mb-small">
            <img src="<?php echo $this->getURL('/img/logo-small.svg'); ?>" alt="Neat">
          </span>

            <h4 class="u-mb-medium">Sign up to get started</h4>
            <form method="POST" id="ajaxform">
                <div class="c-field">
                    <label class="c-field__label">First Name</label>
                    <input class="c-input u-mb-small" name="firstname" type="text" placeholder="e.g. Adam" required>
                </div>
                <div class="c-field">
                    <label class="c-field__label">Last Name</label>
                    <input class="c-input u-mb-small" name="lastname" type="text" placeholder="e.g. Sandler" required>
                </div>

                <div class="c-field">
                    <label class="c-field__label">Email Address</label>
                    <input class="c-input u-mb-small" name="email" type="email" placeholder="e.g. adam@sandler.com" required>
                </div>

                <div class="c-field u-mb-small">
                    <label class="c-field__label">Password</label>
                    <input class="c-input" name="password" type="password" placeholder="Numbers, Pharagraphs Only" required>
                </div>

                <div class="c-field u-mb-small">
                    <label class="c-field__label">Confirm Password</label>
                    <input class="c-input" name="confirmpassword" type="password" placeholder="Rewrite your password" required>
                </div>

                <button class="c-btn c-btn--fullwidth c-btn--info">Register</button>
            </form>
        </div>
    </div>
</div>