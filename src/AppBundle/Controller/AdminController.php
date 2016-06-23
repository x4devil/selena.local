<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin")
 */
class AdminController extends Controller {
    
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="admin")
     * @Method("GET")
     */
    public function indexAction()
    {
         return $this->redirectToRoute('product_index');
    }
}
