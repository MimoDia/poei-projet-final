<?php
namespace Drupal\moyenne\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use \Drupal\user\Entity\User;
use \Drupal\node\NodeInterface;

class MoyenneGeneraleForm extends FormBase {
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
     return 'moyenne_generale_calcul_formulaire';
   }

   //renvoyer la structure
   public function buildForm(array $form, FormStateInterface $form_state) {
     // Champ destiné à afficher le résultat du calcul.
       // if (isset($form_state->getRebuildInfo()['result'])) {
       //   $form['result'] = array(
       //     '#markup' => '<h2>' . $this->t('Result: ') . $form_state->getRebuildInfo()['result'] . '</h2>',
       //   );
       // }

     //      $form['nombre_de_devoir'] = array(
     //   '#type' => 'textfield',
     //   '#title' => t('Number total of home work test'),
     //   '#description'   => $this->t('Enter the numer of home work test did'),
     //   '#required' => TRUE,
     //   '#ajax' =>array(
     //     'callback' => array($this, 'AjaxValidateNumeric'),
     //     'event' => 'change',
     //   ),
     //   '#suffix' => '<span id="error-message-nombre_de_devoir"></span>',
     // );


     $form['liste_matiere_licence1'] = array(
       '#theme' => 'item_list',
       '#title' => t('Licence 1 (coefficient 10)'),
       '#items' => array(
         'chimie_atomistique'  => 'Atome Chimical (coeff 2)',
         'chimie_minerale' =>  'Mineral Chimical (coeff 5)',
         'chimie_physique'  => 'Physical Chimical (coeff 3)',
     ));
     $form['liste_matiere_licence2'] = array(
       '#theme' => 'item_list',
       '#title' => t('Licence 2 (coefficient 7)'),
       '#items' => array(
         'chimie_atomistique'  =>  'Atome Chimical (coeff 4)',
         'chimie_minerale' =>  'Mineral Chimical (coeff 2)',
         'chimie_physique'   => 'Physical Chimical (coeff 1)',
     ));
      $form['liste_matiere_licence3'] = array(
       '#theme' => 'item_list',
       '#title' => t('Licence 3 (coefficient 4)'),
       '#items' => array(
         'chimie_atomistique'  =>  'Atome Chimical (coeff 1)',
         'chimie_minerale' =>  'Mineral Chimical (coeff 1)',
         'chimie_physique'   => 'Physical Chimical (coeff 2)',

     ));
    


     $form['coefficient'] = array(
       '#type' => 'select',
       '#title' => t('Coefficient'),
       '#description'   => $this->t('Enter the coefficient value'),
       '#required' => TRUE,
       '#options' => array(
         'coefficient_dix'    =>  10,
         'coefficient_seven'  =>  7,
         'coefficient_four'   =>  4,
     ));

//
     // $form['operation'] = array(
     //   '#type' => 'select',
     //   '#title' => t('Operation'),
     //   '#description' => t('choisir votre operation'),
     //   '#default_value' => 1,
     //   '#options' => array(
     //     1 => $this->t('Addition'),       
     // ));
//
     $form['chimieAtome'] = array(
       '#type' => 'textfield',
       '#title' => t('Atomic atome chimical mean /20'),
       /*'#description'   => $this->t('Enter your mean of Atomic chimical'),*/
       '#required' => TRUE,
       '#maxlength' => 2,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-chimieAtome"></span>',
     );

     $form['chimiePhysique'] = array(
       '#type' => 'textfield',
       '#title' => t('Atomic physical chimical mean /20'),
       /*'#description'   => $this->t('Enter your mean of physical chimical'), */
       '#required' => TRUE,
       '#maxlength' => 2,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-chimiePhysique"></span>',
     );

          $form['chimieMinearale'] = array(
       '#type' => 'textfield',
       '#title' => t('Mineral chimical mean /20'),
       /*'#description'   => $this->t('Enter your mean of mineral chimical'), */
       '#required' => TRUE,
       '#maxlength' => 2,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-chimieMinearale"></span>',
     );

     $form['calculate'] = array(
       '#type' => 'submit',
       '#value' => t('Déterminer votre moyenne des devoirs'),
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

     $chimieAtome = $form_state->getValue('chimieAtome');
     $chimiePhysique = $form_state->getValue('chimiePhysique');
     $chimieMinearale = $form_state->getValue('chimieMinearale');



      // if (!is_numeric($nombre_de_devoir)) {
      //    $form_state->setErrorByName('nombre_de_devoir', $this->t('The number of tests of must be numeric!'));
      //  }

       if (!is_numeric($chimieAtome)) {
         $form_state->setErrorByName('chimieAtome', $this->t('The mean of atome chimical  value must be numeric!'));
       }
        if (!is_numeric($chimiePhysique)) {
         $form_state->setErrorByName('chimiePhysique', $this->t('The mean of physical chimical value must be numeric!'));
       }

        if (!is_numeric($chimieMinearale)) {
         $form_state->setErrorByName('chimieMinearale', $this->t('The mean of physical chimical value must be numeric!'));
       }

     }


     public function submitForm(array &$form, FormStateInterface $form_state) {
   
     // Récupère la valeur des champs.
       $coefficient  = $form_state->getValue('coefficient');

       $nombre_de_devoir = $form_state->getValue('nombre_de_devoir');
       //$valeurOption = $form_state->getValue('operation');

       $chimieAtome   = $form_state->getValue('chimieAtome');
       $chimiePhysique   = $form_state->getValue('chimiePhysique');
       $chimieMinearale   = $form_state->getValue('chimieMinearale');

           if( $coefficient == 'coefficient_dix'){

           $notes_et_coeff = ($chimieAtome * 2) + ($chimiePhysique * 5) + ($chimieMinearale * 3);

           $moyenne_generale_chimie = round($notes_et_coeff / 10 );
           $moyenne_generale_chimie_enBackoffice= $moyenne_generale_chimie .'/20';


             drupal_set_message(t('Your general chimical mean in Licence 1 is :%result', array('%result' => $moyenne_generale_chimie_enBackoffice)));

      //         // kint(\Drupal::service('current_user'));
      //     $name=\Drupal::service('current_user')->getAccountName();
      //   $uid=\Drupal::service('current_user')->id();
      // $email=\Drupal::service('current_user')->getEmail();

      //   $conn = \Drupal::service('database');
      //   $conn->insert('moyenne_generale')->fields(
      // array(
      //   'moyenne_totale' => $moyenne_generale_chimie,
      //   'time' => time(),
      //    'uid' => $uid,
      //    'email' => $email,
      //    'name' => $name,
      // ))
      // ->execute();
             
         }

         if( $coefficient == 'coefficient_seven'){

           $notes_et_coeff = ($chimieAtome * 4) + ($chimiePhysique * 2) + ($chimieMinearale * 1);

           $moyenne_generale_chimie = $notes_et_coeff / 7 ;

             drupal_set_message(t('Your general chimical mean in Licence 2 is :%result', array('%result' => $moyenne_generale_chimie)));

// // kint(\Drupal::service('current_user'));
//                       $name=\Drupal::service('current_user')->getAccountName();
//         $uid=\Drupal::service('current_user')->id();
//       $email=\Drupal::service('current_user')->getEmail();

//         $conn = \Drupal::service('database');
//         $conn->insert('moyenne_generale')->fields(
//       array(
//         'moyenne_totale' => $moyenne_generale_chimie,
//         'time' => time(),
//          'uid' => $uid,
//          'email' => $email,
//          'name' => $name,
//       ))
//       ->execute();
             
          }//else{
         //  drupal_set_message(t('This operation must be an addition for the coefficient seven'), 'error');
         // } 

         if( $coefficient == 'coefficient_four'){

           $notes_et_coeff = ($chimieAtome * 1) + ($chimiePhysique * 1) + ($chimieMinearale * 2);

           $moyenne_generale_chimie = $notes_et_coeff / 4 ;

             drupal_set_message(t('Your general chimical mean in Licence 3 is :%result', array('%result' => $moyenne_generale_chimie)));

// // kint(\Drupal::service('current_user'));
//          $name=\Drupal::service('current_user')->getAccountName();
//         $uid=\Drupal::service('current_user')->id();
//       $email=\Drupal::service('current_user')->getEmail();

//         $conn = \Drupal::service('database');
//         $conn->insert('moyenne_generale')->fields(
//       array(
//         'moyenne_totale' => $moyenne_generale_chimie,
//         'time' => time(),
//          'uid' => $uid,
//          'email' => $email,
//          'name' => $name,
//       ))
//       ->execute();
             
         }         


     // $form_state->addRebuildInfo('result ' , $moyenne_chimie_atome);

      // Reconstruction du formulaire avec les valeurs saisies (memoriser les donnees saisies).
       $form_state->setRebuild();

      // AFFICHER LE RESULTATS MAIS ON A PREFERER ICI LAFFICHER DANS LE HAUT DU FORMULAIRE 
      // drupal_set_message(t('Result is :%result', array('%result' => $resultat_chimie_atome)));
      //}
      // kint(\Drupal::service('current_user'));

    }
}
