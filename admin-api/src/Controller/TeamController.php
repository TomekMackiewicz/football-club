<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Team;
use App\Form\TeamType;

/**
 * @Route("/api/v1/teams")
 */
class TeamController extends AbstractFOSRestController
{   
    /**
     * @return TeamRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Team::class);
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

        $teams = $this->repository()->findTeams($size, $sort, $order, $offset, $filters);

        if (!$teams) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['teams'] = $teams;
        $response['total_count'] = $this->repository()->countTeams();

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
        $team = $this->repository()->findOneBy(['id' => $id]);

        if (!$team) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        return $this->handleView(
            $this->view($team, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $data = $request->request->all();
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->submit($data);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($team);
            $this->em()->flush();

            return $this->handleView(
                $this->view('team.added', Response::HTTP_CREATED)
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
        $team = $this->repository()->find($id);

        if (!$team) {
            return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
        }

        $form = $this->createForm(TeamType::class, $team);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($team);
            $this->em()->flush();

            return $this->handleView(
                $this->view('team.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * Delete one or multiple teams
     * @Rest\Delete("")
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $teams = $this->repository()->findTeamsByIds($request->request->all());

        if (!$teams) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        foreach ($teams as $team) {
            $this->em()->remove($team);
            $this->em()->flush();
        }

        return $this->handleView(
            $this->view('teams.deleted', Response::HTTP_OK)
        );
    }
    
}
