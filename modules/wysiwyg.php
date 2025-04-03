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
  $css_themes = array_map(function ($profile)  {
    return $profile->settings['css_theme'] ?? '';
  }, wysiwyg_profile_load_all());
  $css_themes[] = variable_get('admin_theme');
  $css_themes[] = variable_get('theme_default', 'bartik');
  $css_themes = array_unique(array_filter($css_themes));
  $backup_active_theme = $GLOBALS['theme_key'];
  foreach ($css_themes as $theme_name) {
    $theme = $themes[$theme_name];
    if (file_exists($template_php = dirname($theme->filename) . '/template.php')) {
      require_once $template_php;
      $alter_fn = "{$theme->name}_d7csp_hosts_alter";
      if (function_exists($alter_fn)) {
        $GLOBALS['theme_key'] = $theme;
        $alter_fn($hosts);
      }
    }
  }
  $GLOBALS['theme_key'] = $backup_active_theme;
  return $hosts;
}
