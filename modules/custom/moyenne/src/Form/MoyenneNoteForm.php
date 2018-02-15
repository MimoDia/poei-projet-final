<?php
namespace Drupal\moyenne\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MoyenneNoteForm extends FormBase {
protected $state;

     /**
      * {@inheritdoc}.
      */
     public function __construct(StateInterface $state) {
       $this->state = $state;
     }

     /**
      * {@inheritdoc}.
      */
     public static function create(ContainerInterface $container) {
       return new static(
         $container->get('state')
       );
     }

   // renvoyer des identifiant
   public function getFormID() {
     return 'moyenne_calcul_formulaire';
   }

   //renvoyer la structure
   public function buildForm(array $form, FormStateInterface $form_state) {
     // Champ destiné à afficher le résultat du calcul.
       // if (isset($form_state->getRebuildInfo()['result'])) {
       //   $form['result'] = array(
       //     '#markup' => '<h2>' . $this->t('Result: ') . $form_state->getRebuildInfo()['result'] . '</h2>',
       //   );
       // }

     $form['liste_matiere'] = array(
       '#type' => 'radios',
       '#title' => t('List of domain'),
       '#options' => array(
         'chimie_atomistique'  => 'Atome Chimical ',
         'chimie_minerale' =>  'Mineral Chimical ',
         'chimie_physique'  => 'Physical Chimical',
     ));

        $form['nombre_de_devoir'] = array(
       '#type' => 'textfield',
       '#title' => t('Number total of home work test'),
       '#description'   => $this->t('Enter the numer of home work test did'),
      // '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-nombre_de_devoir"></span>',
     );    


     $form['note1'] = array(
       '#type' => 'textfield',
       '#title' => t('Note of homework 1'),
       '#description'   => $this->t('Enter your first note'),
      // '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-note1"></span>',
     );

     $form['note2'] = array(
       '#type' => 'textfield',
       '#title' => t('Note of homework 2'),
       '#description'   => $this->t('Enter your second note'),
     //  '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-note2"></span>',
     );

          $form['note3'] = array(
       '#type' => 'textfield',
       '#title' => t('Note of homework 3'),
       '#description'   => $this->t('Enter your third note'),
     //  '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-note3"></span>',
     );

     $form['calculate'] = array(
       '#type' => 'submit',
       '#value' => t('calculer'),
     );
       
     return $form;
   }


   public function AjaxValidateNumeric(array &$form, FormStateInterface $form_state) {
     $response = new AjaxResponse();

       // print_r(json_encode($form_state->getTriggeringElement()));

       $field = $form_state->getTriggeringElement()['#name'];
       $css = ['border' => '2px solid green'];
       $message = $this->t('OK!');
       if (!is_numeric($form_state->getValue($field))) {
         $css = ['border' => '2px solid red'];
         $message = $this->t('%field must be numeric!', array('%field' => $form[$field]['#title']));
       }else{
         $css = ['border' => '2px solid green'];
         $message = $this->t('%field is numeric! and it is allowed', array('%field' => $form[$field]['#title']));
       }

       $response->AddCommand(new CssCommand("[name=$field]", $css));
       $response->AddCommand(new HtmlCommand('#error-message-' . $field, $message));

       return $response;
   }


   // La methode qui permet de faire toutes les vérification possibles
   public function validateForm(array &$form, FormStateInterface $form_state) {
     // Validation is optional.


     $nombre_de_devoir = $form_state->getValue('nombre_de_devoir');
    // $valeurOption = $form_state->getValue('operation');

     $note1 = $form_state->getValue('note1');
     $note2 = $form_state->getValue('note2');
     $note3 = $form_state->getValue('note3');



      if (!is_numeric($nombre_de_devoir)) {
         $form_state->setErrorByName('nombre_de_devoir', $this->t('The number of homework of must be numeric!'));
       }

       if (!is_numeric($note1)) {
         $form_state->setErrorByName('note1', $this->t('The note 1 value must be numeric!'));
       }
        if (!is_numeric($note2)) {
         $form_state->setErrorByName('note2', $this->t('The note 2 value must be numeric!'));
       }

        if (!is_numeric($note3)) {
         $form_state->setErrorByName('note3', $this->t('The note 3 value must be numeric!'));
       }

     }

     public function submitForm(array &$form, FormStateInterface $form_state) {

     // Récupère la valeur des champs.
       $nombre_de_devoir = $form_state->getValue('nombre_de_devoir');

       $note1   = $form_state->getValue('note1');
       $note2   = $form_state->getValue('note2');
       $note3   = $form_state->getValue('note3');

           $moyenne = $note1 + $note2 + $note3;

           $moyenne = $moyenne / $nombre_de_devoir ;

          drupal_set_message(t('Your mean chimical is :%result', array('%result' => $moyenne)));

     // $form_state->addRebuildInfo('result ' , $moyenne_chimie_atome);

      // Reconstruction du formulaire avec les valeurs saisies (memoriser les donnees saisies).
       $form_state->setRebuild();

      // AFFICHER LE RESULTATS MAIS ON A PREFERER ICI LAFFICHER DANS LE HAUT DU FORMULAIRE 
      // drupal_set_message(t('Result is :%result', array('%result' => $resultat_chimie_atome)));
      //}
    }
}