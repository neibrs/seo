<?php

namespace Drupal\seo_textdata\Plugin\QueueWorker;

/**
 * @QueueWorker(
 *   id = "typename_process",
 *   title = @Translation("Link Rule"),
 *   cron = {"time" = 60}
 * )
 */
class TypenameProcess extends \Drupal\seo_textdata\Airui\Plugin\QueueWorker\TypenameProcess {

}
