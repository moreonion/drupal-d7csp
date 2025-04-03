<?php

/**
 * @file
 * Hook implementations for the wysiwyg module.
 */

/**
 * Get base tthe
 * @param mixed $theme
 * @return void
 */
function _d7csp_get_base_themes(string $theme): array {
  $themes = list_themes();
  $result = [$theme];
  while ($base_theme = $themes[$theme]->base_theme ?? NULL) {
    $theme = $base_theme;
    $result[] = $theme;
  }
  return array_reverse($result);
}

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
    $GLOBALS['theme_key'] = $theme_name;
    foreach (_d7csp_get_base_themes($theme_name) as $base_theme) {
      $theme = $themes[$base_theme];
      if (file_exists($template_php = dirname($theme->filename) . '/template.php')) {
        require_once $template_php;
        $alter_fn = "{$theme->name}_d7csp_hosts_alter";
        if (function_exists($alter_fn)) {
          $alter_fn($hosts);
        }
      }
    }
  }
  $GLOBALS['theme_key'] = $backup_active_theme;
  return $hosts;
}
