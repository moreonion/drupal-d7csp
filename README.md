# CSP headers for Drupal 7

The module tries to make it feasible to use CSP headers with Drupal 7 sites. It builds upon
[seckit](https://drupal.org/project/seckit) and enhances it in a few ways:

- It generates a nonce value in each (non-AJAX) request and adds it to all script tags.
- It provides a hook `hook_d7csp()` that allows other modules to specify additional CSP header
  requirements. d7csp then takes care of merging all these requirements and passing them to seckit.


## How to use this module

- Download, install and enable the module and its dependencies
  [as usual für Drupal 7](https://www.drupal.org/docs/7/extend/installing-modules).
- Navigate to `/admin/config/system/seckit` and activate ‘Enable Content Security Policy’. The
  nonces are created automatically and most of your site should work this way.
- Test your site and look at the browser console to identify CSP related errors. Either add the
  needed directive to the seckit configuration or to an implementation of `hook_d7csp_hosts()`.
  You will need additional CSP directives for other assets (styles, images, frames, …), API requests
  and additional scripts loaded through JavaScript.


## Limitations and workarounds


### jQuery 1

jQuery 1 uses `eval()` in some cases. An upgrade to jQuery 3 (with [jQuery migrate](https://drupal.org/project/jquery_update)) fixes this.


### JavaScript loaded in AJAX requests

When loading additional JS in AJAX requests Drupal’s `ajax.js` just drops them in the DOM. This way
they count as inline scripts and lead to CSP errors. In order to authenticate them with a nonce as
well you have to apply a patch [form the Drupal issue #3486305](https://www.drupal.org/project/drupal/issues/3486305).
