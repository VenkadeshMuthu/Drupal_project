<?php
namespace Drupal\demo_service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\demo_service\Service\MessageFormatter;

class DemoController extends ControllerBase {
  /**
   * The message formatter service.
   *
   * @var \Drupal\demo_service\Service\MessageFormatter
   */
  protected $messageFormatter;

  /**
   * Constructor.
   *
   * @param \Drupal\demo_service\Service\MessageFormatter $message_formatter
   *   The message formatter service.
   * 
   * Purpose: The constructor receives the MessageFormatter service instance and assigns it to the $messageFormatter property.
   * This is part of Dependency Injection: the service is "injected" into the class instead of being hard-coded.
   */
  public function __construct(MessageFormatter $message_formatter) {
    $this->messageFormatter = $message_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('demo_service.message_formatter')
    );
  }

  /**
   * A demo page that uses the service.
   */
  public function demoPage() {
    $message = 'Hello, this is a demo of dependency injection!';
    $formatted_message = $this->messageFormatter->formatMessage($message);

    return [
      '#markup' => $this->t('Formatted Message: @message', ['@message' => $formatted_message]),
    ];
  }
}
