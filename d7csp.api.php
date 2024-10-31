<?php

/**
 * @file
 * Document hooks invoked by the d7csp module.
 */

 /**
  * Provide hosts that need allowlisting in the CSP headers.
  *
  * @return string[][]
  *   Arrays of hosts grouped by CSP directive.
  */
function hook_d7csp_hosts(): array {
  $hosts['script-src'][] = 'cdn.example.com';
  return $hosts;
}

/**
 * Alter the allowed hosts in the CSP headers.
 *
 * @param string[][] $hosts
 *   Arrays of hosts grouped by CSP directive.
 */
function hook_d7csp_hosts_alter(array &$hosts) {
  $hosts['script-src'][] = 'cdn2.example.net';
}
