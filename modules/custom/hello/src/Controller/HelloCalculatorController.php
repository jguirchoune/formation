<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloCalculatorController extends ControllerBase {

  public function result($result) {
      return ['#markup' => $result];
  }

}
