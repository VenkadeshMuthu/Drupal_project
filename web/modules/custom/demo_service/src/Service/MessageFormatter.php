<?php
namespace Drupal\demo_service\Service;

class MessageFormatter {
  /**
   * Formats a message.
   *
   * @param string $message
   *   The message to format.
   * @return string
   *   The formatted message.
   */
  public function formatMessage(string $message): string {
    return strtoupper($message); // Convert message to uppercase.
  }
}
