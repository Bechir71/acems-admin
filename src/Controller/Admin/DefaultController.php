<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * Profile page
     *
     * @Route("/profile", name="admin_profile")
     */
    public function profile(Request $request) : Response
    {
        return $this->show($request, $this->getUser());
    }

    /**
     * @Route("/users", name="admin_users")
     */
    public function users(Request $request) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getUsers(1);
        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     *  List users pagination
     *
     * @Route("/users/page/{page}", name="admin_users_paginated", requirements={"page"="\d+"})
     */
    public function usersPaginate(Request $request, $page) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getUsers($page);
        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     * @Route("/settings", name="admin_settings")
     */
    public function settings(Request $request) : Response
    {
        return $this->render('admin/default/settings.html.twig');
    }

    /**
     * @Route("/user/details/{user}", name="admin_user_show")
     */
    public function show(Request $request, User $user) : Response
    {
        if (!$user) {
            return $this->render('admin/default/404.html.twig');
        }
        return $this->render('admin/user/show.html.twig', [
            'user' => $user
        ]);
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

        return [
            'nbUsers'   => $activeUsers - 1,
        ];
    }
}
