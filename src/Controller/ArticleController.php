<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\HandlerArticle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article", name="article_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();

        return $this->render('article/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, HandlerArticle $handlerArticle): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $handlerArticle->add($post);

            // post traitement
            return $this->redirectToRoute('article_index');
        }

        return $this->renderForm('article/edit.html.twig', [
            'form' => $form
        ]);
    }
}
