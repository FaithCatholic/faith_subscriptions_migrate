<?php

namespace Drupal\faith_subscriptions_migrate\Plugin\migrate_plus\data_parser;

use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate_google_sheets\Plugin\migrate_plus\data_parser\GoogleSheets;
use GuzzleHttp\Exception\RequestException;

/**
 * Obtain Google Sheet data for migration.
 *
 * @DataParser(
 *   id = "faith_subscriptions_google_sheets",
 *   title = @Translation("Faith Subscriptions Google Sheets")
 * )
 */
class FaithSubscriptionsGoogleSheets extends GoogleSheets implements ContainerFactoryPluginInterface {

  /**
   * @var array
   */
  protected $headers = [];

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  protected function getSourceData(string $url): array {
    // Since we're being explicit about the data location, we can return the
    // array without calling getSourceIterator to get an iterator to find the
    // correct values.
    try {
      $api_token = \Drupal::config('faith_subscriptions_migrate.settings')->get('api_token');
      $url = str_replace('GOOGLE_SHEETS_API_KEY', $api_token, $url);
      $response = $this->getDataFetcherPlugin()->getResponseContent($url);
      // The TRUE setting means decode the response into an associative array.
      $array = json_decode($response, TRUE);

      // For Google Sheets, the actual row data lives under table->rows.
      if (isset($array['values'])) {
        // Set headers from first row.
        $first_row = array_shift($array['values']);
        $columns = $first_row;

        $this->headers = array_map(function($col) {
          return strtolower($col);
        }, $columns);

        $array = $array['values'];
      } else {
        $array = [];
      }

      return $array;
    }
    catch (RequestException $e) {
      throw new MigrateException($e->getMessage(), $e->getCode(), $e);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function fetchNextRow(): void {
    $current = $this->iterator->current();
    if ($current) {
      foreach ($this->fieldSelectors() as $field_name => $selector) {
        // Actual values are stored in c[<column index>]['v'].
        $column_index = array_search(strtolower($selector), $this->headers);
        if ($column_index >= 0 && isset($current[$column_index])) {
          $this->currentItem[$field_name] = $current[$column_index];
        } else {
          $this->currentItem[$field_name] = '';
        }
      }
      $this->iterator->next();
    }
  }

}
