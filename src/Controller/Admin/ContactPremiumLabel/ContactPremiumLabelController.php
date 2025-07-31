<?php

namespace App\Controller\Admin\ContactPremiumLabel;

use App\Entity\ContactPremiumLabel;
use App\Repository\ContactPremiumLabelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class ContactPremiumLabelController extends AbstractController
{
    #[Route('/contact/premium/label/index', name: 'app_admin_contact_premium_label_index')]
    public function index(ContactPremiumLabelRepository $contactPremiumLabelRepository): Response
    {
        $contactsPremiumLabel = $contactPremiumLabelRepository->findAll();

        return $this->render('pages/admin/contact_premium_label/index.html.twig', [
            'contactsPremiumLabel' => $contactsPremiumLabel,
        ]);
    }

    #[Route('/contact/premium/label/delete/{id<\d+>}', name: 'app_admin_contact_premium_label_delete', methods: ['POST'])]
    public function delete(ContactPremiumLabel $contactPremiumLabel, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete-contactPremiumLabel-{$contactPremiumLabel->getId()}", $request->request->get('csrf_token'))) {
            $labelName = "{$contactPremiumLabel->getName()}";

            $entityManager->remove($contactPremiumLabel);
            $entityManager->flush();

            $this->addFlash('success', "Le label [{$labelName}] a bien été effacé de la liste des labels envoyé par vos contacts PREMIUM.");
        }

        return $this->redirectToRoute('app_admin_contact_premium_label_index');
    }
}
