<nav class="navbar bg-success">
    <a href="index.php"><h2 class="text-white"><img src="./img/logo.png" height="150px" alt="">台北101接駁專車</h2></a>
    <div class="nav navbar end">
        <a href="admin.php"><h3 class="text-white">系統管理</h3></a>
<?php if(isset($_SESSION['username'])):?>
        <a href="./api/loginout.php"><h3 class="text-white">&ensp;登出</h3></a>
        <?php endif;?>
    </div>
</nav>