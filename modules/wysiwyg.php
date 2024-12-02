<?php

/**
 * @file
 * Hook implementations for the wysiwyg module.
 */

/**
 * Implements hook_d7csp_hosts().
 */
function wysiwyg_d7csp_hosts() {
  $hosts = [];
  wysiwyg_initialize_cache();
  $cache = cache_get('wysiwyg_css');
  foreach (list_themes() as $theme) {
    foreach (($cache->data[$theme->name]['files'] ?? []) as $url) {
      $parts = parse_url($url);
      if ($parts['host'] && $parts['host'] != $_SERVER['HTTP_HOST']) {
        $hosts['style-src'][] = $parts['scheme'] . '://' . $parts['host'];
        $hosts['font-src'][] = $parts['scheme'] . '://' . $parts['host'];
        $hosts['img-src'][] = $parts['scheme'] . '://' . $parts['host'];
      }
    }
  }
  // Needed for ckeditor 4.
  $hosts['script-src-attr'][] = "'unsafe-inline'";
  return $hosts;
}

/**
 * Helper function to initialize the CSS cache.
 */
function wysiwyg_initialize_cache() {
  $themes = array_unique(array_filter(array_map(function ($profile)  {
    return $profile->settings['css_theme'] ?? '';
  }, wysiwyg_profile_load_all())));
  foreach ($themes as $theme) {
    $css = wysiwyg_get_css($theme);
    if (count($css) === 1 && $url = $css[0]) {
      if (strpos(parse_url($url, PHP_URL_PATH), '/wysiwyg_theme/') === 0) {
        drupal_http_request($url);
      }
    }
  }
}
