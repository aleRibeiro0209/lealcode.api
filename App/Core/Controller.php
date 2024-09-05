<?php

namespace App\Core;

abstract class Controller {

  public \stdClass $funcionario;

  protected function getBodyRequest() {
    $json = file_get_contents('php://input');
    $obj = json_decode($json);
    return $obj ? $obj : null;
  }

  protected function getModel($model) {
    $class = '\\App\\Models\\' . ucfirst($model);
    return new $class;
  }

}