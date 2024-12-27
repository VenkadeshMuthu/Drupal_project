<?php
namespace Drupal\drupalup_user_form\Controller;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Controller for creating a new user.
 */
class UserCreateController extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'drupalup_user_form_create';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['name_1'] = [
            '#type' => 'textfield',
            '#title' => $this->t('First Name'),
            '#required' => TRUE,
        ];
        $form['name_2'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Last Name'),
            '#required' => TRUE,
        ];
        $form['phone'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Phone'),
        ];
        $form['email'] = [
            '#type' => 'email',
            '#title' => $this->t('Email'),
            '#required' => TRUE,
        ];
        $form['address'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Address'),
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Create User'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Insert new user record into the database
        $connection = Database::getConnection();
        $connection->insert('drupalup_user_form_table')
            ->fields([
                'name_1' => $form_state->getValue('name_1'),
                'name_2' => $form_state->getValue('name_2'),
                'phone' => $form_state->getValue('phone'),
                'email' => $form_state->getValue('email'),
                'address' => $form_state->getValue('address'),
            ])
            ->execute();

        // Success message
        $this->messenger()->addMessage($this->t('User created successfully.'));
        $form_state->setRedirect('drupalup_user_form.user_data');  // Redirect after submission
    }
}
