<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Training;
use App\Form\TrainingType;

/**
 * @Route("/api/v1/trainings")
 */
class TrainingController extends AbstractFOSRestController
{   
    /**
     * @return TrainingRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Training::class);
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

        $trainings = $this->repository()->findTrainings($size, $sort, $order, $offset, $filters);

        if (!$trainings) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['trainings'] = $trainings;
        $response['total_count'] = $this->repository()->countTrainings();

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
        $training = $this->repository()->findOneBy(['id' => $id]);

        if (!$training) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        return $this->handleView(
            $this->view($training, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $data = $request->request->all();
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training, ['trainers' => $data['trainers']]);
        $form->submit($data);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($training);
            $this->em()->flush();

            return $this->handleView(
                $this->view('training.added', Response::HTTP_CREATED)
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
        $training = $this->repository()->find($id);

        if (!$training) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $form = $this->createForm(TrainingType::class, $training, ['trainers' => $data['trainers']]);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($training);
            $this->em()->flush();

            return $this->handleView(
                $this->view('training.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * Delete one or multiple trainings
     * @Rest\Delete("")
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $trainings = $this->repository()->findTrainingsByIds($request->request->all());

        if (!$trainings) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        foreach ($trainings as $training) {
            $this->em()->remove($training);
            $this->em()->flush();
        }

        return $this->handleView(
            $this->view('trainings.deleted', Response::HTTP_OK)
        );
    }
    
}
