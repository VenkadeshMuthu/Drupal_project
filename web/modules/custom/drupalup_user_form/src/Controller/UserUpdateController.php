<?php
namespace Drupal\drupalup_user_form\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for updating user details.
 */
class UserUpdateController extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'drupalup_user_form_update';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        // Fetch user data by ID
        $id = \Drupal::routeMatch()->getRawParameters()->get('id');
        $connection = Database::getConnection();
        $query = $connection->select('drupalup_user_form_table', 'd')
            ->fields('d')
            ->condition('id', $id)
            ->execute();
        
        $user = $query->fetchAssoc();

        // If user not found, throw error
        if (!$user) {
            throw new NotFoundHttpException();
        }

        // Build the form
        $form['id'] = [
            '#type' => 'hidden',
            '#value' => $user['id'],
        ];
        $form['name_1'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#default_value' => $user['name_1'],
            '#required' => TRUE,
        ];
        $form['name_2'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#default_value' => $user['name_2'],
            '#required' => TRUE,
        ];
        $form['phone'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Phone'),
            '#default_value' => $user['phone'],
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#default_value' => $user['email'],
            '#required' => TRUE,
        ];
        $form['address'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Address'),
            '#default_value' => $user['address'],
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Update User'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Update user data
        $connection = Database::getConnection();
        $connection->update('drupalup_user_form_table')
            ->fields([
                'name_1' => $form_state->getValue('name_1'),
                'name_2' => $form_state->getValue('name_2'),
                'phone' => $form_state->getValue('phone'),
                'email' => $form_state->getValue('email'),
                'address' => $form_state->getValue('address'),
            ])
            ->condition('id', $form_state->getValue('id'))
            ->execute();

        // Success message
        $this->messenger()->addMessage($this->t('User updated successfully.'));
        $form_state->setRedirect('drupalup_user_form.user_data');
    }
}
