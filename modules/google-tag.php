<?php

/**
 * @file
 * Implement hooks on behalf of the google_tag module.
 */

/**
 * Implements hook_d7csp_hosts().
 */
function google_tag_d7csp_hosts() {
  $hosts['connect-src'][] = 'https://www.google.com';
  $hosts['connect-src'][] = '*.google-analytics.com';
  $hosts['frame-src'][] = 'https://www.googletagmanager.com';
  $hosts['img-src'][] = 'https://www.google-analytics.com';
  $hosts['img-src'][] = 'https://www.googletagmanager.com';
  $hosts['script-src'][] = 'https://www.googletagmanager.com';

  return $hosts;
}
