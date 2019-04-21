<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/balance-sheet")
 */
class BalanceSheetController extends AbstractController
{
    /**
     * @Route("/", name="balance_sheet")
     */
    public function index()
    {
        return $this->render('balance_sheet/index.html.twig', [
            'controller_name' => 'BalanceSheetController',
        ]);
    }
}
