<?php

/**
 * @file
 * Implement hooks on behalf of the recaptcha module.
 */

/**
 * Implements hook_d7csp_hosts().
 */
function recaptcha_d7csp_hosts() {
  $hosts['connect-src'][] = 'https://www.google.com';
  $hosts['frame-src'][] = 'https://www.google.com';
  return $hosts;
}
