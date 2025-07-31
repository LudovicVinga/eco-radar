<?php

namespace App\Controller\User\CreateLabel;

use App\Entity\ContactPremiumLabel;
use App\Entity\User;
use App\Form\PremiumContactLabelFormType;
use App\Repository\SettingRepository;
use App\Service\SendEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class CreateLabelController extends AbstractController
{
    public function __construct(
        private SendEmailService $sendEmailService,
    ) {
    }

    #[Route('/creer/label/', name: 'app_user_create_label', methods: ['GET', 'POST'])]
    public function create(SettingRepository $settingRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $contactPremiumLabel = new ContactPremiumLabel();

        $form = $this->createForm(PremiumContactLabelFormType::class, $contactPremiumLabel);
        $form->handleRequest($request);

        /**
         * @var User
         */
        $user = $this->getUser();

        if (null != $user) {
            $contactPremiumLabel->setUser($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $contactPremiumLabel->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($contactPremiumLabel);
            $entityManager->flush();

            $this->sendEmailService->send([
                'sender_email' => 'eco-radar@gmail.com',
                'sender_full_name' => 'Eve Florien',
                'recipient_email' => 'eco-radar@gmail.com',
                'subject' => 'PREMIUM LABEL : un contact vous propose un label.',
                'html_template' => 'emails/contact_premium_label_create.html.twig',
                'context' => [
                    'contactPremiumLabel' => $contactPremiumLabel,
                ],
            ]);

            $this->addFlash('success', 'Merci ! Votre label à bien été transmis, nous reviendrons vers vous très prochainement.');

            return $this->redirectToRoute('app_user_home');
        }

        // Pour le footer
        $settings = $settingRepository->findAll();
        $setting = $settings[0];

        return $this->render('pages/user/create_label/index.html.twig', [
            'setting' => $setting,
            'form' => $form->createView(),
        ]);
    }
}
