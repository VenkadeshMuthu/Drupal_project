<?php

namespace Drupal\test_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class TestModuleController extends ControllerBase {
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This is the Test Module page.'),
    ];
  }
}
