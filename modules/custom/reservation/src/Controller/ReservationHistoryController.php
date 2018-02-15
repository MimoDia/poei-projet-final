<?php

namespace Drupal\reservation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Datetime\DateFormatter;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ReservationHistoryController extends ControllerBase {

	protected $database;
 	protected $dateFormatter;

	 public function __construct(Connection $database, DateFormatter $dateFormatter) {
	   $this->database = $database;
	   $this->dateFormatter = $dateFormatter;
	 }

	 public static function create(ContainerInterface $container) {
	   return new static(
	     $container->get('database'),
	     $container->get('date.formatter')
	   );
	 }  

	public function content(NodeInterface $node) {
		
		/*kint($node->id());
		die();*/
			  $query = $this->database->select('reservation', 'hnh')
			     ->fields('hnh', array('uid','nid','reservation_date_start','reservation_date_end'))
			     ->condition('nid', $node->id());

		//message entête
		 $count = $query->countQuery()->execute()->fetchField();
	    $message = array(
	     '#theme' => 'reservation-node-history',
	     '#node'  => $node,
	     '#count' => $count
	   		);

		$result = $query->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit('5')->execute();
		   $rows = array();
		   $userStorage = $this->entityTypeManager()->getStorage('user');

		   $Storage = $this->entityTypeManager()->getStorage('node');
		   $ids = \Drupal::entityQuery('node')->condition('type', 'salle')->execute();
		  
		   foreach ($result as $record) {
		   //	kint($record);
		     $rows[] = array(
		       $userStorage->load($record->uid)->toLink(),
		      
		      	$Storage->load($record->nid)->toLink(),
		       $this->dateFormatter->format($record->reservation_date_start,'date_reservation_francais'),
		       $this->dateFormatter->format($record->reservation_date_end,'date_reservation_francais'),
		     );
		   }
		   // affichage si tableau non vide
		   //if($table != 'NULL'){
		   		$table = array(
			     '#theme'  => 'table',
			     '#header' => array($this->t('Author'),$this->t('Room'), $this->t('Booking date start'), $this->t('Booking date end')),
			     '#rows'   => $rows,
		   );
		   

		   // Pagination.
		   $pager = array('#type' => 'pager');

		   // On renvoie les 3 render arrays.
		   return array(
		     'message' => $message,
		     'table' => $table,
		     'pager' => $pager
	
		   );


	}

}

 