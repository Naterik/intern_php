<?php
session_start();

spl_autoload_register(function ($class) {
  $fileName = "$class.php";

  $fileModel              = PATH_MODEL . $fileName;
  $fileControllerAdmin    = PATH_CONTROLLER_ADMIN . $fileName;

  if (is_readable($fileModel)) {
    require_once $fileModel;
  } else if (is_readable($fileControllerAdmin)) {
    require_once $fileControllerAdmin;
  }
});

require_once './configs/env.php';
require_once './configs/helper.php';


require_once './routes/admin.php';
