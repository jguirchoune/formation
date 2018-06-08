<?php

namespace Drupal\hello\Controller;

use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase {

  public function content($param) {
    //ksm($this->currentUser());
    $message = $this->t('You are on the Hello page. Your name is %username! URL parameter is @param.', [
      '%username' => $this->currentUser()->getAccountName(),
      '@param' => $param,
    ]);
    $build = ['#markup' => $message];

    return $build;
  }

}
