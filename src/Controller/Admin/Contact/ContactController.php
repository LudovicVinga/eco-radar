<?php

namespace App\Controller\Admin\Contact;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class ContactController extends AbstractController
{
    #[Route('/contact/index', name: 'app_admin_contact_index', methods: ['GET'])]
    public function index(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();

        return $this->render('pages/admin/contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/contact/delete/{id<\d+>}', name: 'app_admin_contact_delete', methods: ['POST'])]
    public function delete(Contact $contact, Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid("delete-contact-{$contact->getId()}", $request->request->get('csrf_token'))) {
            $contactName = "{$contact->getFirstName()} {$contact->getLastName()}";

            $entityManager->remove($contact);
            $entityManager->flush();

            $this->addFlash('success', "Le contact [{$contactName}] a bien été supprimé.");
        }

        return $this->redirectToRoute('app_admin_contact_index');
    }
}
