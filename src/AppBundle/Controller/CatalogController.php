<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Paginator;

/**
 *
 * @Route("/catalog")
 */
class CatalogController extends Controller {

    protected $activePage = 'catalog';
    protected $DEFAULT_PAGES = 5;
    protected $MAX_RESULT = 5000;
    protected $DEFAULT_CATEGORY = 0;
    protected $DEFAULT_SIZE = 10;
    protected $DEFAULT_PAGE = 1;
    protected $DEFAULT_ORDER = 'p.price';
    protected $DEFAULT_DIRECT = 'asc';

    /**
     *
     * @Route("/", name="catalog_index")
     * @Method("GET")
     */
    public function indexAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $category = ($request->get('c') != null && $request->get('c') != 0) ? $request->get('c') : $this->DEFAULT_CATEGORY;
        $page = ($request->get('p') != null && $request->get('p') != 0) ? $request->get('p') : $this->DEFAULT_PAGE;
        $size = ($request->get('s') != null && $request->get('s') != 0) ? $request->get('s') : $this->DEFAULT_SIZE;
        $order = ($request->get('o') != null && $request->get('o') != '') ? $request->get('o') : $this->DEFAULT_ORDER;
        $direct = ($request->get('d') != null && $request->get('d') != '') ? $request->get('d') : $this->DEFAULT_DIRECT;


        $categories = $em->getRepository('AppBundle:Category')->findAll();



        if ($page == 1) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $size;
        }

        $count = $this->getPageCount(
                count($this->getProductWithOffset(
                                $category, 0, $this->MAX_RESULT, $this->DEFAULT_ORDER, $this->DEFAULT_DIRECT)), $size);

        $products = $this->getProductWithOffset(
                $category, $offset, $size, $order, $direct);


        return $this->render('catalog/index.html.twig', array(
                    'categories' => $categories,
                    'products' => $products,
                    'count' => $count,
                    'activePage' => $this->activePage,
                    'page' => $page,
                    'size' => $size,
                    'order' => $order,
                    'direct' => $direct,
                    'category' => $category,
                    'paginator' => $this->getPaginator($count, $page, $this->DEFAULT_PAGES),
        ));
    }

    private function getPageCount($count, $size) {
        if ($count != 0) {
            if ($count % $size == 0) {
                $count = (int) ($count / $size);
            } else {
                $count = (int) ($count / $size) + 1;
            }
        }
        return $count;
    }

    private function getProductWithOffset($category, $offset, $size, $order, $direct) {
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

        $productQuery
                ->setFirstResult($offset)
                ->setMaxResults($size)
                ->orderBy($order, $direct);
        return $productQuery->getQuery()->getResult();
    }

    private function getPaginator($count, $page) {
        $paginator = new Paginator();
        $offset = 2;
        if ($count != 0) {
            $paginator->setActivePage($page);
            $paginator->setPrevPage($page - 1);
            $paginator->setNextPage($page + 1);


            $start = $page - $offset;
            $end = $page + $offset;

            if ($start <= 0) {
                $start = 1;
            }

            if ($page == 1) {
                $end += $offset;
            } else if ($page == 2) {
                $end += $offset - 1;
            }
            
            if ($page == $count - 2) {
                $start -= $offset - 1;
            } else if ($page == $count - 1) {
                $start -= $offset;
            }

            if ($end >= $count) {
                $end = $count - 1;
            }
            
            if ($start <= 0) {
                $start = 1;
            }

            $paginator->setStartPage($start);
            $paginator->setEndPage($end);
        }
        return $paginator;
    }

}
