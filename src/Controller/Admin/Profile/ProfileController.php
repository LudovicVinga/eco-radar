<?php

namespace App\Controller\Admin\Profile;

use App\Entity\User;
use App\Form\EditPasswordFormType;
use App\Form\EditProfileFormType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin')]
final class ProfileController extends AbstractController
{
    #[Route('/profile/index', name: 'app_admin_profile_index', methods:['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/profile/index.html.twig', [
            
        ]);
    }

    #[Route('/profile/edit-profile', name: 'app_admin_profile_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User
         */
        $admin = $this->getUser();

        $form = $this->createForm(EditProfileFormType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $admin->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'Votre profil a bien été mis à jour.');

            return $this->redirectToRoute('app_admin_profile_index');
        }

        return $this->render('pages/admin/profile/edit_profile.html.twig', [
            "form" => $form->createView()
        ]);
    }

    #[Route('/profile/edit-password', name: 'app_admin_profile_edit_password', methods:['GET', 'POST'])]
    public function editPassword(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {

        $form = $this->createForm(EditPasswordFormType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /**
            * @var User
            */
            $admin = $this->getUser();

            $data = $form->getData();

            $passwordHashed = $hasher->hashPassword($admin, $data['plainPassword']);

            $admin->setPassword($passwordHashed);
            $admin->setUpdatedAt(new DateTimeImmutable());

            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour.');

            return $this->redirectToRoute('app_admin_profile_index');

        }

        return $this->render('pages/admin/profile/edit_password.html.twig', [
            "form" => $form->createView()
        ]);
    }

}