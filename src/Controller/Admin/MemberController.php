<?php

namespace App\Controller\Admin;

use App\Entity\ {
    UFR,
    Post,
    User,
    Level,
    Address,
    UsersData,
    Gender,
    BalanceSheet
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
        $form = $this->createForm(UserType::class, $user)->remove('post');

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($user->getPost() != null) {
                $user->addRole('ROLE_ADMIN');
            }

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
     * @Route("/{user}/edit", name="admin_user_edit")
     */
    public function editUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if($user->getPost() != null) {
                $user->addRole('ROLE_ADMIN');
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Le profil a été mis à jour.');
            return $this->redirectToRoute('admin_user_show', [
                'user' => $user->getId()
            ]);
        }

        return $this->render('admin/user/edit.html.twig', [
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
     * @Route("/search", name="admin_user_search")
     */
    public function searchUser(Request $request) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->findByUsername($request->query->get('username'));
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

            if($user->getPost() != null) {
                $user->addRole('ROLE_ADMIN');
            }

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
            $genders = [];

            $balanceSheet = $em->getRepository(BalanceSheet::class)->getCurrent();
            if(!$balanceSheet) {
                $balanceSheet = new BalanceSheet;
            }

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

            $objects = $em->getRepository(Gender::class)->findAll();
            foreach($objects as $obj) {
                $gender = $obj->getValue();

                if($gender == Gender::FEMALE) {
                    $genders['F'] = $obj;
                } else {
                    $genders['H'] = $obj;
                }
            }

            try {
                for($rowIt = 2; $rowIt <= $highestRow; $rowIt++) {
                    $data = $worksheet->rangeToArray(
                        "A$rowIt:I$rowIt",  // The worksheet range that we want to retrieve
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
                        ->setBruise(strtoupper($data['H']) == 'OUI')
                        ->setGender(null != $data['I'] ? $genders[strtoupper($data['I'])] : null)
                        ->setEnabled(true)
                    ;

                    if($user->isMembershipFee()) {
                        $balanceSheet->plus(BalanceSheet::COTISATION);
                    }

                    $em->persist($user);
                    $em->persist($balanceSheet);
                }
            } catch(\Exception $e) {
                $this->addFlas('danger', 'Le fichier excel est invalide.');
                return $this->redirectToRoute('admin_users');
            } finally {
                unlink($fileName);
            }
            $em->flush();

            $this->addFlash('success', "Le fichier a été importé.");
            return $this->redirectToRoute('admin_users');
        }
        
        return $this->render('admin/common/upload-excel-file.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/office", name="admin_office_members")
     * @IsGranted("ROLE_ADMIN")
     */
    public function officeMembers(Request $request): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->getOfficeMembers();

        return $this->render('admin/user/office-members.html.twig', [
            'users' => $users
        ]);
    }

    public function generateUniqueFileName(): string
    {
        return md5(uniqid());
    }

    public function membersCount(): Response
    {
        return new Response(
            $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('COUNT(u.id)')
            ->from('App:User', 'u')
            ->where("u.roles != 'ROLE_SUPER_ADMIN'")
            ->getQuery()
            ->getSingleScalarResult() - 1
        );
    }

}
