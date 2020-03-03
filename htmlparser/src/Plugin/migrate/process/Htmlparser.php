<?php

namespace Drupal\htmlparser\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\Component\Utility;
use Drupal\Component\Utility\Html;

/**
 * Provides a 'Htmlparser' migrate process plugin.
 *
 * @MigrateProcessPlugin(
 *  id = "htmlparser"
 * )
 */
class Htmlparser extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$value) {
      throw new MigrateException('Input must not be null or empty.');
    }
    if (is_array($value)) {
      throw new MigrateException('Input must not be an array.');
    }
    if (!file_exists($value)) {
      throw new MigrateException('File ' . $value . ' not found.');
    }

    $html = file_get_contents($value);

    return $this->getBody($html);
  }

  /**
   * Tries to get Body element from HTML.
   *
   * @param string $html
   *   The HTML as string.
   *
   * @return string
   *   Clean Body content.
   */
  protected function getBody($html) {
    $innerHTML = '';
    $dom = new \DOMDocument();
    @$dom
    ->loadHTML($html);
    $elem = $dom->getElementsByTagName('body');

    for ($i = 0; $i < $elem->length; $i++) {
      $entry = $elem->item($i);
      $children = $entry->childNodes;
      foreach ($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML($child);
      }
    }

    $parsedHTML = $this->fixEncoding($innerHTML);

    return $parsedHTML;
  }

  /**
   * Tries to fix Encoding issues.
   *
   * @param string $html
   *   The Body content as String.
   *
   * @return string
   *   Clean Body content.
   */
  protected function fixEncoding($html) {
    // Fix Encoding issues
    $enc = mb_detect_encoding($html, 'UTF-8', TRUE);
    if (!$enc) {
      $html = mb_convert_encoding($html, 'UTF-8', 'WINDOWS-1252');
    }

    return $html;
  }

}

