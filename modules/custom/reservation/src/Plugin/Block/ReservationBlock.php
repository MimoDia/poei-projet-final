<?php

namespace Drupal\reservation\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'ReservationBlock' block.
 *
 * @Block(
 *  id = "reservation_block",
 *  admin_label = @Translation("Reservation block"),
 * )
 */
class ReservationBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'reservation_process' => $this->t(''),
    ] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form['reservation_process'] = [
      '#type' => 'submit',
      '#title' => $this->t('Reservation process'),
      '#description' => $this->t('start the process'),
      '#default_value' => $this->configuration['reservation_process'],
      '#weight' => '0',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['reservation_process'] = $form_state->getValue('reservation_process');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    return \Drupal::formBuilder()->getForm('\Drupal\reservation\Form\SubmitForm');
  }

}
