<?php

if (!function_exists('debug')) {
  function debug($data)
  {
    echo '<pre>';
    print_r($data);
    die;
  }
}


function hashPassword($password)
{
  return md5($password);
}
