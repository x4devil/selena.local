<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;

/**
 * Product controller.
 *
 * @Route("/product")
 */
class ProductController extends Controller {

    protected $activePage = 'product';
    protected $PAGE_SIZE = 10;
    protected $MAX_RESULT = 5000;

    /**
     * Lists all Product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $categorys = $em->getRepository('AppBundle:Category')->findAll();
        $page = ($request->get('page') != null && $request->get('page') != 0) ? $request->get('page') : 1;

        if ($page == 1) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $this->PAGE_SIZE;
        }

        $count = $this->getPageCount(
                count($this->getProductWithOffset(
                                $request->get('category'), $request->get('name'), 0, $this->MAX_RESULT)));

        $products = $this->getProductWithOffset(
                $request->get('category'), $request->get('name'), $offset, $this->PAGE_SIZE);

        return $this->render('product/index.html.twig', array(
                    'products' => $products,
                    'page' => $page,
                    'category' => $request->get('category') != null ? $request->get('category') : 0,
                    'name' => $request->get('name'),
                    'categorys' => $categorys,
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

    private function getProductWithOffset($category, $name, $offset, $size) {
        $em = $this->getDoctrine()->getManager();
        $productRepo = $em->getRepository('AppBundle:Product');


        $productQuery = $productRepo->createQueryBuilder('p');
        $productQuery
                ->leftJoin('p.images', 'i')
                ->join('p.category', 'c')
                ->distinct()
                ->where('1=1');

        if ($category != null && $category != 0) {
            $productQuery->andWhere($productQuery->expr()->eq('c.id', $category));
        }
        if ($name != null && $name != '') {
            $productQuery->andWhere($productQuery->expr()->like('p.name', '\'%' . $name . '%\''));
        }

        $productQuery
                ->setFirstResult($offset)
                ->setMaxResults($size);
        return $productQuery->getQuery()->getResult();
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $product = new Product();
        $form = $this->createForm('AppBundle\Form\ProductType', $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', array(
                    'product' => $product,
                    'form' => $form->createView(),
                    'activePage' => $this->activePage,
        ));
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Product $product) {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm('AppBundle\Form\ProductType', $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
                    'product' => $product,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
                    'activePage' => $this->activePage,
        ));
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Product $product) {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Creates a form to delete a Product entity.
     *
     * @param Product $product The Product entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Product $product) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
