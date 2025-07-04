<?php

namespace App\Controller\User\Profile;

use App\Entity\User;
use App\Form\EditPasswordFormType;
use App\Form\EditProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class ProfileController extends AbstractController
{
    #[Route('/profile/index', name: 'app_user_profile_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/user/profile/index.html.twig', [
        ]);
    }

    #[Route('/profile/edit-profile', name: 'app_user_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User
         */
        $user = $this->getUser();

        $form = $this->createForm(EditProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('app_user_profile_index');
        }

        return $this->render('pages/user/profile/edit_profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/edit-password', name: 'app_user_profile_edit_password', methods: ['GET', 'POST'])]
    public function editPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $form = $this->createForm(EditPasswordFormType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User
             */
            $user = $this->getUser();

            $data = $form->getData();

            $passwordHashed = $hasher->hashPassword($user, $data['plainPassword']);

            $user->setPassword($passwordHashed);
            // $user->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour.');

            return $this->redirectToRoute('app_user_profile_index');
        }

        return $this->render('pages/user/profile/edit_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
