<div class="o-page o-page--center">
    <div class="o-page__card">
        <div class="c-card c-card--center">
          <span class="c-icon c-icon--large u-mb-small">
            <img src="<?php echo $this->getURL("/img/logo-small.svg"); ?>" alt="Neat">
          </span>

            <h4 class="u-mb-medium">Welcome</h4>
            <form method="POST" id="ajaxform">
                <div class="c-field">
                    <label class="c-field__label">Email Address</label>
                    <input class="c-input u-mb-small" name="email" type="email" placeholder="e.g. adam@sandler.com" required>
                </div>

                <div class="c-field">
                    <label class="c-field__label">Password</label>
                    <input class="c-input u-mb-small" name="password" type="password" placeholder="Numbers, Pharagraphs Only" required>
                </div>

                <button class="c-btn c-btn--fullwidth c-btn--info">Login</button>
                <p>or</p>
                <a href="<?php echo $this->getURL('/register'); ?>" class="c-btn c-btn--fullwidth c-btn--info">Register</a>
            </form>
        </div>
    </div>
</div>