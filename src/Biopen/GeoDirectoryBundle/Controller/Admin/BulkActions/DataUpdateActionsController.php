<?php

namespace Biopen\GeoDirectoryBundle\Controller\Admin\BulkActions;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DataUpdateActionsController extends BulkActionsAbstractController
{
   public function updateGamificationAction(Request $request, SessionInterface $session)
   {
      $em = $this->get('doctrine_mongodb')->getManager();
      $qb = $em->createQueryBuilder('BiopenCoreBundle:User');
      $qb->field('email')->notEqual(null);
      $query = $qb->getQuery();
      $users = $query->execute();

      $gamificationService = $this->get('biopen_user.gamification');

      $i = 0;
      foreach ($users as $key => $user)
      {
         $gamificationService->updateGamification($user);

         if ((++$i % 100) == 0) {
            $em->flush();
            $em->clear();
         }
      }

      $em->flush();
      $em->clear();

      $session->getFlashBag()->add('success', count($users) . " utilisateurs ont été mis à jour");
      return $this->redirect($this->generateUrl('admin_biopen_core_user_list'));
   }
}