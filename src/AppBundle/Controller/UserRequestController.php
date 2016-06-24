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
class UserRequestController extends Controller {

    protected $activePage = 'userrequest';
    protected $PAGE_SIZE = 10;
    protected $MAX_RESULT = 5000;

    /**
     * Lists all UserRequest entities.
     *
     * @Route("/", name="userrequest_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $page = ($request->get('page') != null && $request->get('page') != 0) ? $request->get('page') : 1;

        if ($page == 1) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $this->PAGE_SIZE;
        }

        $count = $this->getPageCount(
                count($this->getRequestWithOffset(0, $this->MAX_RESULT)));
        $userRequests = $this->getRequestWithOffset($offset, $this->PAGE_SIZE);

        return $this->render('userrequest/index.html.twig', array(
                    'userRequests' => $userRequests,
                    'page' => $page,
                    'count' => $count,
                    'activePage' => $this->activePage,
        ));
    }

    private function getPageCount($count) {
        if ($count != 0) {
            if ($count % $this->PAGE_SIZE == 0) {
                $count = (int) ($count / $this->PAGE_SIZE);
            } else {
                $count = (int) ($count / $this->PAGE_SIZE) + 1;
            }
        }
        return $count;
    }

    private function getRequestWithOffset($offset, $size) {
        $em = $this->getDoctrine()->getManager();
        $productRepo = $em->getRepository('AppBundle:UserRequest');


        $productQuery = $productRepo->createQueryBuilder('r')
                ->setFirstResult($offset)
                ->setMaxResults($size);

        return $productQuery->getQuery()->getResult();
    }

    /**
     * Creates a new UserRequest entity.
     *
     * @Route("/new", name="userrequest_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
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
                    'activePage' => $this->activePage,
        ));
    }

    /**
     * Finds and displays a UserRequest entity.
     *
     * @Route("/{id}", name="userrequest_show")
     * @Method("GET")
     */
    public function showAction(UserRequest $userRequest) {
        $deleteForm = $this->createDeleteForm($userRequest);

        return $this->render('userrequest/show.html.twig', array(
                    'userRequest' => $userRequest,
                    'delete_form' => $deleteForm->createView(),
                    'activePage' => $this->activePage,
        ));
    }

    /**
     * Displays a form to edit an existing UserRequest entity.
     *
     * @Route("/{id}/edit", name="userrequest_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, UserRequest $userRequest) {
        $em = $this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($userRequest);

        $products = $userRequest->getUserRequestProduct();
        $sum = 0;
        foreach ($products as $p) {
            $sum += $p->getAmount() * $p->getPrice();
        }

        if ($request->isMethod('POST')) {
            $userRequest->setStatus($request->get('status'));
            $em->persist($userRequest);
            $em->flush();
            return $this->redirectToRoute('userrequest_index');
        }

        return $this->render('userrequest/edit.html.twig', array(
                    'userRequest' => $userRequest,
                    'delete_form' => $deleteForm->createView(),
                    'sum' => $sum,
                    'activePage' => $this->activePage,
        ));
    }

    /**
     * @Route("/{id}/excel", name="userrequest_excel")
     * @Method("GET")
     */
    public function excelAction(UserRequest $userRequest) {
        
    }

    /**
     * Deletes a UserRequest entity.
     *
     * @Route("/{id}", name="userrequest_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, UserRequest $userRequest) {
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
    private function createDeleteForm(UserRequest $userRequest) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('userrequest_delete', array('id' => $userRequest->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
