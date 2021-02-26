<?php

namespace App\Controller\Admin\BulkActions;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use App\Document\ElementStatus;

class BulkActionsAbstractController extends Controller
{
    protected $optionList = [];
    protected $title = null;
    protected $fromBeginning = false;
    protected $batchSize = 1000;
    protected $automaticRedirection = true;

    protected function elementsBulkAction($functionToExecute, $dm, $request, $session)
    {
        $isStillElementsToProceed = false;

        if (!$this->fromBeginning && $request->get('batchFromStep')) {
            $batchFromStep = $request->get('batchFromStep');
        } else {
            $batchFromStep = 0;
        }

        $qb = $dm->query('Element')
            ->field('status')->gte(ElementStatus::PendingModification)
            ->skip($batchFromStep);
        $count = (clone $qb)->getCount();
        $elementsToProcceedCount = 0;
        if ($count > $this->batchSize) {
            $batchLastStep = $batchFromStep + $this->batchSize;
            $isStillElementsToProceed = true;
            $elementsToProcceedCount = $count - $this->batchSize;
        } else {
            $batchLastStep = $batchFromStep + $count;
        }

        $elements = $qb->limit($this->batchSize)->getCursor(); 

        $i = 0;
        $renderedViews = [];
        foreach ($elements as $key => $element) {
            try {
                $view = $this->$functionToExecute($element, $dm);
                if ($view) {
                    $renderedViews[] = $view;
                }
            } catch (\Exception $e) {
                $renderedViews[] = "Erreur en traitant l'élement {$element->getId()} : {$e->getMessage()} FILE {$e->getFile()} LINE {$e->getLine()}";
            }

            if (0 == (++$i % 100)) {
                $dm->flush();
                $dm->clear();
            }
        }

        $dm->flush();
        $dm->clear();

        $redirectionRoute = $this->generateUrl($request->get('_route'), ['batchFromStep' => $batchLastStep]);
        if ($isStillElementsToProceed && $this->automaticRedirection) {
            return $this->redirect($redirectionRoute);
        }

        if ($this->automaticRedirection) {
            $session->getFlashBag()->add('success', 'Tous les éléments ont été traité avec succès');

            return $this->redirectToIndex();
        }

        return $this->render('admin/pages/bulks/bulk_abstract.html.twig', [
            'isStillElementsToProceed' => $isStillElementsToProceed,
            'renderedViews' => $renderedViews,
            'firstId' => $batchFromStep,
            'lastId' => $batchLastStep,
            'elementsToProcceedCount' => $elementsToProcceedCount,
            'redirectionRoute' => $redirectionRoute,
            'title' => $this->title ? $this->title : $functionToExecute, ]);
    }

    protected function redirectToIndex()
    {
        return $this->redirect($this->generateUrl('gogo_bulk_actions_index'));
    }
}
