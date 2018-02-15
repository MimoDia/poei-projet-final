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


$liste= array('Atome Chimical (coeff 2)', 'Mineral Chimical (coeff 5)', 'Physical Chimical (coeff 3)' );

 array(
'#markup' =>'markup',
'liste'   => $liste,
  );

class MoyenneGeneraleProfForm extends FormBase {
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
     return 'moyenne_generale_calcul_formulaire_secretaire';
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
         'chimie_atomistique'  => 'Chimie atomique (coeff 2)',
         'chimie_minerale' =>  'Chimie minérale (coeff 5)',
         'chimie_physique'  => 'Chimie physique (coeff 3)',
     ));
     $form['liste_matiere_licence2'] = array(
       '#theme' => 'item_list',
       '#title' => t('Licence 2 (coefficient 7)'),
       '#items' => array(
         'chimie_atomistique'  =>  'Chimie atomique (coeff 4)',
         'chimie_minerale' =>  'Chimie minérale (coeff 2)',
         'chimie_physique'   => 'Chimie physique (coeff 1)',
     ));
      $form['liste_matiere_licence3'] = array(
       '#theme' => 'item_list',
       '#title' => t('Licence 3 (coefficient 4)'),
       '#items' => array(
         'chimie_atomistique'  =>  'Chimie atomiquel (coeff 1)',
         'chimie_minerale' =>  'Chimie minérale (coeff 1)',
         'chimie_physique'   => 'Chimie physique (coeff 2)',

     ));
    
        $form['nom'] = array(
       '#type' => 'textfield',
       '#title' => t('Name'),
      /* '#description'   => $this->t('Enter the name'), */
       
     ); 

        $form['prenom'] = array(
       '#type' => 'textfield',
       '#title' => t('First name'),
      /* '#description'   => $this->t('Enter the first name'), */
     ); 

       $form['naissance_annee'] = array(
       '#type' => 'textfield',
       '#title' => t('Your year of birth'),
       /* '#description'   => $this->t('Enter the year of birth '), */
      '#maxlength' => 4,
       '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-naissance_annee"></span>',
     ); 

       $form['licence'] = array(
       '#type' => 'select',
       '#title' => t('Licence level'),
       /*'#description'   => $this->t('Enter the licence level'), */
       '#required' => TRUE,
       '#options' => array(
         'licence_one'     => 'Licence 1 (coeff 10)',
         'licence_two'     =>  'Licence 2 (coeff 7)',
         'licence_three'   => 'Licence 3 Chimical (coeff 4)',
     ));     

     $form['coefficient'] = array(
       '#type' => 'select',
       '#title' => t('Coefficient'),
       /* '#description'   => $this->t('Enter the value of coefficient'), */
       '#required' => TRUE,
       '#options' => array(
         'coefficient_dix'    =>  10,
         'coefficient_seven'  =>  7,
         'coefficient_four'   =>  4,
     ));

     $form['chimieAtome'] = array(
       '#type' => 'textfield',
       '#title' => t('Atomic atome chimical mean /20'),
      /* '#description'   => 'Entrez la moyene en chimie atomistique',*/
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-chimieAtome"></span>',
     );

     $form['chimiePhysique'] = array(
       '#type' => 'textfield',
       '#title' => t('Atomic physical chimical mean /20'),
      /* '#description'   => 'Entrez votre moyenne en chimie physique', */
       '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-chimiePhysique"></span>',
     );

          $form['chimieMinearale'] = array(
       '#type' => 'textfield',
       '#title' => t('Mineral chimical mean /20'),
      /* '#description'   => 'Entrez la moyene en chimie minérale', */
       '#required' => TRUE,
       '#ajax' =>array(
         'callback' => array($this, 'AjaxValidateNumeric'),
         'event' => 'change',
       ),
       '#suffix' => '<span id="error-message-chimieMinearale"></span>',
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
//kint($form_state->getValues());

     $nombre_de_devoir = $form_state->getValue('nombre_de_devoir');
    // $valeurOption = $form_state->getValue('operation');

     $chimieAtome = $form_state->getValue('chimieAtome');
     $chimiePhysique = $form_state->getValue('chimiePhysique');
     $chimieMinearale = $form_state->getValue('chimieMinearale');
     $naissance_annee = $form_state->getValue('naissance_annee');

     $coefficient  = $form_state->getValue('coefficient');
     $licence  = $form_state->getValue('licence');
     // kint($coefficient);
   //  kint($licence);

      // if (!is_numeric($nombre_de_devoir)) {
      //    $form_state->setErrorByName('nombre_de_devoir', $this->t('The number of tests of must be numeric!'));
      //  }


      if($licence == 'licence_one' && $coefficient != 'coefficient_dix'){

          $form_state->setErrorByName('licence_one', $this->t('You must select Licence 1 and coefficient 10 !'));
          
       }  

        if($licence == 'licence_two' && $coefficient != 'coefficient_seven'){

          $form_state->setErrorByName('licence_three', $this->t('You must select Licence 2 and coefficient 7 !'));
          
       } 

       if($licence == 'licence_three' && $coefficient != 'coefficient_four'){

          $form_state->setErrorByName('licence_three', $this->t('You must select Licence 3 and coefficient 4 !'));
          
       } 


       if(!is_numeric($naissance_annee)) {
         $form_state->setErrorByName('naissance_annee', $this->t('The year of birth must be numeric !'));
       }

        if (!is_numeric($chimieAtome)) {
         $form_state->setErrorByName('chimieAtome', $this->t('The mean of atome chimical  value must be numeric !'));
       }

        if (!is_numeric($chimiePhysique)) {
         $form_state->setErrorByName('chimiePhysique', $this->t('The mean of physical chimical value must be numeric !'));
       }

        if (!is_numeric($chimieMinearale)) {
         $form_state->setErrorByName('chimieMinearale', $this->t('The mean of physical chimical value must be numeric !'));
       }


       if (isset($naissance_annee) && strlen($naissance_annee) <= 3 ) {
        $form_state->setErrorByName('naissance_annee', $this->t('The year of birth must have 4 numeric caracters  !'));
     }

      if (isset($naissance_annee) && $naissance_annee < 1990 ) {
        $form_state->setErrorByName('naissance_annee', $this->t('The year of birth must be superior than 1990 !'));
     }

     if (isset($chimieAtome) && strlen($chimieAtome) > 2 ) {
        $form_state->setErrorByName('chimieAtome', $this->t('The mean of atome chimical  value must have 2 numeric caracters  !'));
     }

     if (isset($chimieMinearale) && strlen($chimieMinearale) > 2 ) {
        $form_state->setErrorByName('chimie Minearale', $this->t('The mean of  chimical mineral value must have 2 numeric caracters  !'));
     }

     if (isset($chimiePhysique) && strlen($chimiePhysique) > 2 ) {
        $form_state->setErrorByName('chimie Physique', $this->t('The mean of  Physical chimical value must have 2 numeric caracters  !'));
     } 


     // verification que les champs ne sont pas vides  
    if (!$form_state->getValue('nom') || empty($form_state->getValue('nom'))) {
        $form_state->setErrorByName('nom', $this->t('Your first firt name is required '));
    }
    // 
    if (!$form_state->getValue('prenom') || empty($form_state->getValue('prenom'))) {
        $form_state->setErrorByName('prénom', $this->t('Your first name is required'));
    }

      if (empty($form_state->getValue('chimieAtome'))) {
        $form_state->setErrorByName('chimie atomistique', $this->t('Your mean in atomic chimistry is required'));
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

       $nom   = strtoupper($form_state->getValue('nom'));
       $prenom  = ucfirst($form_state->getValue('prenom'));
       $naissance_annee = $form_state->getValue('naissance_annee');
       $licence   = ucfirst($form_state->getValue('licence'));


           if( $coefficient == 'coefficient_dix') {

           $notes_et_coeff = ($chimieAtome * 2) + ($chimiePhysique * 5) + ($chimieMinearale * 3);

           $moyenne_generale_chimie = round( $notes_et_coeff / 10 );
           $moyenne_generale_chimie_enBackoffice= $moyenne_generale_chimie .'/20';


             drupal_set_message(t('Your general chimical mean in Licence 1 is :%result', array('%result' => $moyenne_generale_chimie_enBackoffice)));


        $conn = \Drupal::service('database');
        $conn->insert('moyenne_generale_secretariat')->fields(
      array(
        'moyenne_totale' => $moyenne_generale_chimie,
        'date' => time(),
        'nom' => $nom,
         'prenom' => $prenom,
         'naissance_annee' => $naissance_annee,
         'licence' => $licence,
      ))
      ->execute();
             
         }//else{
         //  drupal_set_message(t('This operation must be an addition for the coefficient ten'), 'error');
         // } 

         if( $coefficient == 'coefficient_seven'){

           $notes_et_coeff = ($chimieAtome * 4) + ($chimiePhysique * 2) + ($chimieMinearale * 1);

           $moyenne_generale_chimie = round( $notes_et_coeff / 7 );

             drupal_set_message(t('Your general chimical mean in Licence 2 is :%result', array('%result' => $moyenne_generale_chimie)));


        $conn = \Drupal::service('database');
        $conn->insert('moyenne_generale_secretariat')->fields(
      array(
        'moyenne_totale' => $moyenne_generale_chimie,
        'date' => time(),
        'nom' => $nom,
         'prenom' => $prenom,
         'naissance_annee' => $naissance_annee,
         'licence' => $licence,
      ))
      ->execute();
             
          }//else{
         //  drupal_set_message(t('This operation must be an addition for the coefficient seven'), 'error');
         // } 

         if( $coefficient == 'coefficient_four'){

           $notes_et_coeff = ($chimieAtome * 1) + ($chimiePhysique * 1) + ($chimieMinearale * 2);

           $moyenne_generale_chimie = round( $notes_et_coeff / 4 );

             drupal_set_message(t('Your general chimical mean in Licence 3 is :%result', array('%result' => $moyenne_generale_chimie)));


        $conn = \Drupal::service('database');
        $conn->insert('moyenne_generale_secretariat')->fields(
      array(
        'moyenne_totale' => $moyenne_generale_chimie,
        'date' => time(),
        'nom' => $nom,
         'prenom' => $prenom,
         'naissance_annee' => $naissance_annee,
         'licence' => $licence,
      ))
      ->execute();
             
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
