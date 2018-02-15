<?php

namespace Drupal\reservation\Access;
use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;


class ReservationAccessCheck implements AccessCheckInterface {

  public function applies(Route $route) {
    return NULL;
  }
  
  public function access(Route $route, Request $request = NULL, AccountInterface $account) {
    
    $node = \Drupal::routeMatch()->getParameter('node');
   // kint( $node->getType());
  //  die();
  	
        if($node->getType()=='salle'){
          return AccessResult::allowed();
          }
          else {
                return AccessResult::forbidden();
            }
            
        }
    
}