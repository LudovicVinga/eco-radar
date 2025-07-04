<?php

namespace App\Controller\User\Label;

use App\Repository\CategoryRepository;
use App\Repository\LabelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class LabelController extends AbstractController
{
    #[Route('/label/index', name: 'app_user_label_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, LabelRepository $labelRepository): Response
    {
        $categories = $categoryRepository->findall();
        $labels = $labelRepository->findBy(['isPublished' => true], ['publishedAt' => 'DESC']);

        return $this->render('pages/user/label/index.html.twig', [
            'categories' => $categories,
            'labels' => $labels,
        ]);
    }
}
