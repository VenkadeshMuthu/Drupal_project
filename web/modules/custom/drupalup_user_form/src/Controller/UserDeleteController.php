<?php
namespace Drupal\drupalup_user_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

/**
 * Controller for deleting a user.
 */
class UserDeleteController extends ControllerBase {

    public function deleteUser($id) {
        // Delete the user record from the database
        $connection = Database::getConnection();
        $connection->delete('drupalup_user_form_table')
            ->condition('id', $id)
            ->execute();

        // Success message
        $this->messenger()->addMessage($this->t('User deleted successfully.'));
        return $this->redirect('drupalup_user_form.user_data');
    }
}
