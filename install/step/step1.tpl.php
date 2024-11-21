<?php include PATHS.'step/header.tpl.php';?>

<div class="container">

<div class="col-md-12">
    <div class="card mb-4 o-hidden">
        <img class="card-img-top" src="bg-2.jpg" alt="">
        <div class="card-body">
            <h5 class="card-title">
                安装许可协议
            </h5>
            <p class="card-text">
                <?php echo format_textarea($license)?>
            </p>
        </div>
        
        <div class="card-body">
            <a href="#" class="card-link">
                网站模式系统安装
            </a>
            <a href="#" class="card-link">
                API模式安装
            </a>
        </div>
    </div>
</div>
</div>

<?php include PATHS.'step/bottom.tpl.php';?>