<?php
function marinelli_s_c($first, $second, $layout_type, $exception) {
  $class = "";

  if ($first && $second) { // we have both sidebars
    switch ($layout_type) {
      case 1: // content on the left
        switch ($exception) {
          case 1:
            $class = 'grid_3 alpha'; // adjust the margins (we already have a 10px margin from sidebarWrapper)
            break;
          case 2:
            $class = 'grid_3 omega'; // adjust the margins (we already have a 10px margin from sidebarWrapper)
            break;
        }
        break;

    case 2: // content on the middle
      switch($exception){
        case 1:
          $class = 'grid_3 pull_6'; // put the first sidebar on the left
          break;
        case 2:
          $class = 'grid_3'; // put the second sidebar far from the content
          break;
      }
      break;

    case 3: // content on the right
      switch($exception){
        case 1:
          $class = 'grid_3 alpha'; // adjust margins
          break;
        case 2:
          $class = 'grid_3 omega';
          break;
      }
      break;
    }
  }
  elseif (($first && !$second) || (!$first && $second)) { // we have only one sidebar
    switch ($layout_type) {
      case 1: // content on the left
        $class = 'grid_4';
        break;
      case 2: // content on the middle
      	switch($exception){
      		case 1:
      		  $class= 'grid_4 pull_8';
      		  break;
      		case 2:
      		  $class= 'grid_4';
      		  break;
      	}
      break;
      case 3: // content on the right
        $class= 'grid_4 pull_8';
        break;
    }
  }

  return $class;
}

/**
 * Sidebar wrapper class.
 */
function marinelli_w_c($layout_type) {
  $class = "";

  switch ($layout_type) {
    case 1: // content on the left
      $class = 'grid_6';
      break;

    case 3: // content on the right
      $class = 'grid_6 pull_6';
      break;

  }

  return $class;
}

/**
 * content class
 */
function marinelli_c_c($first, $second, $layout_type,$exception) {
  $class = "";

  if ($first && $second) { // both sidebars
    switch ($layout_type) {
      case 1: // content on the left
        $class = 'grid_6';
        break;

      case 2: // content on the middle
        $class = 'grid_6 push_3';
        break;

      case 3: // content on the right
        $class= 'grid_6 push_6';
        break;
    }
  }
  elseif (($first && !$second) || (!$first && $second)) { // only one sidebar
    switch ($layout_type) {
      case 1: // content on the left
        $class = 'grid_8';
        break;
      case 2: // content on the middle
      	switch($exception){
      		case 1:
      		  $class= 'grid_8 push_4';
      		  break;
      		case 2:
      		  $class= 'grid_8';
      		  break;
      	}
      break;
      case 3: // content on the right
        $class = 'grid_8 push_4';
        break;
    }
  }
  elseif (!$first && !$second) { // no sidebars
    $class = 'grid_12';
  }

  return $class;
}
