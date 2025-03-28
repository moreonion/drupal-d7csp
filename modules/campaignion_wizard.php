<?php

/**
 * @file
 * Implement hooks on behalf of the campaignion_wizard module.
 */

/**
 * Implements hook_d7csp_hosts().
 */
function campaignion_wizard_d7csp_hosts() {
  if (!path_is_admin($_GET['q'])) {
    return [];
  }
  $hosts['font-src'][] = 'data:';
  return $hosts;
}
