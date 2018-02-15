<?php

namespace Drupal\reservation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;
use Drupal\paragraphs\Entity\Paragraph;

class ReservationListController extends ControllerBase {

	public function content($param) {

		
		$ids = \Drupal::entityQuery('node')->condition('type', 'salle')->pager('3')->execute();
		//kint(\Drupal::entityQuery('node')->condition('type', 'salle'));
		if ($param) {
			$ids = \Drupal::entityQuery('node')->condition('type', 'salle')->pager('3')->execute();
			//kint(\Drupal::entityQuery('node')->condition('type', $param));
		}	
		$storage =\Drupal::entityTypeManager()->getStorage('node');
		$entities = $storage->loadMultiple($ids);
		//kint($entities);
	//	array $options = array()

		foreach ($entities as $node){
    			$items[] = $node->toLink();
    			//$items[] = $node->get('field_photos_salle')->entity->url();
    			$items[] = $node->get('field_photos_salle')->first()->view();
    			$items[] = $node->get('field_description_salle')->first()->view();

			}

			$list = array(
			'#theme' => 'item_list',
			'#items' => $items,
			);
			$pager = array(
			'#type' => 'pager',
			);

		return array($list,$pager);
		

	}

}

	