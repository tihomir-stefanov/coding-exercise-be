<?php

namespace App\Controller;

use App\Entity\Award;
use App\Form\UserFormType;
use App\Repository\AwardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{

    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    public function index(AwardRepository $awardRepository, UserInterface $user): Response
    {
        //TODO: we could have one object for user and student since it is the same person (ont to one in entity)
        /** @var null|Award $award */
        $award = $awardRepository->findOneByLevel($user->getStudent()->getLevel());

        return $this->render('dashboard.html.twig', [
            'user' => $user,
            'award' => $award,
        ]);
    }

    #[Route('/user', name: 'app_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        UserInterface $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        $form = $this->createForm(UserFormType::class, $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();

                $plainPassword =  $form->get('plainPassword')->getData();

                if($plainPassword) {
                    $hashedPassword = $passwordHasher->hashPassword(
                        $user,
                        $plainPassword
                    );
                    $user->setPassword($hashedPassword);
                }

                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    'Your changes were saved!'
                );
                return $this->redirectToRoute('app_dashboard');
            }
        }
        return $this->render('user/edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
