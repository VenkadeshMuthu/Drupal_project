<?php
namespace Drupal\drupalup_user_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a controller for sending emails.
 */
class EmailController extends ControllerBase {

  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs the EmailController object.
   *
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager service.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   */
  public function __construct(MailManagerInterface $mail_manager, MessengerInterface $messenger) {
    $this->mailManager = $mail_manager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('messenger')
    );
  }

  /**
   * Sends an email.
   */
  public function sendEmail() {
    $module = 'drupalup_user_form';
    $key = 'custom_email'; // Identifier for this email type
    $to = 'recipient@example.com';
    $langcode = $this->currentUser()->getPreferredLangcode();
    $params['subject'] = 'Test Email Subject';
    $params['message'] = 'This is the body of the email.';
    $params['headers'] = ['Content-Type' => 'text/html'];

    // Optional: Additional headers (e.g., 'From' address)
    $params['headers']['From'] = 'sender@example.com';

    $send = TRUE; // If FALSE, the email will not actually be sent.

    // Send the email
    $result = $this->mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

    if ($result['result'] !== TRUE) {
      $this->messenger->addError($this->t('There was a problem sending your email.'));
    }
    else {
      $this->messenger->addMessage($this->t('Your email has been sent.'));
    }
  }
}
