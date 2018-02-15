<?php

namespace Drupal\reservation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

class ReservationController extends ControllerBase {

	public function content($param) {
	
     $user = $this->currentUser()->getAccountName();
     return ['#markup'=> $this->t('My name is :<strong> %nom </strong> this is my parameter: %param !',array('%nom'=>$user,'%param'=>$param))];
   
	}

}