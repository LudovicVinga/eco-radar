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
}
