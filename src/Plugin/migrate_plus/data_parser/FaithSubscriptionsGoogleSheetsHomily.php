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
 *   id = "faith_subscriptions_google_sheets_homily",
 *   title = @Translation("Faith Subscriptions Google Sheets: Homily")
 * )
 */
class FaithSubscriptionsGoogleSheetsHomily extends GoogleSheets implements ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  protected function getSourceData(string $url): array {
    // Since we're being explicit about the data location, we can return the
    // array without calling getSourceIterator to get an iterator to find the
    // correct values.
    try {
      $google_sheets_api_url = \Drupal::config('faith_subscriptions_migrate.settings')->get('homily_sheet_url');
      $api_token = \Drupal::config('faith_subscriptions_migrate.settings')->get('api_token');
      $url = $google_sheets_api_url .'?alt=json&key='. $api_token;
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

}
