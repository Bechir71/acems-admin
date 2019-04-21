<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\BalanceSheet;

/**
 * @Route("/balance-sheet")
 * @IsGranted("ROLE_ADMIN")
 */
class BalanceSheetController extends AbstractController
{
    /**
     * @Route("/", name="admin_balance_sheet_index")
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
