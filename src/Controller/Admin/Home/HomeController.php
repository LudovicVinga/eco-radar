<?php

namespace App\Controller\Admin\Home;

use App\Repository\CategoryRepository;
use App\Repository\ContactRepository;
use App\Repository\LabelRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_admin_home', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, LabelRepository $labelRepository, UserRepository $userRepository, ContactRepository $contactRepository): Response
    {
        $categories = $categoryRepository->count();
        $labels = $labelRepository->count();
        $users = $userRepository->count();
        $contacts = $contactRepository->count();

        return $this->render('pages/admin/home/index.html.twig', [
            'categories_count' => $categories,
            'labels_count' => $labels,
            'users_count' => $users,
            'contacts_count' => $contacts,
        ]);
    }
}
