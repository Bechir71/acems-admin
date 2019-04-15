<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Controller to manage member.
 *
 * @Route("/user")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class MemberController extends AbstractController
{

    /**
     * @Route("/add", name="admin_add_user")
     */
    public function add(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user)
            ->add('saveAndCreateNew', SubmitType::class, [
                'label' => 'Valider et ajouter un nouveau membre'
            ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a été ajouté.");

            if($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_add_user');
            } else {
                return $this->redirectToRoute('admin_users');
            }
        }
        
        return $this->render('admin/user/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

}