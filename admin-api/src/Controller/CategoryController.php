<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Category;
use App\Form\CategoryType;

/**
 * @Route("/api/v1/categories")
 */
class CategoryController extends AbstractFOSRestController
{
    
    /**
     * @return CategoryRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Category::class);
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

        $categories = $this->repository()->findCategories($size, $sort, $order, $offset, $filters);

        if (!$categories) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['categories'] = $categories;
        $response['total_count'] = $this->repository()->countCategories();

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
        $category = $this->repository()->findOneBy(['id' => $id]);

        if (!$category) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        return $this->handleView(
            $this->view($category, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($category);
            $this->em()->flush();

            return $this->handleView(
                $this->view('category.added', Response::HTTP_CREATED)
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
        $category = $this->repository()->find($id);

        if (!$category) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($category);
            $this->em()->flush();

            return $this->handleView(
                $this->view('category.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * Delete one or multiple categories
     * @Rest\Delete("")
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $categories = $this->repository()->findCategoriesByIds($request->request->all());

        if (!$categories) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        foreach ($categories as $category) {
            $this->em()->remove($category);
            $this->em()->flush();
        }

        return $this->handleView(
            $this->view('categories.deleted', Response::HTTP_OK)
        );
    }
    
}
