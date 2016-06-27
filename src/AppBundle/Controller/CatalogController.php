<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 *
 * @Route("/catalog")
 */
class CatalogController extends Controller {

    protected $activePage = 'category';

    /**
     *
     * @Route("/", name="catalog_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('catalog/index.html.twig', array(
                    'categories' => $categories,
                    'activePage' => $this->activePage,
        ));
    }

}
