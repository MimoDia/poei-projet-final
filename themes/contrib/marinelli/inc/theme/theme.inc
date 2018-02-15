<?php

/**
 * @file
 * $Id$.
 */

/**
 * Overrides of theme implementations.
 */
function marinelli_theme() {
  return array(
  // Function that renders classic primary menu with <h2>.
    'custom_links' => array(
      'variables' => ['links' => NULL, 'attributes' => NULL, 'heading' => NULL],
    ),
    'mega_menu' => array(
      'variables' => ['menu' => NULL],
    ),
    'mbanner_text' => array(
      // 'variables' => array('text' => NULL),.
    ),
    'mbanner_nav'  => array(
      'variables' => ['prev' => NULL, 'next' => NULL],
    ),
  );
}

/**
 * Custom primary menu with <h2> for each item.
 */
function marinelli_custom_links($variables) {
  global $language_url;
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(['class' => $heading['class']]);
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = [$key];

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(['class' => $class]) . '><h2>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</h2></li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}

/**
 * Mega drop down primary links.
 *
 * Param <array> $menu
 *   Full array of main menu.
 *
 * return string
 *   Html with mega menu to printo into page
 */
function marinelli_mega_menu($variables) {
  drupal_add_js(path_to_theme() . '/js/hoverintent/hoverintent.js');
  drupal_add_js(path_to_theme() . '/js/menu/marinelli_menu.js');

  $menu = $variables['menu'];
  $alt = theme_get_setting('menu_alt_class');
  // Open list.
  $output = '<ul class="megamenu-1">';

  $count_main_links = 1;

  foreach ($menu as $key => $value) {
    // Check if the link is hidden.
    if ($value['link']['hidden'] != 1) {
      // Give an unique id for better styling.
      $id = 'menu-main-title-' . $value['link']['mlid'];
      $options = [];
      if (isset($value['link']['options']['attributes']['title'])) {
        $options = ['attributes' => ['title' => $value['link']['options']['attributes']['title']]];
      }

      // First level markup (li or h2)
      if (theme_get_setting('menu_headings') == 1) {
        $output .= '<li class="megamenu-li-first-level" id="' . $id . '">' . l($value['link']['link_title'], $value['link']['href'], $options);
      }
      elseif (theme_get_setting('menu_headings') == 2) {
        // Use <h2>, according to http://drupal.org/node/561750
        $output .= '<li class="megamenu-li-first-level" id="' . $id . '"><h2>' . l($value['link']['link_title'], $value['link']['href'], $options) . '</h2>';
      }

      $class = "";
      $altclass = "";

      // Add the alt class based on theme settings.
      if (in_array($count_main_links, $alt)) {
        $altclass = " alt";
      }

      // Choose mega class (div width based on the numbers of columns)
      switch (count($value['below'])) {
        case 1:
          $class = 'one-col' . $altclass;
          break;

        case 2:
          $class = 'two-col' . $altclass;
          break;

        case 3:
          $class = 'three-col' . $altclass;
          break;

        case 4:
          $class = 'four-col' . $altclass;
          break;

        case 5:
          $class = 'five-col' . $altclass;
          break;

        case 6:
          $class = 'six-col' . $altclass;
          break;
      }

      // Check if we have children.
      if (count($value['below']) > 0) {
        // Open div mega.
        $output .= '<div class="mega ' . $class . '">';
        // Open div megamenuWrapper.
        $output .= '<div class="megamenuWrapper">';

        foreach ($value['below'] as $key2 => $value2) {
          if ($value2['below']) {
            // Open div menusection.
            $output .= '<div class="menu-section">';
          }

          // Give an unique id for better styling.
          $id = 'menu-section-title-' . $value2['link']['mlid'];
          $options = ['class' => ['menu-section-link']];
          if (isset($value2['link']['options']['attributes']['title'])) {
            $options['attributes'] = ['title' => $value2['link']['options']['attributes']['title']];
          }

          // && $value2['below']) { // use a list.
          if (theme_get_setting('menu_headings') == 1) {
            // Open section list.
            $output .= '<ul class="megamenu-section">';

            // Check if the link is hidden.
            if ($value2['link']['hidden'] != 1) {
              $output .= '<li class="menu-section-title" id="' . $id . '">' . l($value2['link']['link_title'], $value2['link']['href'], $options);
            }
          }
          // && $value2['below']) { // use <h3>.
          elseif (theme_get_setting('menu_headings') == 2) {
            // Check if the link is hidden.
            if ($value2['link']['hidden'] != 1) {
              $output .= '<h3>' . l($value2['link']['link_title'], $value2['link']['href'], $options) . '</h3>';
            }
          }

          if ($value2['below']) {
            // Open 2nd level list.
            $output .= '<ul class="megamenu-2">';

            foreach ($value2['below'] as $key3 => $value3) {
              $options = ['class' => ['menu-leaf-link']];
              if (isset($value3['link']['options']['attributes']['title'])) {
                $options['attributes'] = ['title' => $value3['link']['options']['attributes']['title']];
              }

              // Check if the link is hidden.
              if ($value3['link']['hidden'] != 1) {
                // 2nd level <li>.
                $output .= '<li class="menu-leaf-list">' . l($value3['link']['link_title'], $value3['link']['href'], $options) . '</li>';
              }
            } // end third foreach

            // Close 2nd level <ul>.
            $output .= '</ul>';

            // Close the list only if we use <li>.
            if (theme_get_setting('menu_headings') == 1) {
              // Close 2ndlevel <li>.
              $output .= '</li>';
              // Close section <ul>.
              $output .= '</ul>';
            }

            // Close <div> menu-section.
            $output .= '</div>';
          }
        } // end second foreach

        // Close <div> megamenuWrapper.
        $output .= '</div>';
        $output .= '<div class="closepanel"><span class="close-panel" title="close this panel">' . t('close this panel') . '</span></div>';
        // Close <div> mega.
        $output .= '</div>';
      } // end check for children

      // Close first level <li>.
      $output .= '</li>';
      $count_main_links++;
    } // end check if link is hidden
  } //end first foreach

  // Close first level <ul>.
  $output .= '</ul>';

  return $output;
}

/**
 *
 */
// function marinelli_mbanner_text() {
//   $banner_text = '<div id="header-image-text" class="marinelli-hide-no-js">';
//   $banner_text .= '<div id="header-image-text-data">';
//   $banner_text .= '<' . OUTTAG . ' id="header-image-title"><a href="#" class="bannerlink" title="' . t('See this content') . '">title</a></' . OUTTAG . '>';
//   $banner_text .= '<p id="header-image-description"><a href="#" class="bannerlink" title="' . t('See this content') . '">description</a></p>';
//   $banner_text .= '</div>';
//   $banner_text .= '</div>';

//   return $banner_text;
// }

/**
 *
 */
// function marinelli_mbanner_nav($variables) {
//   $banner_navigation = '<div id="header-image-navigation" class="marinelli-hide-no-js">';
//   $banner_navigation .= '<a href="#" id="header-image-prev" title="' . $variables['prev'] . '">&lsaquo;</a>';
//   $banner_navigation .= '<a href="#" id="header-image-next" title="' . $variables['next'] . '">&rsaquo;</a>';
//   $banner_navigation .= '</div>';

//   return $banner_navigation;
// }
