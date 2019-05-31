<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Post;
use App\Form\PostType;

/**
 * @Route("/api/v1/posts")
 */
class PostController extends AbstractFOSRestController
{
    
    /**
     * @return PostRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Post::class);
    }

    /**
     * @return ObjectManager
     */
    private function em()
    {
        return $this->getDoctrine()->getManager();
    }
    
    /**
     * @Rest\Get("")
     * @return Response
     */
    public function cgetAction(Request $request)
    {       
        $page = $request->query->get('page');
        $size = (int) $request->query->get('size');
        $sort = $request->query->get('sort');
        $order = $request->query->get('order');
        $offset = ($page-1) * $size;
        $filters = json_decode($request->query->get('filters'), true);

        $posts = $this->repository()->findPosts($size, $sort, $order, $offset, $filters);

        if (!$posts) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['posts'] = $posts;
        $response['total_count'] = $this->repository()->countPosts();

        return $this->handleView(
            $this->view($response, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Get("/{id}")
     * @return Response
     */
    public function getAction(int $id)
    {
        $post = $this->repository()->findOneBy(['id' => $id]);

        if (!$post) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        return $this->handleView(
            $this->view($post, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {        
        $data = $request->request->all();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, ['categories' => $data['categories']]);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {           
            $post->setPublishDate(new \DateTime());           
            $this->em()->persist($post); 
            $this->em()->flush();

            return $this->handleView(
                $this->view('post.added', Response::HTTP_CREATED)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * @Rest\Patch("/{id}")
     * @return Response
     */
    public function patchAction(Request $request, int $id)
    {
        $data = $request->request->all();        
        $post = $this->repository()->find($id);

        if (!$post) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $form = $this->createForm(PostType::class, $post, ['categories' => $data['categories']]);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($post);
            $this->em()->flush();

            return $this->handleView(
                $this->view('post.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * Delete one or multiple posts
     * @Rest\Delete("")
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $posts = $this->repository()->findPostsByIds($request->request->all());

        if (!$posts) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        foreach ($posts as $post) {
            $this->em()->remove($post);
            $this->em()->flush();
        }

        return $this->handleView(
            $this->view('posts.deleted', Response::HTTP_OK)
        );
    }
    
}
