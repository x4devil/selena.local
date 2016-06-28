<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\SessionRequest;
use AppBundle\Entity\UserRequest;
use AppBundle\Entity\UserRequestProduct;

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
        $sessionProducts = $this->getSessionProducts();

        $total = 0;
        foreach ($sessionProducts as $p) {
            $total += $p->getProduct()->getPrice() * $p->getAmount();
        }


        return $this->render('cart/index.html.twig', array(
                    'sessionProducts' => $sessionProducts,
                    'activePage' => $this->activePage,
                    'total' => $total,
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

        $sessionProducts = $this->getSessionProducts();

        $newSessionProducts = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($sessionProducts as $p) {
            if ($p->getProduct()->getId() == $product->getId()) {
                $amount += $p->getAmount();
            } else {
                $newSessionProducts->add($p);
            }
        }

        $newProduct = new SessionRequest();
        $newProduct
                ->setAmount($amount)
                ->setProduct($product)
        ;
        $newSessionProducts->add($newProduct);


        $session->set('products', $newSessionProducts);

        $responce['message'] = 'Товар был добавлен в корзину.<br> '
                . 'Товаров в Вашей корзине: ' . $newSessionProducts->count();
        $responce['code'] = 'success';
        return new JsonResponse(array('responce' => $responce));
    }

    /**
     *
     * @Route("/check/{id}", name="cart_del")
     * @Method("GET")
     */
    public function delAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:Product')->findOneBy(array('id' => $id));

        $session = $this->getRequest()->getSession();
        $sessionProducts = $this->getSessionProducts();

        foreach ($sessionProducts as $p) {
            if ($p->getProduct()->getId() == $product->getId()) {
                $sessionProducts->removeElement($p);
                break;
            }
        }

        $session->set('products', $sessionProducts);

        return $this->redirectToRoute('cart_index');
    }

    /**
     *
     * @Route("/check", name="check_index")
     * @Method("GET")
     */
    public function checkAction(Request $request) {
        $session = $this->getRequest()->getSession();
        $sessionProducts = $this->getSessionProducts();
        $total = 0;
        foreach ($sessionProducts as $p) {
            $total += $p->getProduct()->getPrice() * $p->getAmount();
        }
        if ($total == 0) { //Хитрый ублюдок, обмануть решил
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/check.html.twig', array(
                    'activePage' => $this->activePage,
                    'sessionProducts' => $sessionProducts,
                    'total' => $total,
        ));
    }

    /**
     *
     * @Route("/check/update", name="check_update")
     * @Method("PUT")
     */
    public function checkUpdateAction(Request $request) {
        $session = $this->getRequest()->getSession();
        $sessionProducts = $this->getSessionProducts();
        $newSessionProducts = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($sessionProducts as $p) {
            $amount = $request->get('amount-' . $p->getProduct()->getId());
            if ($amount != null && $amount != 0) {
                $newProduct = new SessionRequest();
                $newProduct
                        ->setAmount($amount)
                        ->setProduct($p->getProduct())
                ;
                $newSessionProducts->add($newProduct);
            } else {
                $newProduct = new SessionRequest();
                $newProduct
                        ->setAmount($p->getAmount())
                        ->setProduct($p->getProduct())
                ;
                $newSessionProducts->add($newProduct);
            }
        }
        $session->set('products', $newSessionProducts);

        return $this->redirectToRoute('check_index');
    }

    /**
     *
     * @Route("/check", name="check_request")
     * @Method("PUT")
     */
    public function checkRequestAction(Request $request) {
        $name = $request->get('name');
        $middlename = $request->get('middlename');
        $lastname = $request->get('lastname');
        $company = $request->get('company');
        $phone = $request->get('phone');
        $email = $request->get('email');

        $userRequest = new UserRequest();
        $userRequest
                ->setName($name)
                ->setMiddlename($middlename)
                ->setLastname($lastname)
                ->setCompany($company)
                ->setPhone($phone)
                ->setEmail($email)
                ->setCreated(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $productRepo = $em->getRepository('AppBundle:Product');
        $em->persist($userRequest);
        $em->flush();

        $sessionProducts = $this->getSessionProducts();
        foreach ($sessionProducts as $sp) {
            if ($sp != null && $sp->getProduct() != null && $sp->getAmount() != null && $sp->getAmount() > 0) {
                $urp = new UserRequestProduct();
                $urp
                        ->setUserRequest($userRequest)
                        ->setAmount($sp->getAmount())
                        ->setProduct($productRepo->findOneBy(array('id' => $sp->getProduct()->getId())))
                        ->setPrice($sp->getProduct()->getPrice());
                $em->persist($urp);
                $em->flush();
            }
        }
//        $this->getRequest()->getSession()->set('products', new \Doctrine\Common\Collections\ArrayCollection());

        $responce['message'] = 'Спасибо за заказ. Ваша заявка принята.<br> Номер вашей заявки: ' . $userRequest->getId();
        $responce['code'] = 'success';
        return new JsonResponse(array('responce' => $responce));
    }

    private function getSessionProducts() {
        $session = $this->getRequest()->getSession();
        $sessionProducts = $session->get('products');
        if ($sessionProducts == null) {
            $sessionProducts = new \Doctrine\Common\Collections\ArrayCollection();
        }

        return $sessionProducts;
    }

}
