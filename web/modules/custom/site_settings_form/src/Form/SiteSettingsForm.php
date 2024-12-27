<?php
namespace Drupal\site_settings_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SiteSettingsForm extends ConfigFormBase {
  
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['module_name.site_settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_settings_form';
  }

  /**
   * Build the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('module_name.site_settings');

    $form['site_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site Name'),
      '#default_value' => $config->get('site_name'),
      '#description' => $this->t('Enter the name of the site.'),
    ];

    $form['site_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Site Email'),
      '#default_value' => $config->get('site_email'),
      '#description' => $this->t('Enter the site contact email.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('module_name.site_settings')
      ->set('site_name', $form_state->getValue('site_name'))
      ->set('site_email', $form_state->getValue('site_email'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
