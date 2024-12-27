<?php

namespace Drupal\drupalup_user_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Database\Database;

class UserReadController extends ControllerBase {

  /**
   * Displays a list of users with edit and delete links, and an "Add New User" button.
   */
  public function displayUsers() {
    $connection = Database::getConnection();
    $query = $connection->select('drupalup_user_form_table', 'd')
      ->fields('d')
      ->execute();
    
    $rows = [];
    $i=0;
    // Loop through the result and prepare the rows for display
    foreach ($query as $record) {
      // Edit button
      $edit_url = Url::fromRoute('drupalup_user_form.update_user', ['id' => $record->id]);
      $edit_link = Link::fromTextAndUrl('Edit', $edit_url)->toRenderable();
      $edit_link['#attributes'] = ['class' => ['btn', 'btn-success', 'btn-sm']]; // Green button

      // Delete button with confirmation dialog
      $delete_url = Url::fromRoute('drupalup_user_form.delete_user', ['id' => $record->id]);
      $delete_link = Link::fromTextAndUrl('Delete', $delete_url)->toRenderable();
      $delete_link['#attributes'] = [
        'class' => ['btn', 'btn-danger', 'btn-sm'], // Red button
        'onclick' => 'return confirm("Are you sure you want to delete this user?");',
      ];

      // Add row data
      $rows[] = [
        ++$i,
        $record->name_1,
        $record->name_2,
        $record->phone,
        $record->email,
        $record->address,
        render($edit_link),
        render($delete_link),
      ];
    }

    // Add the "Add New User" button above the table
    $add_user_url = Url::fromRoute('drupalup_user_form.create_user');
    $add_user_button = Link::fromTextAndUrl('Add New User', $add_user_url)->toRenderable();
    $add_user_button['#attributes'] = ['class' => ['btn', 'btn-primary', 'btn-sm']]; // Blue button

    // Initialize the build array
    $build = [];

    // Add the button as a separate renderable array element
    $build['add_user_button'] = [
      '#markup' => render($add_user_button),
    ];

    // Attach the CSS library for styling
    $build['#attached']['library'][] = 'drupalup_user_form/drupalup_user_form_styles';

    // Define the table headers
    $header = [
      'S.No',
      'First name',
      'Last name',
      'Phone',
      'Email',
      'Address',
      [
        'data' => 'Operations',
        'colspan' => 2, // Spanning two columns for Edit and Delete buttons
        'class' => ['text-center'],
      ],
    ];

    // Add the table to the renderable array
    $build['user_table'] = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No users found'),
    ];

    return $build;
  }
}
