<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
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
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("SelenaExcelRobot")
                ->setTitle("Заявка №" . $userRequest->getId())
                ->setCategory("Заявки");

        $sheet = $phpExcelObject->setActiveSheetIndex(0);
        $this->createSheetHeader($phpExcelObject, $sheet, $userRequest);
        $this->createSheetTable($phpExcelObject, $sheet, $userRequest);

        $phpExcelObject->getActiveSheet()->setTitle('Заявка №' . $userRequest->getId());
        $phpExcelObject->setActiveSheetIndex(0);

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'stream-file.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

    private function createSheetHeader($phpExcelObject, $sheet, UserRequest $userRequest) {
        $sheet->setCellValue('C1', 'Заявка № ' . $userRequest->getId())
                ->setCellValue('A2', 'Заказчик')
                ->setCellValue('A3', 'Номер телефона')
                ->setCellValue('B2', $userRequest->getLastname() . ' ' . $userRequest->getName() . ' ' . $userRequest->getMiddlename())
                ->setCellValue('B3', $userRequest->getPhone())
        ;
        $sheet->setCellValue('D2', 'Дaта заказа')
                ->setCellValue('D3', 'Электронная почта')
                ->setCellValue('D4', 'Компания')
                ->setCellValue('E2', $userRequest->getCreated())
                ->setCellValue('E3', $userRequest->getEmail())
                ->setCellValue('E4', $userRequest->getCompany() != null ? $userRequest->getCompany() : '')
        ;
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);

        $this->cellFontStyle($phpExcelObject, 'A2:A3', true);
        $this->cellFontStyle($phpExcelObject, 'D2:D4', true);
    }

    private function createSheetTable($phpExcelObject, $sheet, UserRequest $userRequest) {
        $startIndex = 6;
        $sheet->setCellValue('A' . $startIndex, '№')
                ->setCellValue('B' . $startIndex, 'Название')
                ->setCellValue('C' . $startIndex, 'Количество')
                ->setCellValue('D' . $startIndex, 'Стоимость')
                ->setCellValue('E' . $startIndex, 'Сумма')
        ;

        $index = $startIndex + 1;
        $sum = 0;
        foreach ($userRequest->getUserRequestProduct() as $p) {
            $sheet->setCellValue('A' . $index, $index - $startIndex)
                    ->setCellValue('B' . $index, $p->getProduct()->getName())
                    ->setCellValue('C' . $index, $p->getAmount())
                    ->setCellValue('D' . $index, $p->getPrice())
                    ->setCellValue('E' . $index, $p->getPrice() * $p->getAmount())
            ;
            $sum += $p->getPrice() * $p->getAmount();
            $index++;
        }
        $sheet->setCellValue('D' . $index, 'Итог');
        $sheet->setCellValue('E' . $index, $sum);
        $index++;

        $headerColorHex = '4F81BD';
        $this->cellBackGroundColor($phpExcelObject, 'A' . $startIndex . ':' . 'E' . $startIndex, $headerColorHex);
        $this->cellBorder($phpExcelObject, 'A' . $startIndex . ':' . 'E' . ($index - 2));
        $this->cellBorder($phpExcelObject, 'D' . ($index - 1) . ':' . 'E' . ($index - 1));
        $this->cellFontStyle($phpExcelObject, 'A' . $startIndex . ':' . 'E' . $startIndex, true);
    }

    private function cellBackGroundColor($phpExcelObject, $cells, $color) {
        $phpExcelObject->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'type' => 'solid',
            'startcolor' => array(
                'rgb' => $color
            )
        ));
    }

    private function cellFontStyle($phpExcelObject, $cells, $bold, $size = 11) {
        $phpExcelObject->getActiveSheet()->getStyle($cells)->applyFromArray(array(
            'font' => array(
                'bold' => $bold,
                'size' => $size
            )
        ));
    }

    private function cellTextAlign($phpExcelObject, $cells, $aligment) {
        $phpExcelObject->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
            'alignment' => array(
                'horizontal' => $aligment, //PHPExcel_Style_Alignment::HORIZONTAL_LEFT
            )
        ));
    }

    private function cellBorder($phpExcelObject, $cells) {
        $phpExcelObject->getActiveSheet()->getStyle($cells)->applyFromArray(array(
            'borders' => array(
                'allborders' => array(
                    'style' => 'medium',
                    'color' => array('rgb' => '000000'),
                )
            )
        ));
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
