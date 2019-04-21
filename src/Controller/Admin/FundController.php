<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\BalanceSheet;

/**
 * @Route("/fund")
 * @IsGranted("ROLE_ADMIN")
 */
class FundController extends AbstractController
{
    /**
     * @Route("/{schoolYear}", name="admin_fund_index", defaults={"schoolYear"="current"})
     */
    public function index($schoolYear)
    {
        $em = $this->getDoctrine()->getManager();
        $balanceSheet = null;

        if($schoolYear == 'current')
            $balanceSheet = $em->getRepository(BalanceSheet::class)->getCurrent();
        else {
            $balanceSheet = $em->getRepository(BalanceSheet::class)->getBySchoolYear($schoolYear);
        }

        return $this->render('admin/fund/index.html.twig', [
            'balanceSheet' => $balanceSheet,
        ]);
    }
}
