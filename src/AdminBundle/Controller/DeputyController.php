<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AdminBundle\Entity\Deputy;
use AdminBundle\Form\DeputyType;

/**
 * Deputy controller.
 *
 * @Route("/admin/deputy")
 */
class DeputyController extends Controller
{

    /**
     * Lists all Deputy entities.
     *
     * @Route("/", name="admin_deputy")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminBundle:Deputy')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Deputy entity.
     *
     * @Route("/", name="admin_deputy_create")
     * @Method("POST")
     * @Template("AdminBundle:Deputy:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Deputy();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_deputy_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Deputy entity.
     *
     * @param Deputy $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Deputy $entity)
    {
        $form = $this->createForm(new DeputyType(), $entity, array(
            'action' => $this->generateUrl('admin_deputy_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Deputy entity.
     *
     * @Route("/new", name="admin_deputy_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Deputy();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Deputy entity.
     *
     * @Route("/{id}", name="admin_deputy_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Deputy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Deputy entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Deputy entity.
     *
     * @Route("/{id}/edit", name="admin_deputy_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Deputy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Deputy entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Deputy entity.
    *
    * @param Deputy $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Deputy $entity)
    {
        $form = $this->createForm(new DeputyType(), $entity, array(
            'action' => $this->generateUrl('admin_deputy_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Deputy entity.
     *
     * @Route("/{id}", name="admin_deputy_update")
     * @Method("PUT")
     * @Template("AdminBundle:Deputy:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminBundle:Deputy')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Deputy entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_deputy_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Deputy entity.
     *
     * @Route("/{id}", name="admin_deputy_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminBundle:Deputy')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Deputy entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_deputy'));
    }

    /**
     * Creates a form to delete a Deputy entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_deputy_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
