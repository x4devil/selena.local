<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Images;
use AppBundle\Form\ImagesType;

/**
 * Images controller.
 *
 * @Route("/images")
 */
class ImagesController extends Controller
{
    /**
     * Lists all Images entities.
     *
     * @Route("/", name="images_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository('AppBundle:Images')->findAll();

        return $this->render('images/index.html.twig', array(
            'images' => $images,
        ));
    }

    /**
     * Creates a new Images entity.
     *
     * @Route("/new", name="images_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $image = new Images();
        $form = $this->createForm('AppBundle\Form\ImagesType', $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('images_show', array('id' => $image->getId()));
        }

        return $this->render('images/new.html.twig', array(
            'image' => $image,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Images entity.
     *
     * @Route("/{id}", name="images_show")
     * @Method("GET")
     */
    public function showAction(Images $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return $this->render('images/show.html.twig', array(
            'image' => $image,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Images entity.
     *
     * @Route("/{id}/edit", name="images_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Images $image)
    {
        $deleteForm = $this->createDeleteForm($image);
        $editForm = $this->createForm('AppBundle\Form\ImagesType', $image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('images_edit', array('id' => $image->getId()));
        }

        return $this->render('images/edit.html.twig', array(
            'image' => $image,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Images entity.
     *
     * @Route("/{id}", name="images_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Images $image)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('images_index');
    }

    /**
     * Creates a form to delete a Images entity.
     *
     * @param Images $image The Images entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Images $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('images_delete', array('id' => $image->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
