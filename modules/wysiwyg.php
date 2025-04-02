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
  // Needed for ckeditor 4.
  $hosts['script-src-attr'][] = "'unsafe-inline'";
  $themes = list_themes();
  $css_themes = array_unique(array_filter(array_map(function ($profile)  {
    return $profile->settings['css_theme'] ?? '';
  }, wysiwyg_profile_load_all())));
  foreach ($css_themes as $theme_name) {
    $theme = $themes[$theme_name];
    if ($theme->engine == 'phptemplate') {
      require_once dirname($theme->filename) . '/template.php';
      $alter_fn = "{$theme->name}_d7csp_hosts_alter";
      if (function_exists($alter_fn)) {
        $alter_fn($hosts);
      }
    }
  }
  return $hosts;
}
