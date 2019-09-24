<?php declare(strict_types=1);

namespace App\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FormFactory;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\User;

/**
 * @Route("/api/v1/admin/users")
 */
class UserController extends FOSRestController
{
    private $formFactory;
    private $userManager;
    private $dispatcher;
    private $repository;

    public function __construct(
        FormFactory $formFactory, 
        UserManagerInterface $userManager, 
        EventDispatcherInterface $dispatcher, 
        EntityManagerInterface $entityManager)
    {
        $this->formFactory = $formFactory;
        $this->userManager = $userManager;
        $this->dispatcher = $dispatcher;
        $this->repository = $entityManager->getRepository(User::class);
    }     

    /**
     * @Rest\Get("")
     * @param Request $request
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

        $users = $this->repository->findUsers($size, $sort, $order, $offset, $filters);

        if (!$users) {
            return $this->handleView(
                $this->view(null, Response::HTTP_NO_CONTENT)
            );
        }

        $response['users'] = $users;
        $response['total_count'] = $this->repository->countUsers();

        return $this->handleView(
            $this->view($response, Response::HTTP_OK)
        );
    }

    /**
     * Register new user.
     * 
     * @Route("", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function registerAction(Request $request)
    {         
        $user = $this->userManager->createUser();
        $user->setEnabled(true);
        $event = new GetResponseUserEvent($user, $request);
        $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
        if (null !== $event->getResponse()) {            
            return $event->getResponse();
        }
        $form = $this->formFactory->createForm([
            'csrf_protection' => false
        ]);
        $form->setData($user);         
        $form->submit($request->request->all());
        
        if (!$form->isValid()) {
            $event = new FormEvent($form, $request);
            $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);
                        
            return new View($form->getErrors(true), Response::HTTP_BAD_REQUEST);
        }
        $event = new FormEvent($form, $request);
        $this->dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);
        if ($event->getResponse()) {             
            return $event->getResponse();
        }
        $this->userManager->updateUser($user);
        $response = new JsonResponse([
            'msg' => 'user.register.success',
            'token' => $this->get('lexik_jwt_authentication.jwt_manager')
                ->create($user),
        ], JsonResponse::HTTP_CREATED, [
                'Location' => $this->generateUrl(
                    'app_profile_get',
                    [ 'user' => $user->getId() ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            ]
        );
        $this->dispatcher->dispatch(
            FOSUserEvents::REGISTRATION_COMPLETED,
            new FilterUserResponseEvent($user, $request, $response)
        );

        return $response;
    }
}