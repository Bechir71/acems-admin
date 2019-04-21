<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\BalanceSheet;

/**
 * @Route("/balance-sheet")
 */
class BalanceSheetController extends AbstractController
{
    /**
     * @Route("/", name="balance_sheet_index")
     */
    public function index()
    {
        $balanceSheet = $this->getDoctrine()->getRepository(BalanceSheet::class)->getCurrent();
        return $this->render('admin/balance-sheet/index.html.twig', [
            'balance' => $balanceSheet,
            'movements' => $balanceSheet ? $balanceSheet->getMovements() : null
        ]);
    }
}
