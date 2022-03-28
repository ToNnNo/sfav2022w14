<?php

namespace App\Controller;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @Route("/cache", name="cache_")
 */
class CacheController extends AbstractController
{
    private $baseUrlApi;
    private $http;

    public function __construct(string $baseUrlApi, HttpClientInterface $http)
    {
        $this->baseUrlApi = $baseUrlApi;
        $this->http = $http;
    }

    /**
     * @Route("/psr-6", name="psr6")
     */
    public function psr6(Request $request, CacheItemPoolInterface $cache): Response
    {

        $item = $cache->getItem('psr6_jsonplaceholder_typicode_com_users');
        if( !$item->isHit() ) {
            $response = $this->http->request('GET', $this->baseUrlApi."/users");
            $item->set($response->toArray());
            $item->expiresAfter(60);
            $cache->save($item);
        }
        $users = $item->get();

        if ('delete' === $request->query->get('cache')) {
            $cache->deleteItem('psr6_jsonplaceholder_typicode_com_users');
            return $this->redirectToRoute('cache_psr6');
        }


        return $this->render('cache/index.html.twig', [
            'users' => $users,
            'title' => 'Cache PSR-6'
        ]);
    }

    /**
     * @Route("/contracts", name="contracts")
     */
    public function contracts(Request $request, TagAwareCacheInterface $cache): Response
    {
        // reload

        $users = $cache->get('contracts_jsonplaceholder_typicode_com_users', function(ItemInterface $item) {
            $item->expiresAfter(300);
            $item->tag(['all', 'users']);
            $response = $this->http->request('GET', $this->baseUrlApi."/users");

            return $response->toArray();
        });

        $posts = $cache->get('contracts_jsonplaceholder_typicode_com_posts', function(ItemInterface $item) {
            $item->expiresAfter(300);
            $item->tag(['all', 'posts']);
            $response = $this->http->request('GET', $this->baseUrlApi."/posts");

            return $response->toArray();
        });

        dump($posts); // todo remove to prod

        // remove
        if ('delete' === $request->query->get('cache')) {
            $cache->delete('contracts_jsonplaceholder_typicode_com_users');
        }

        if($request->query->has('invalidate')) {
            $cache->invalidateTags([ $request->query->get('invalidate') ]);

            return $this->redirectToRoute('cache_contracts');
        }

        return $this->render('cache/index.html.twig', [
            'users' => $users,
            'title' => 'Cache Contract'
        ]);
    }
}
