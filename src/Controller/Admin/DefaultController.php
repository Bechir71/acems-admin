<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Gender;

/**
 * Controller to manage default admin pages.
 *
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="admin_index")
     */
    public function index(Request $request): Response
    {
        return $this->render('admin/default/index.html.twig', [
            'data' => $this->getStats()
        ]);
    }

    /**
     * @Route("/basic-usage", name="admin_basic_usage")
     */
    public function basicUsage(Request $request) : Response
    {
        return $this->render('admin/default/basicUsage.html.twig');
    }

    /**
     * @Route("/security", name="admin_security")
     */
    public function security(Request $request) : Response
    {
        return $this->render('admin/default/security.html.twig');
    }

    /**
     * @Route("/troubleshooting", name="admin_troubleshooting")
     */
    public function troubleshooting(Request $request) : Response
    {
        return $this->render('admin/default/troubleshooting.html.twig');
    }

    /**
     * @Route("/settings", name="admin_settings")
     */
    public function settings(Request $request) : Response
    {
        return $this->render('admin/default/settings.html.twig');
    }

    /**
     * @Route("/demo/{name}", name="admin_page_demo")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function pageDemo($name) : Response
    {
        return $this->render("admin/demo/$name.html.twig");
    }

    public function getStats()
    {
        $em = $this->getDoctrine()->getManager();


        $qb = $em->createQueryBuilder();

        $activeUsers = $qb->select('count(u.id)')
            ->from('App:User', 'u')
            ->getQuery()
            ->getSingleScalarResult();
        
        $students = $em->getRepository(User::class)->getAllMembers();
        $ufrStats = [];
        $membershipFeeCount = 0;
        $newUsers = 0;
        $nbMales = 0;
        $nbFemales = 0;

        foreach($students as $student) {
            $key = $student->getUfr() ? $student->getUfr()->getName() : 'Autre';

            if(!array_key_exists($key, $ufrStats)) {
                $ufrStats[$key] = 0;
            }
            $ufrStats[$key] += 1;

            if($student->isMembershipFee()) {
                $membershipFeeCount++;
            }

            if($student->isBruise()) {
                $newUsers++;
            }

            if($student->getGender() && $student->getGender()->getValue() == Gender::FEMALE) {
                $nbFemales++;
            } 
        }

        $count = count($students);
        $nbMales = round((($count - $nbFemales) * 100) / ($count ?: 1), 1);
        $nbFemales = round(($nbFemales * 100) / ($count ?: 1), 1);

        foreach ($ufrStats as $name => $value) {
            $ufrStats[$name] = round(($value * 100) / ($count ?: 1), 2);
        }
        
        array_multisort($ufrStats, SORT_DESC);

        return [
            'nbUsers'   => $activeUsers - 1,
            'ufrStats' => $ufrStats,
            'membershipFeeCount' => $membershipFeeCount,
            'newUsers' => $newUsers,
            'males' => $nbMales,
            'females' => $nbFemales
        ];
    }
}
