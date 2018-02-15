<?php

namespace Drupal\reservation\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Session\AccountProxy;

/**
 * Class ReservationForm.
 */
class ReservationForm extends FormBase {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;
  /**
   * Drupal\Core\Session\AccountProxy definition.
   *
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $currentUser;
  /**
   * Constructs a new ReservationForm object.
   */
  public function __construct(
    Connection $database,
    AccountProxy $current_user
  ) {
    $this->database = $database;
    $this->currentUser = $current_user;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('current_user')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'reservation_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['reservation_date_start'] = [
      '#type' => 'date',
      '#title' => $this->t('Reservation Date Start'),
      '#description' => $this->t('The date reservation start'),
    ];
    $form['reservation_date_end'] = [
      '#type' => 'date',
      '#title' => $this->t('Reservation Date End'),
      '#description' => $this->t('The date reservation end'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save resservation'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $nid = \Drupal::request()->query->get('nid');
   // kint( $nid);
  //  $date_start_reservation= strtotime($form_state->getValue('reservation_date_start'));
    $date_start = $this->database->select('CrenauxHoraires', 'chr')
           ->fields('chr', array('date_start_crenau'))
           ->condition('nid', $nid)->execute();
           $date_start_crenau= $date_start->fetchField();

    $date_end = $this->database->select('CrenauxHoraires', 'chr')
           ->fields('chr', array('date_end_crenau'))
           ->condition('nid', $nid)->execute();
           $date_end_crenau= $date_end->fetchField();
// requête d'extraction des dates occupées
     $date_start_occup = $this->database->select('reservation', 'chr')
           ->fields('chr', array('reservation_date_start'))
           ->execute();
            $datestart_reservation_occup= $date_start_occup->fetchField();

   $date_end_occup = $this->database->select('reservation', 'chr')
           ->fields('chr', array('reservation_date_end'))
           ->execute();
           $dateEnd_reservation_occup = $date_end_occup->fetchField(); 


   ////////////////////////*****/////////////////////
    $date_start_reservation= strtotime($form_state->getValue('reservation_date_start'));
    $date_end_reservation= strtotime($form_state->getValue('reservation_date_end'));


      // vérifier que la date de début n'est pas supérieur à la date de fin
      if ($date_start_reservation > $date_end_reservation) {

          $form_state->setErrorByName('date_start_reservation', $this->t('Start date must be less than date end!'));
        }
        // vérifier que la date de début n'est pas vide
        if ($date_start_reservation == 0) {
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date must be date!'));
        }
        // vérifier que la date de fin n'est pas vide
        if ($date_end_reservation == 0) {
          $form_state->setErrorByName('reservation_date_end', $this->t('The end date must be date!'));
        }
        ////////////////////Comparaison des dates crénaux horaires avec les dates de réservation entrées//////
        // vérifier que la $date_start_reservation >= $date_start_crenau
        if ( $date_start_crenau > $date_start_reservation ) {
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date must be taller or egal than crenau date!'));
        }
        // vérifier que la $date_end_crenau >= $date_start_reservation
        if ( $date_start_reservation > $date_end_crenau) {
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date must be less or egal than crenau date!'));
        }
        // vérifier que la $date_end_reservation >= $date_start_crenau
        if ($date_start_crenau > $date_end_reservation ) {
          $form_state->setErrorByName('reservation_date_end', $this->t('The end date must be taller or egal than crenau date !'));
        }
        // vérifier que la $date_end_crenau >= $date_end_reservation
        if ($date_end_crenau < $date_end_reservation) {
          $form_state->setErrorByName('reservation_date_end', $this->t('The end date must be less or egal crenau date !'));
        }
        //vérification pour une empêcher de réserver avec une date déjà occupéé 
            //vérifions que la date de début de la réservation n'est pas déjà occupéé
        if ($date_start_reservation == $datestart_reservation_occup) {
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date is busy "Start"!'));
        }
         if ($date_start_reservation == $dateEnd_reservation_occup) {
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date is busy "End"!'));
        }
        // fin
        if ($date_end_reservation == $dateEnd_reservation_occup) {
          $form_state->setErrorByName('reservation_date_end', $this->t('The end date is busy "End"!'));
        }
        if ($date_end_reservation == $datestart_reservation_occup) {
          $form_state->setErrorByName('reservation_date_end', $this->t('The end date is busy "Start"!'));
        }
        ///compris entre deux dates déjà occupées

       
       /* if ($date_start_reservation >= $dateEnd_reservation_occup) {
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date is busy!'));
        }  */
        
        ///// $date_start_crenau =< $date_start_reservation =< $date_end_crenau ...regrouper en une ligne possible
        if (($datestart_reservation_occup < $date_start_reservation) && ($date_start_reservation < $dateEnd_reservation_occup)){
          $form_state->setErrorByName('date_start_reservation', $this->t('The start date is busy !'));
        }
         
        //// $date_start_crenau =<  $reservation_date_end =< $date_end_crenau ... regrouper en une ligne possible
         if (($datestart_reservation_occup < $date_end_reservation) && ($date_end_reservation < $dateEnd_reservation_occup)){
          $form_state->setErrorByName('reservation_date_end', $this->t('The end date is busy !'));
        }
      

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    /*$query = $this->database->select('reservation', 'hnh')
           ->fields('hnh', array('uid', 'update_time'))
           ->condition('nid', $node->id());*/

    $date_start_reservation= strtotime($form_state->getValue('reservation_date_start'));
    $date_end_reservation= strtotime($form_state->getValue('reservation_date_end'));
    //récupération de l'id passé en paramètre
    $nid = \Drupal::request()->query->get('nid');
    //insertion dans la base de données
    $this->database->insert('reservation')->fields(
    array(
      'nid' =>    $nid, //id de la salle
      'uid' => $this->currentUser->id(), //utilisateur courant
      // //la date début de la réservation
      'reservation_date_start' =>   $date_start_reservation,
      //la date fin de la réservation
      'reservation_date_end' =>  $date_end_reservation,
        
       )
    )->execute();
    drupal_set_message( t('Thanks you for reservation :%name', array('%name' => $this->currentUser->getAccountName())));

    $form_state->setRedirect('view.salles.page_1');

  }

}
