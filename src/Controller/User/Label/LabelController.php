<?php

namespace App\Controller\User\Label;

use App\Entity\Category;
use App\Entity\Label;
use App\Repository\CategoryRepository;
use App\Repository\LabelRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
final class LabelController extends AbstractController
{
    #[Route('/label/index', name: 'app_user_label_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, LabelRepository $labelRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $categories = $categoryRepository->findall();
        $query = $labelRepository->findBy(['isPublished' => true], ['publishedAt' => 'DESC']);

        $labels = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            2 /* limit per page */
        );

        return $this->render('pages/user/label/index.html.twig', [
            'categories' => $categories,
            'labels' => $labels,
        ]);
    }

    #[Route('/label/filtre-par-categorie/{id<\d+>}/{slug}', name: 'app_user_label_filter_by_category', methods: ['GET'])]
    public function labelsFilterByCategory(Category $category, LabelRepository $labelRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findall();
        $labels = $labelRepository->findBy(['category' => $category], ['isPublished' => 'DESC']);

        return $this->render('pages/user/label/index.html.twig', [
            'labels' => $labels,
            'categories' => $categories,
        ]);
    }

    #[Route('/label/article/{id<\d+>}/{slug}', name: 'app_user_label_show', methods: ['GET'])]
    public function showLabel(Label $label): Response
    {
        return $this->render('pages/user/label/show.html.twig', [
            'label' => $label,
        ]);
    }
}
