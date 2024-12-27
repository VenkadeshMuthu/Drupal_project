<?php

namespace Drupal\weather_module\Service;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Cache\CacheBackendInterface;

/**
 * Service to fetch weather data from an external API.
 */
class WeatherService {

  /**
   * HTTP client for making API requests.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * Cache backend for caching API responses.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cacheBackend;

  /**
   * Constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   The HTTP client.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   */
  public function __construct(ClientInterface $http_client, CacheBackendInterface $cache_backend) {
    $this->httpClient = $http_client;
    $this->cacheBackend = $cache_backend;
  }

  /**
   * Fetches weather data for a given city with caching.
   *
   * @param string $city
   *   The name of the city.
   *
   * @return array
   *   The weather data or an error message.
   */
  public function getWeather($city) {
    $cid = 'weather_data:' . $city; // Cache ID.
    $cache = $this->cacheBackend->get($cid);
  
    // Return cached data if available.
    if ($cache) {
      return $cache->data;
    }
  
    try {
      // API endpoint and key (replace with your API key).
      $api_url = 'https://api.openweathermap.org/data/2.5/weather';
      $api_key = 'ffb033cce085240f97c66b2bc388b5bf'; // Replace with your API key.
  
      // Make the API request.
      $response = $this->httpClient->request('GET', $api_url, [
        'query' => [
          'q' => $city,
          'appid' => $api_key,
          'units' => 'metric',
        ],
      ]);
  
      $data = json_decode($response->getBody()->getContents(), TRUE);
  
      // Process and cache the response.
      if (!empty($data)) {
        $this->cacheBackend->set($cid, $data, time() + 3600); // Cache for 1 hour.
        return $data;
      }
      else {
        return ['error' => 'No weather data found.'];
      }
    }
    catch (\Exception $e) {
      return ['error' => 'Failed to fetch weather data: ' . $e->getMessage()];
    }
  }
  

  /**
   * Clears cached weather data for a specific city.
   *
   * @param string $city
   *   The city whose cache needs to be cleared.
   */
  public function clearWeatherCache($city) {
    $cid = 'weather_data:' . $city;
    $this->cacheBackend->delete($cid);
  }
}
