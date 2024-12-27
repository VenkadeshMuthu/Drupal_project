<?php

namespace Drupal\drupalup_user_form\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Custom page controller.
 */
class CustomPageController extends ControllerBase {

  /**
   * Returns the custom page.
   */
  public function customPage() {
    return [
      '#theme' => 'custom_page_template',
      // '#content' => $this->t('This is an example of using hook_theme with Twig templates in Drupal 9.'),
    ];
  }

}
