<?php
echo "<pre>";
// print_r($_SERVER);
// return;


require "framework/app.php";
require "framework/bootstrap.php";

$bootstrap = new Bootstrap();
$app = new App($bootstrap);



echo $app->run();
