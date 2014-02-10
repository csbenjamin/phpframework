<?php

require "framework/app.php";
require "framework/bootstrap.php";

$bootstrap = new Bootstrap();
$app = new App($bootstrap);



echo $app->run();
