<?php
namespace Drupal\my_drupal_tasks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Database\Database;

class TaskController extends ControllerBase {

  /**
   * Displays the tasks table.
   */
  public function displayTasks() {
    // Get the database connection
    $connection = Database::getConnection();
    $query = $connection->select('tasks', 't')
      ->fields('t')
      ->execute();

    // Prepare rows for the table
    $rows = [];
    $i = 0;

    // Loop through the tasks and build the table rows
    foreach ($query as $record) {
      // Create the Edit link
      $edit_url = Url::fromRoute('my_drupal_tasks.edit_task', ['id' => $record->id]);
      $edit_link = Link::fromTextAndUrl($this->t('Edit'), $edit_url)->toRenderable();
      $edit_link['#attributes'] = ['class' => ['btn', 'btn-success', 'btn-sm']];

      // Create the Delete link
      $delete_url = Url::fromRoute('my_drupal_tasks.delete_task', ['id' => $record->id]);
      $delete_link = Link::fromTextAndUrl($this->t('Delete'), $delete_url)->toRenderable();
      $delete_link['#attributes'] = [
        'class' => ['btn', 'btn-danger', 'btn-sm'],
        'onclick' => 'return confirm("Are you sure you want to delete this task?");',
      ];

      // Add row for this task
      $rows[] = [
        ++$i,
        $record->task_name,
        $record->description,
        $record->task_date,
        $record->status,
        $edit_link,  // Render links directly in the row
        $delete_link,
      ];
    }

    // Table header
    $header = [
      $this->t('S.No'),
      $this->t('Task Name'),
      $this->t('Description'),
      $this->t('Date'),
      $this->t('Status'),
      $this->t('Operations'),
    ];

    // Add "Add Task" button
    $add_task_url = Url::fromRoute('my_drupal_tasks.create_task');
    $add_task_link = Link::fromTextAndUrl($this->t('Add New Task'), $add_task_url)->toRenderable();
    $add_task_link['#attributes'] = ['class' => ['btn', 'btn-primary', 'btn-sm', 'mb-2']];

    // Return the render array
    return [
      'add_task_button' => [
        '#type' => 'markup',
        '#markup' => render($add_task_link), // This will render the button
      ],
      'tasks_table' => [
        '#theme' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => $this->t('No tasks found.'),
      ],
    ];
  }
}
