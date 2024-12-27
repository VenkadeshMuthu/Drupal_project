<?php

namespace Drupal\weather_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\weather_module\Service\WeatherService;

/**
 * Controller to display weather data.
 */
class WeatherController extends ControllerBase {

  /**
   * Weather service instance.
   *
   * @var \Drupal\weather_module\Service\WeatherService
   */
  protected $weatherService;

  /**
   * Constructs the controller.
   *
   * @param \Drupal\weather_module\Service\WeatherService $weather_service
   *   The weather service.
   */
  public function __construct(WeatherService $weather_service) {
    $this->weatherService = $weather_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('weather_module.weather_service')
    );
  }

  /**
   * Returns weather data for a given city.
   */
  public function showWeather($city = 'London') {
    $weather_data = $this->weatherService->getWeather($city);

    if (isset($weather_data['error'])) {
      return ['#markup' => $this->t('Error: @message', ['@message' => $weather_data['error']])];
    }

    return [
      '#theme' => 'item_list',
      '#items' => [
        'City: ' . $weather_data['name'],
        'Temperature: ' . $weather_data['main']['temp'] . ' °C',
        'Weather: ' . $weather_data['weather'][0]['description'],
      ],
    ];
  }
}
