<?php
/**
 * @Author: Sebastian Castro
 * @Date:   2017-03-28 15:29:03
 * @Last Modified by:   Sebastian Castro
 * @Last Modified time: 2018-04-22 19:45:15
 */
namespace Biopen\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ConfigurationAPIAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'biopen_core_bundle_config_api_admin_classname';

    protected $baseRoutePattern = 'biopen/core/configuration-api';

    protected function configureFormFields(FormMapper $formMapper)
    {
        $dm = $this->getConfigurationPool()->getContainer()->get('doctrine_mongodb');
        $apiProperties = $dm->getRepository('BiopenGeoDirectoryBundle:Element')->findAllCustomProperties();

        $formMapper
            ->with("Configuration")
                ->add('api.protectPublicApiWithToken', 'checkbox', array('label' => "Protéger l'api publique pour récupérer les élément avec des jetons utilisateurs (i.e. besoin de créer un compte pour utiliser l'api publique)", 'required' => false)) 
                ->add('api.internalApiAuthorizedDomains', 'text', array('label' => "Liste des domaines externe qui utiliseront l'API interne. Mettez * si vous voulez que n'importe quel domaine puisse y avoir accès. Cette option est nécessaire si vous voulez afficher vos données avec GoGoCartoJs mais sur un autre serveur.", 'required' => false)) 
                ->add('api.publicApiPrivateProperties', 'choice', array("choices" => $apiProperties, 'label' => "Liste des champs que vous ne voulez pas partager dans l'api publique", 'required' => false, 'multiple' => true))
            ->end()
            ->with("Liste des apis disponibles")
                ->add('apilist', 'text', array('mapped' => false, 'label' => false, 'required' => false, 'attr' => ['class' => 'gogo-api-list'])) 
            ->end()
        ;
    }
}