<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Form\ProfileType;

/**
 * @Route("/api/v1/admin/profile")
 */
class ProfileController extends AbstractFOSRestController
{

    /**
     * @return ObjectManager
     */
    private function em()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @Rest\Get("/{user}")
     * @param UserInterface $user
     * @ParamConverter("user", class="App:User")
     * @throws AccessDeniedHttpException
     * @return Response
     */
    public function getAction(UserInterface $user)
    {
        if ($user !== $this->getUser()) {
            return $this->handleView(
                $this->view('profile.forbidden', Response::HTTP_FORBIDDEN)
            );
        }

        return $this->handleView(
            $this->view($user, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Patch("/{user}")
     * @param UserInterface $user
     * @ParamConverter("user", class="App:User")
     * @return Response
     */
    public function patchAction(Request $request, UserInterface $user)
    {       
        if ($user !== $this->getUser()) {
            return $this->handleView($this->view('profile.forbidden', Response::HTTP_FORBIDDEN));
        }

        if (!$user) {
            return $this->handleView($this->view(null, Response::HTTP_NO_CONTENT));
        }

        $data = $request->request->all();
        $form = $this->createForm(ProfileType::class, $user);
        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($user);
            $this->em()->flush();

            return $this->handleView($this->view('profile.edited', Response::HTTP_OK));
        }

        return $this->handleView($this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST));
    }
}