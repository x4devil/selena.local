<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;

/**
 * @Route("/admin")
 */
class AdminController extends Controller {

    protected $activePage = 'user';

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin")
     * @Method("GET")
     */
    public function indexAction() {
        return $this->redirectToRoute('product_index');
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request) {
        $user = $this->get('security.context')->getToken()->getUser();
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        $errors = null;
        $success = null;
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $encoderService = $this->get('security.encoder_factory');
            $encoder = $encoderService->getEncoder($user);

            $oldPassword = $request->get('old-password');
            $newPassword = $request->get('new-password');

            if (($oldPassword == null || $oldPassword == '') && ($newPassword == null || $newPassword == '')) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $success = 'Пользователь сохранен';
                $errors = null;
            } else if ($oldPassword != null && $oldPassword != '' && ($newPassword == null || $newPassword == '')) {
                $errors = 'Новый пароль не может быть пустым';
            } else if ($oldPassword != null && $oldPassword != '' && $newPassword != null && $newPassword != '') {
                $encodeOldPassword = $encoder->encodePassword($oldPassword, $user->getSalt());
                if ($encodeOldPassword == $user->getPassword()) {
                    $encodeNewPassword = $encoder->encodePassword($newPassword, $user->getSalt());
                    $user->setPassword($encodeNewPassword);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                    $success = 'Пользователь сохранен';
                    $errors = null;
                } else {
                    $errors = 'Старый пароль не совпадает';
                    $success = null;
                }
            } else {
                $errors = 'Старый пароль не совпадает';
                $success = null;
            }
        }
        return $this->render('user/edit.html.twig', array(
                    'user' => $user,
                    'errors' => $errors,
                    'success' => $success,
                    'edit_form' => $editForm->createView(),
                    'activePage' => $this->activePage,
        ));
    }

}
