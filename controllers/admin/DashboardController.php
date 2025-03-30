<?php

class DashboardController
{
  private $letterModel;

  public function __construct()
  {
    $this->letterModel = new Letter();
  }
  public function index()
  {
    $letters = $this->letterModel->getAllLetters(30);
    require_once PATH_VIEW_ADMIN . 'dashboard.php';
  }
}
