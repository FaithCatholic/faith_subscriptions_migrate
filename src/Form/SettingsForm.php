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
      '#description' => $this->t('Paste the Google Sheets API token in the appropriate settings.php file.'),
      '#disabled' => TRUE,
      '#required' => TRUE,
    );

    $form['commentary_sheet_url'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Commentary sheet url'),
      '#default_value' => $config->get('commentary_sheet_url') ? $config->get('commentary_sheet_url') : '',
      '#description' => $this->t('Enter the url for Commentary sheet at sheets.googleapis.com. Do not include the "key" parameter.'),
      '#placeholder' => $this->t('https://sheets.googleapis.com/v4/spreadsheets/SPREADSHEET_ID/values/SHEET_NAME'),
    );

    $form['commentary_sheet_es_url'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Commentary (Spanish) sheet url'),
      '#default_value' => $config->get('commentary_sheet_es_url') ? $config->get('commentary_sheet_es_url') : '',
      '#description' => $this->t('Enter the url for Commentary (es) sheet at sheets.googleapis.com. Do not include the "key" parameter.'),
      '#placeholder' => $this->t('https://sheets.googleapis.com/v4/spreadsheets/SPREADSHEET_ID/values/SHEET_NAME'),
    );

    $form['growgo_sheet_url'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Grow+Go sheet url'),
      '#default_value' => $config->get('growgo_sheet_url') ? $config->get('growgo_sheet_url') : '',
      '#description' => $this->t('Enter the url for Grow+Go sheet at sheets.googleapis.com. Do not include the "key" parameter.'),
      '#placeholder' => $this->t('https://sheets.googleapis.com/v4/spreadsheets/SPREADSHEET_ID/values/SHEET_NAME'),
    );

    $form['homily_sheet_url'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Homily sheet url'),
      '#default_value' => $config->get('homily_sheet_url') ? $config->get('homily_sheet_url') : '',
      '#description' => $this->t('Enter the url for Homily sheet at sheets.googleapis.com. Do not include the "key" parameter.'),
      '#placeholder' => $this->t('https://sheets.googleapis.com/v4/spreadsheets/SPREADSHEET_ID/values/SHEET_NAME'),
    );

    $form['lectionary_sheet_url'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Lectionary sheet url'),
      '#default_value' => $config->get('lectionary_sheet_url') ? $config->get('lectionary_sheet_url') : '',
      '#description' => $this->t('Enter the url for Lectionary sheet at sheets.googleapis.com. Do not include the "key" parameter.'),
      '#placeholder' => $this->t('https://sheets.googleapis.com/v4/spreadsheets/SPREADSHEET_ID/values/SHEET_NAME'),
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
