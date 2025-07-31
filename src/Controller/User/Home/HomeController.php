<?php

namespace App\Controller\User\Home;

use App\Entity\User;
use App\Entity\ContactPremium;
use App\Service\SendEmailService;
use App\Form\PremiumContactFormType;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/user')]
final class HomeController extends AbstractController
{
    public function __construct(
        private SendEmailService $sendEmailService,
    ) {
    }

    #[Route('/home', name: 'app_user_home', methods: ['GET', 'POST'])]
    public function index(Request $request, SettingRepository $settingRepository, EntityManagerInterface $entityManager): Response
    {
        $contactPremium = new ContactPremium();

        $form = $this->createForm(PremiumContactFormType::class, $contactPremium);
        $form->handleRequest($request);


        /**
         * @var User
         */
        $user = $this->getUser();

        if (null != $user) {
            if ($user->getEmail() == $contactPremium->getEmail()) {
                $contactPremium->setUser($user);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $contactPremium->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($contactPremium);
            $entityManager->flush();

            $this->sendEmailService->send([
                'sender_email' => 'eco-radar@gmail.com',
                'sender_full_name' => 'Eve Florien',
                'recipient_email' => 'eco-radar@gmail.com',
                'subject' => "PREMIUM : un message d'un contact Premium vous a été envoyé.",
                'html_template' => 'emails/contact_premium.html.twig',
                'context' => [
                    'contactPremium' => $contactPremium,
                ],
            ]);

            $this->addFlash('success', 'Merci ! Votre message à bien été envoyé, nous reviendrons vers vous très prochainement.');

            return $this->redirectToRoute('app_user_home');
        } 

        // Pour le footer
        $settings = $settingRepository->findAll();
        $setting = $settings[0];

        return $this->render('pages/user/home/index.html.twig', [
            'form' => $form->createView(),
            'setting' => $setting,
        ]);
    }
}
