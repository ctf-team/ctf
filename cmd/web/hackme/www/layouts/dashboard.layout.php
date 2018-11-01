<!doctype html>
<html lang="en">
<?php $this->fetch("head"); ?>
<body>
<div class="o-page">
<?php
    $this->fetch('sidebar');
?>
<main class="o-page__content">
<?php
    $this->fetch('navbar');
    $this->get('content');
    $this->fetch('footer');
?>
</main>
</div>
</body>
</html>