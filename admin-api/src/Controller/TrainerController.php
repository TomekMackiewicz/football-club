<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Trainer;
use App\Form\TrainerType;

/**
 * @Route("/api/v1/admin/trainers")
 */
class TrainerController extends AbstractFOSRestController
{
    
    /**
     * @return TrainerRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Trainer::class);
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

        $trainers = $this->repository()->findTrainers($size, $sort, $order, $offset, $filters);

        if (!$trainers) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['trainers'] = $trainers;
        $response['total_count'] = $this->repository()->countTrainers();

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
        $trainer = $this->repository()->findOneBy(['id' => $id]);

        if (!$trainer) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        return $this->handleView(
            $this->view($trainer, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $trainer = new Trainer();
        $form = $this->createForm(TrainerType::class, $trainer);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($trainer);
            $this->em()->flush();

            return $this->handleView(
                $this->view('trainer.added', Response::HTTP_CREATED)
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
        $trainer = $this->repository()->find($id);

        if (!$trainer) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $form = $this->createForm(TrainerType::class, $trainer);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($trainer);
            $this->em()->flush();

            return $this->handleView(
                $this->view('trainer.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * Delete one or multiple trainers
     * @Rest\Delete("")
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $trainers = $this->repository()->findTrainersByIds($request->request->all());

        if (!$trainers) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        foreach ($trainers as $trainer) {
            $this->em()->remove($trainer);
            $this->em()->flush();
        }

        return $this->handleView(
            $this->view('trainers.deleted', Response::HTTP_OK)
        );
    }
    
}
