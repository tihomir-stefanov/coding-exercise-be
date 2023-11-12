<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\AppointmentRepository;
use App\Repository\AwardRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/dashboard/{id}', name: 'app_dashboard')]
    public function index(
        UserRepository $userRepository,
        AppointmentRepository $appointmentRepository,
        AwardRepository $awardRepository,
        int $id
    ): Response
    {
        $user = $userRepository->findOneById($id);
        $award = $awardRepository->findOneByLevel($user->getStudent()->getLevel());

        $appointments = $appointmentRepository->createQueryBuilder('a')
            ->join('a.student', 's')
            ->join('s.user', 'u')
            ->andWhere('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return $this->render('dashboard.html.twig', [
            'user' => $user,
            'appointments' => $appointments,
            'award' => $award,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_edit')]
    public function edit(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        int $id
    ): Response
    {
        $user = $userRepository->findOneById($id);

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard', ['id' => $user->getId()]);
        }

        return $this->render('edit.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
