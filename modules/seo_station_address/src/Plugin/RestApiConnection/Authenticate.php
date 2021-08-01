<?php
namespace Drupal\seo_station_address\Plugin\RestApiConnection;

use Drupal\api_connection\Plugin\RestApiConnectionBase;
use Drupal\Component\Serialization\Json;
use GuzzleHttp\RequestOptions;

/**
 * REST API connection for the ReqRes example service.
 *
 * @RestApiConnection(
 *   id = "airui_authenticate",
 *   label = @Translation("Airui Authentication"),
 *   urls = {
 *     "dev" = "http://192.168.1.69:8081",
 *     "test" = "http://192.168.1.69:8081",
 *     "live" = "http://192.168.1.69:8081"
 *   }
 * )
 */
class Authenticate extends \Drupal\seo_station_address\Airui\Plugin\RestApiConnection\Authenticate { }
