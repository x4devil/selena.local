<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\UserRequest;
use AppBundle\Form\UserRequestType;

/**
 * UserRequest controller.
 *
 * @Route("/userrequest")
 */
class UserRequestController extends Controller
{
    /**
     * Lists all UserRequest entities.
     *
     * @Route("/", name="userrequest_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userRequests = $em->getRepository('AppBundle:UserRequest')->findAll();

        return $this->render('userrequest/index.html.twig', array(
            'userRequests' => $userRequests,
        ));
    }

    /**
     * Creates a new UserRequest entity.
     *
     * @Route("/new", name="userrequest_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $userRequest = new UserRequest();
        $form = $this->createForm('AppBundle\Form\UserRequestType', $userRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userRequest);
            $em->flush();

            return $this->redirectToRoute('userrequest_show', array('id' => $userRequest->getId()));
        }

        return $this->render('userrequest/new.html.twig', array(
            'userRequest' => $userRequest,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a UserRequest entity.
     *
     * @Route("/{id}", name="userrequest_show")
     * @Method("GET")
     */
    public function showAction(UserRequest $userRequest)
    {
        $deleteForm = $this->createDeleteForm($userRequest);

        return $this->render('userrequest/show.html.twig', array(
            'userRequest' => $userRequest,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserRequest entity.
     *
     * @Route("/{id}/edit", name="userrequest_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserRequest $userRequest)
    {
        $deleteForm = $this->createDeleteForm($userRequest);
        $editForm = $this->createForm('AppBundle\Form\UserRequestType', $userRequest);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($userRequest);
            $em->flush();

            return $this->redirectToRoute('userrequest_edit', array('id' => $userRequest->getId()));
        }

        return $this->render('userrequest/edit.html.twig', array(
            'userRequest' => $userRequest,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserRequest entity.
     *
     * @Route("/{id}", name="userrequest_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserRequest $userRequest)
    {
        $form = $this->createDeleteForm($userRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($userRequest);
            $em->flush();
        }

        return $this->redirectToRoute('userrequest_index');
    }

    /**
     * Creates a form to delete a UserRequest entity.
     *
     * @param UserRequest $userRequest The UserRequest entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(UserRequest $userRequest)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('userrequest_delete', array('id' => $userRequest->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
