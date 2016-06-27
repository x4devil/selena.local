<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\SessionRequest;

/**
 *
 * @Route("/cart")
 */
class CartController extends Controller {

    protected $activePage = 'cart';

    /**
     *
     * @Route("/", name="cart_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $session = $this->getRequest()->getSession();
        $sessionProducts = $session->get('products');


        return $this->render('cart/index.html.twig', array(
                    'sessionProducts' => $sessionProducts,
                    'activePage' => $this->activePage,
        ));
    }

    /**
     *
     * @Route("/add", name="cart_add")
     * @Method("PUT")
     */
    public function addAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->findOneBy(array('id' => $request->get('product')));
        $amount = $request->get('amount');
        if (!$amount || $amount <= 0) {
            $amount = 1;
        }

        if (!$product) {
            $responce['message'] = 'Упс:( Мы не смогли найти нужный товар';
            $responce['code'] = 'danger';
            return new JsonResponse(array('responce' => $responce));
        }

        $session = $this->getRequest()->getSession();

        $sessionProducts = $session->get('products');
        if ($sessionProducts == null) {
            $sessionProducts = new \Doctrine\Common\Collections\ArrayCollection();
        }

        $sessionProduct = new SessionRequest();
        foreach ($sessionProducts as $p) {
            if ($p->getProduct()->getId() == $product->getId()) {
                $amount += $p->getAmount();
                $sessionProduct = $p;
                break;
            }
        }
        $sessionProduct
                ->setAmount($amount)
                ->setProduct($product)
        ;

        $sessionProducts->add($sessionProduct);
        $session->set('products', $sessionProducts);

        $responce['message'] = 'Товар был добавлен в корзину.<br> '
                . 'Товаров в Вашей корзине: ' . $sessionProducts->count();
        $responce['code'] = 'success';
        return new JsonResponse(array('responce' => $responce));
    }

    /**
     *
     * @Route("/{id}", name="cart_del")
     * @Method("DELETE")
     */
    public function delAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->findOneBy(array('id' => $id));

        $session = $this->getRequest()->getSession();
        $sessionProducts = $session->get('products');
        if ($sessionProducts == null) {
            $sessionProducts = new \Doctrine\Common\Collections\ArrayCollection();
        }
        
        foreach ($sessionProducts as $p) {
            if ($p->getProduct()->getId() == $product->getId()) {
                $sessionProducts->removeElement($p);
                break;
            }
        }
        
        $sessionProducts = $session->set('products', $sessionProducts);

        return $this->redirectToRoute('cart_index');
    }

}
