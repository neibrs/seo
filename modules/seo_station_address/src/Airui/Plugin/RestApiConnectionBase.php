<?php

namespace Drupal\seo_station_address\Airui\Plugin;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Http\ClientFactory;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\api_connection\RestApiEnvironmentUrlException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for REST API connection plugins.
 */
abstract class RestApiConnectionBase extends \Drupal\api_connection\Plugin\RestApiConnectionBase {

  /**
   * {@inheritdoc}
   */
  public function sendRequest($endpoint, $method, array $options = []) {
    if ((bool) $this->pluginDefinition['activated'] !== TRUE) {
      return FALSE;
    }

    if (isset($options[RequestOptions::BODY])) {
      $options[RequestOptions::BODY] = Json::encode($options[RequestOptions::BODY]);
    }

    if (!isset($options[RequestOptions::HEADERS])) {
      $options[RequestOptions::HEADERS] = [];
    }
    if (!isset($options[RequestOptions::HEADERS]['Content-Type'])) {
      $options[RequestOptions::HEADERS]['Content-Type'] = 'application/json';
    }

    $current_environment = $this->getEnvironment();
    if (!isset($this->pluginDefinition['urls'][$current_environment])) {
      throw new RestApiEnvironmentUrlException($current_environment);
    }
//    $base_url = $this->pluginDefinition['urls'][$current_environment];
    $base_url = 'http://api.airuidata.com';

    if ($response = $this->handleRequest($method, $base_url . '/' . $endpoint, $options)) {
      $body = (string) $response->getBody();
      if (!$response->getStatusCode() == 200) {
        $this->logger->error($body);
      }
      $body = Json::decode($body);
      if (empty($body)) {
        // If endpoint returns no body, but we got a 200 status code, return
        // TRUE, to indicate request was successful.
        return TRUE;
      }
      return $body;
    }

    return FALSE;
  }

  /**
   * Send the request to the API.
   *
   * @param string $method
   *   The request method.
   * @param string $endpoint_url
   *   The full API endpoint URL.
   * @param array $options
   *   Options for the request i.e body, header, ...
   *
   * @return bool|mixed|\Psr\Http\Message\ResponseInterface
   *   The response object.
   */
  protected function handleRequest($method, $endpoint_url, array $options) {
    // Try to send the request to the API.
    try {
      return $this->client->request($method, $endpoint_url, $options);
    }
    catch (GuzzleException $e) {
      $this->logger->error($e->getMessage());
    }
    return FALSE;
  }

  /**
   * Create HandlerStack for Guzzle, to log requests and responses separately.
   *
   * @param array $message_formats
   *   Message formats to log.
   *
   * @return \GuzzleHttp\HandlerStack
   *   The Guzzle handler stack.
   */
  protected function createLoggingHandlerStack(array $message_formats) {
    $stack = HandlerStack::create();

    foreach ($message_formats as $message_format) {
      // We'll use unshift instead of push, to add the middleware to the bottom
      // of the stack, not the top.
      $stack->unshift(
        $this->createGuzzleLoggingMiddleware($message_format)
      );
    }

    return $stack;
  }

  /**
   * Create Guzzle middleware, to log requests to Drupal logger.
   *
   * @param string $message_format
   *   The message format to format log messages in.
   *
   * @return callable
   *   The callable logging middleware.
   */
  protected function createGuzzleLoggingMiddleware($message_format) {
    return Middleware::log(
      $this->logger,
      new MessageFormatter($message_format)
    );
  }

}
