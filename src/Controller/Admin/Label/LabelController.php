<?php

namespace App\Controller\Admin\Label;

use App\Entity\Label;
use App\Entity\User;
use App\Form\AdminLabelFormType;
use App\Repository\LabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class LabelController extends AbstractController
{
    #[Route('/label/index', name: 'app_admin_label_index', methods: ['GET'])]
    public function index(LabelRepository $labelRepository): Response
    {
        $labels = $labelRepository->findAll();

        return $this->render('pages/admin/label/index.html.twig', [
            'labels' => $labels,
        ]);
    }

    #[Route('/label/create', name: 'app_admin_label_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $label = new Label();

        $form = $this->createForm(AdminLabelFormType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User
             */
            $user = $this->getUser();

            $label->setUser($user);
            $label->setCreatedAt(new \DateTimeImmutable());
            $label->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($label);
            $entityManager->flush();

            $this->addFlash('success', 'Le label a bien été crée');

            return $this->redirectToRoute('app_admin_label_index');
        }

        return $this->render('pages/admin/label/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/label/show/{id<\d+>}', name: 'app_admin_label_show', methods: ['GET'])]
    public function show(Label $label): Response
    {
        return $this->render('pages/admin/label/show.html.twig', [
            'label' => $label,
        ]);
    }

    #[Route('/label/edit/{id<\d+>}', name: 'app_admin_label_edit', methods: ['GET', 'POST'])]
    public function edit(Label $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminLabelFormType::class, $label);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User
             */
            $user = $this->getUser();

            $label->setUser($user);
            $label->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($label);
            $entityManager->flush();

            $this->addFlash('success', 'Le label a bien été modifié');

            return $this->redirectToRoute('app_admin_label_index');
        }

        return $this->render('pages/admin/label/edit.html.twig', [
            'label' => $label,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/label/delete/{id<\d+>}', name: 'app_admin_label_delete', methods: ['POST'])]
    public function delete(Label $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete-label-{$label->getId()}", $request->request->get('csrf_token'))) {
            $entityManager->remove($label);
            $entityManager->flush();

            $this->addFlash('success', 'Le label a bien été supprimé');
        }

        return $this->redirectToRoute('app_admin_label_index');
    }

    #[Route('/label/publish/{id<\d+>}', name: 'app_admin_label_publish', methods: ['POST'])]
    public function publish(Label $label, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Si il y a un problème avec le jeton de sécurité, fin du script
        if (!$this->isCsrfTokenValid("publish-label-{$label->getId()}", $request->request->get('csrf_token'))) {
            return $this->redirectToRoute('app_admin_label_index');
        }

        // Si l'article est déjà publié
        if ($label->isPublished()) {
            // On le retire de la liste des publication
            $label->setIsPublished(false);
            // On réinitialise la date de publication
            $label->setPublishedAt(null);

            $this->addFlash('success', 'Le label a été retiré de la liste.');
        } else {   // Si l'article n'est pas publié
            // On le publie
            $label->setIsPublished(true);
            $label->setPublishedAt(new \DateTimeImmutable());

            $this->addFlash('success', 'Le label a été ajouté a la liste.');
        }

        $entityManager->persist($label);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_label_index');
    }
}
