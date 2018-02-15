<?php

/**
 * @file
 * $Id$.
 */

/**
 * Generate markup for marinelli's banners.
 *
 * @return string
 *   HTML markup to printo into page
 */
use Drupal\Component\Utility\Unicode;

function marinelli_banners_markup($banners) {
  if ($banners && theme_get_setting('banner_usage') != 0) {
    // Add javascript to manage banners.
    marinelli_banners_add_js(count($banners));

    // Generate HTML markup for banners.
    return marinelli_banner_markup($banners);
  }
  else {
    return '';
  }
}

/**
 * Add JS required to manage banner into this page.
 *
 * @param int $num_banners
 *   Number of banner visible into this page.
 */
function marinelli_banners_add_js($num_banners) {
  // In this two cases we do not need cycle, we use a small script to fill banner text
  // banners match only url.
  if ($num_banners <= 1) {
    $num_banners['#attached']['library'][] = 'marinelli/marinelli-banner-text-js';
    // drupal_add_js(path_to_theme() . '/js/banner/marinelli_banner_text.js');
  }
  // Adds cycle passing some parameters.
  else {
    // Add the required javascript.
    $num_banners['#attached']['library'][] = 'marinelli/marinelli-banner-cycle-js';
    // $vars['#attached']['library'][] = 'marinelli/marinelli-banner-text-js';
    // drupal_add_js(path_to_theme() . '/js/cycle/cycle.js');
    // drupal_add_js(path_to_theme() . '/js/banner/marinelli_configure_cycle.js');

    // Pass the text variables to javascript.
    drupal_add_js(
      [
        'marinelli' => [
          'banner_effect' => theme_get_setting('banner_effect'),
          'banner_speed'  => theme_get_setting('banner_speed'),
          'banner_delay'  => theme_get_setting('banner_delay'),
          'banner_pause'  => theme_get_setting('banner_pause') == 1 ? 1 : 0,
        ],
      ],
      ['type' => 'setting']
    );
  }
}

/**
 * Generate banners markup.
 *
 * @return string
 *   HTML code to display banner markup.
 */
function marinelli_banner_markup($banners) {
  $output = '';

  foreach ($banners as $i => $banner) {
    $variables = [
      'path'  => $banner['image_path'],
      'alt' => t('@image_desc', ['@image_desc' => $banner['image_description']]),
      'title'   => t('@image_title', ['@image_title' => $banner['image_title']]),
      'attributes' => [
    // Hide all the slides except #1.
        'class' => 'slide' . ($i != 0 ? ' marinelli-hide-no-js' : ''),
        'id' => 'slide-number-' . $i,
        'longdesc' => t('@image_desc', ['@image_desc' => $banner['image_description']]),
      ],
    ];
    // Draw image.
    $image = theme('image', $variables);

    // Remove link if is the same page.
    $banner['image_url'] = ($banner['image_url'] == current_path()) ? FALSE : $banner['image_url'];

    // Add link (if required)
    $output .= $banner['image_url'] ? l($image, $banner['image_url'], ['html' => TRUE]) : $image;
  }
  return $output;
}

/**
 * Get banner to show into current page in accord with settings.
 *
 * @return array
 *   Banners to show
 */
function marinelli_show_banners() {
  $banners = marinelli_get_banners(FALSE);
  $display_banners = [];

  // Current path alias.
  $path = Unicode::strtolower(\Drupal::service('path.current')->getPath());

  // Check visibility for each banner.
  foreach ($banners as $banner) {
    // Pages.
    $pages = Unicode::strtolower($banner['image_visibility']);

    // Check path for alias, and (if required) for path.
    $page_match = drupal_match_path($path, $pages);
    if ($path != $_GET['q']) {
      $page_match = $page_match || \Drupal::service('path.current')->getPath($pages);
    }

    // Add banner to visible banner.
    if ($page_match) {
      $display_banners[] = $banner;
    }
  }
  return $display_banners;
}
