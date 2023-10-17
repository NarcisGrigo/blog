<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ArticleRepository $articleRepository, CategoryRepository $categoryRepository): Response
    {
        // liste des catégories
        $categories = $categoryRepository->findAll();

        // tableau pour stocker les informations de catégorie et le nombre d'articles
        $categoryData = [];

        foreach ($categories as $category) {
            $articles = $articleRepository->findBy(['category' => $category]);
            $articleCount = count($articles);

            $categoryData[] = [
                'category' => $category,
                'articleCount' => $articleCount,
            ];
        }


        // la liste des articles
        $articles = $articleRepository->findAll();

        return $this->render('home/home.html.twig', [
            'articles' => $articles,
            'categoryData' => $categoryData,
        ]);

    }

    #[Route('/show/{id}', name: 'app_show')]
    public function show(ArticleRepository $articleRepository, $id)
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        return $this->render('home/show.html.twig', ['article' => $article]);
    }
}