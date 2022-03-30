<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\HandlerArticle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/{id}", name="detail", requirements={"id"="\d+"})
     */
    public function detail(Post $post): Response
    {
        return $this->render('article/detail.html.twig', [
            'post' => $post
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

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Post $post, HandlerArticle $handlerArticle): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $handlerArticle->edit();

            // post traitement
            return $this->redirectToRoute('article_edit', ['id' => $post->getId()]);
        }

        return $this->renderForm('article/edit.html.twig', [
            'form' => $form
        ]);
    }
}
