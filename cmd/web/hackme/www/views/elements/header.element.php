<p>Welcome, <a href="<?php echo $this->getURL('/user/'.$user['id']); ?>"><?php echo $this->auth->user()['email']; ?></a> - <a href="<?php echo $this->getURL("/dashboard"); ?>">Dashboard</a>, <?php
    if($this->auth->is_impersonating()) {
        ?>
        <a href="<?php echo $this->getURL('/endimpersonation'); ?>">End Impersonation</a></p>
        <?php
    }
    else {
        ?><a href="<?php echo $this->getURL('/logout'); ?>">Logout</a></p><?php
    }
