<?php

namespace Drupal\faith_subscriptions_migrate\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'faith_subscriptions_migrate.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'faith_subscriptions_migrate_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('faith_subscriptions_migrate.settings');

    $form['api_token'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Google Sheets API token'),
      '#default_value' => $config->get('api_token') ? $config->get('api_token') : '',
      '#description' => $this->t('Paste the Google Sheets API token here.'),
      '#disabled' => TRUE,
      '#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    $values = $form_state->getValues();

    foreach ($values as $key => $value) {
      $this->config('faith_subscriptions_migrate.settings')->set($key, $value);
    }

    $this->config('faith_subscriptions_migrate.settings')->save();
  }

}
