<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Game;
use App\Form\GameType;
//use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/game")
 */
class GameController extends FOSRestController
{
    /**
     * Lists all games
     * @Rest\Get("/all")
     *
     * @return Response
     */
    public function getGameAction(Request $request)
    {        
        $repository = $this->getDoctrine()->getRepository(Game::class);
        $games = $repository->findall();
        
        $response['games'] = $games;
        $response['total_count'] = 2;
       
        return $this->handleView($this->view($response));
    }
  
    /**
     * @Rest\Post("/new")
     *
     * @return Response
     */
    public function postGameAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush();

            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }

        return $this->handleView($this->view($form->getErrors()));
    }
}