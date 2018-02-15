<?php

namespace Drupal\reservation\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'DefaultBlock' block.
 *
 * @Block(
 *  id = "default_block",
 *  admin_label = @Translation("Default block"),
 * )
 */
class DefaultBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
  	$build = [];
  	return \Drupal::formBuilder()->getForm('\Drupal\reservation\Form\SubmitForm');
    
  }

}
