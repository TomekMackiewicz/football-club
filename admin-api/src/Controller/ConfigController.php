<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Config;
use App\Form\ConfigType;

/**
 * @Route("/api/v1/admin/config")
 */
class ConfigController extends AbstractFOSRestController
{
    /**
     * @return ConfigRepository
     */
    private function repository()
    {
        return $this->getDoctrine()->getRepository(Config::class);
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
    public function getAction()
    {
        $config = $this->repository()->getConfig();

        // Return default config if no data yet
        if (!$config) {
            $defaultConfig = $this->defaultConfig();
            return $this->handleView(
                $this->view($defaultConfig, Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($config, Response::HTTP_OK)
        );
    }

    /**
     * @Rest\Patch("/{id}")
     * @return Response
     */
    public function patchAction(Request $request, int $id)
    {
        $data = $request->request->all();        
        $config = $this->repository()->find($id);
        $form = $this->createForm(ConfigType::class, $config);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($config);
            $this->em()->flush();

            return $this->handleView(
                $this->view('config.edited', Response::HTTP_OK)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }
    
    /**
     * @Rest\Post("")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $config = new Config();
        $data = $request->request->all();
        $form = $this->createForm(ConfigType::class, $config);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em()->persist($config);
            $this->em()->flush();

            return $this->handleView(
                $this->view('config.edited', Response::HTTP_CREATED)
            );
        }

        return $this->handleView(
            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
        );
    }

    /**
     * @return Config
     */
    private function defaultConfig()
    {
        $config = new Config();
        $config->setId(-1);
        $config->setSmallFileSize(50);
        $config->setMediumFileSize(100);
        $config->setLargeFileSize(150);
        
        return $config;
    }    
}