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
  if (!($cache = cache_get('wysiwyg_css'))) {
    // No wysiwyg profile was configured to use a css theme.
    return $hosts;
  }
  $themes = list_themes();
  foreach (array_keys($cache->data) as $theme_name) {
    $theme = $themes[$theme_name];
    if ($theme->engine == 'phptemplate') {
      require_once dirname($theme->filename) . '/template.php';
      $alter_fn = "{$theme->name}_d7csp_hosts_alter";
      if (function_exists($alter_fn)) {
        $alter_fn($hosts);
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
