<?php
namespace Drupal\drupalup_user_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'My Custom Block' block.
 *
 * @Block(
 *   id = "my_custom_block",
 *   admin_label = @Translation("My Custom Block"),
 *   category = @Translation("Custom")
 * )
 */
class MyCustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Return the block content.
    return [
      '#markup' => $this->t('This is my custom block content.'),
    ];
  }
}
