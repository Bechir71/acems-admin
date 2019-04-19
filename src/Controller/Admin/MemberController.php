<?php

namespace App\Controller\Admin;

use App\Entity\ {
    UFR,
    Post,
    User,
    Level,
    Address,
    UsersData
};

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Form\UserType;
use App\Form\UsersDataType;

/**
 * Controller to manage member.
 *
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class MemberController extends AbstractController
{
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
     * @Route("/details/{user}", name="admin_user_show")
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
     * @Route("/complete", name="admin_user_complete_profile")
     */
    public function completeProfile(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été mis à jour.');
            return $this->redirectToRoute('admin_user_show', [
                'user' => $user->getId()
            ]);
        }

        return $this->render('admin/user/complete.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/list", name="admin_users")
     */
    public function users(Request $request) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getMembers(1);
        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     *  List users pagination
     *
     * @Route("/list/page/{page}", name="admin_users_paginated", requirements={"page"="\d+"})
     */
    public function usersPaginate(Request $request, $page) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getMembers($page);
        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     * @Route("/add", name="admin_add_user")
     * @IsGranted("ROLE_ADMIN")
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

    /**
     * @Route("/load", name="load_users_from_excel")
     * @IsGranted("ROLE_ADMIN")
     */
    public function load(Request $request): Response
    {
        $usersData = new UsersData();
        $form = $this->createForm(UsersDataType::class, $usersData);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        
        if($form->isSubmitted() && $form->isValid()) {
            $file = $usersData->getExcel();

            if(!in_array($file->guessExtension(), ['csv', 'xlsx', 'xls'])) {
                $this->addFlash('danger', 'Le fichier excel est invalide.');
                return $this->redirectToRoute('admin_users');
            }

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $directory = $this->getParameter('excels_directory');

            try {
                $file->move($directory, $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                throw new \Exception("Can't upload '$fileName' file");
            }

            $fileName = $directory . '/' . $fileName;
            $extension = substr($fileName, strpos($fileName, '.') + 1);
            $reader = IOFactory::createReader(ucfirst($extension));

            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileName);
            $worksheet = $spreadsheet->getActiveSheet();

            $highestRow = (int)($worksheet->getHighestDataRow() / 3) - 1;
            $ufrs = [];
            $addresses = [];
            $levels = [];

            $objects = $em->getRepository(Address::class)->findAll();
            foreach($objects as $obj) {
                $addresses[strtoupper($obj->getName())] = $obj;
            }

            $objects = $em->getRepository(UFR::class)->findAll();
            foreach($objects as $obj) {
                $ufrs[strtoupper($obj->getName())] = $obj;
            }

            $objects = $em->getRepository(Level::class)->findAll();
            foreach($objects as $obj) {
                $levels[strtoupper($obj->getName())] = $obj;
            }

            for($rowIt = 2; $rowIt <= $highestRow; $rowIt++) {
                $data = $worksheet->rangeToArray(
                    "A$rowIt:G$rowIt",  // The worksheet range that we want to retrieve
                    NULL,               // Value that should be returned for empty cells
                    TRUE,               // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,               // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE                // Should the array be indexed by cell row and cell column
                )[$rowIt];

                $user = (new User())
                    ->setUsername($data['A'])
                    ->setAddress(null != $data['B'] ? $addresses[strtoupper($data['B'])] : null)
                    ->setRoom($data['C'])
                    ->setUfr(null != $data['D'] ? $ufrs[strtoupper($data['D'])] : null)
                    ->setLevel(null != $data['E'] ? isset($levels[strtoupper($data['E'])]) ? $levels[strtoupper($data['E'])] : null : null)
                    ->setPhone($data['F'])
                    ->setMembershipFee(strtoupper($data['G']) == 'OUI')
                ;

                $em->persist($user);
            }

            $em->flush();

            $this->addFlash('success', "Le fichier a été importé.");
            return $this->redirectToRoute('admin_users');
        }
        
        return $this->render('admin/common/upload-excel-file.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function generateUniqueFileName(): string
    {
        return md5(uniqid());
    }

}
