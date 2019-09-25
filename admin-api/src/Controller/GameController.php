<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Game;
use App\Form\GameType;

/**
 * @Route("/api/v1/editor/games")
 */
class GameController extends AbstractFOSRestController
{
    /**
     * @return GameRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
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

        $games = $this->repository()->findGames($size, $sort, $order, $offset, $filters);

        if (!$games) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['games'] = $games;
        $response['total_count'] = $this->repository()->countGames();

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
        $game = $this->repository()->findOneBy(['id' => $id]);

        if (!$game) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        return $this->handleView(
            $this->view($game, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($game);
            $this->em()->flush();

            return $this->handleView(
                $this->view('game.added', Response::HTTP_CREATED)
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
        $game = $this->repository()->find($id);

        if (!$game) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $form = $this->createForm(GameType::class, $game);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($game);
            $this->em()->flush();

            return $this->handleView(
                $this->view('game.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * Delete one or multiple games
     * @Rest\Delete("")
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $games = $this->repository()->findGamesByIds($request->request->all());

        if (!$games) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        foreach ($games as $game) {
            $this->em()->remove($game);
            $this->em()->flush();
        }

        return $this->handleView(
            $this->view('games.deleted', Response::HTTP_OK)
        );
    }

}