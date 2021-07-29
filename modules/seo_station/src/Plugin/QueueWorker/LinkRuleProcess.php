<?php

namespace Drupal\seo_station\Plugin\QueueWorkers;


/**
 * @QueueWorker(
 *   id = "link_rule_process",
 *   title = @Translation("Link Rule"),
 *   cron = {"time" = 60}
 * )
 */
class LinkRuleProcess extends \Drupal\seo_station\Airui\Plugin\QueueWorker\LinkRuleProcess {

}
