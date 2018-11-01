<header class="c-navbar u-mb-medium">
    <button class="c-sidebar-toggle js-sidebar-toggle">
        <i class="feather icon-align-left"></i>
    </button>

    <h2 class="c-navbar__title">Welcome, <?php echo $user['firstname'].' '.$user['lastname']; ?></h2>
    <div class="c-dropdown dropdown">
        <div class="c-avatar c-avatar--xsmall dropdown-toggle" id="dropdownMenuAvatar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="button">
            <img class="c-avatar__img" src="../img/avatar-72.jpg" alt="Adam Sandler">
        </div>

        <div class="c-dropdown__menu has-arrow dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuAvatar">
            <a class="c-dropdown__item dropdown-item" href="<?php echo $this->getURL('/user/'.$user['id']); ?>">View Profile</a>
            <a class="c-dropdown__item dropdown-item" href="<?php echo $this->getURL('/edit/'.$user['id']); ?>">Edit Profile</a>
            <?php if($this->auth->is_impersonating()) { ?>
                <a class="c-dropdown__item dropdown-item" href="<?php echo $this->getURL('/endimpersonation'); ?>">End Impersonation</a>
            <?php } else { ?>
                <a class="c-dropdown__item dropdown-item" href="<?php echo $this->getURL('/logout'); ?>">Logout</a>
            <?php } ?>
        </div>
    </div>
</header>