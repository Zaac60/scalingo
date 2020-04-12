<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProjectAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', 'text');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('name', null, ['template' => 'admin/partials/list_project_name.html.twig'])
            ->add('description')
            ->add('dataSize', null)
            ->add('adminEmails')
            ->add('published', null)
            ->add('pinned', null, ['editable' => true])
            ->add('createdAt')
            ->add('_action', 'actions', [
                'actions' => [
                    // 'show' => array(),
                    // 'edit' => array(),
                    'delete' => [],
                ],
            ]);
    }
}
