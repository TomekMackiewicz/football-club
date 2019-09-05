<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Config;

/**
 * @Route("/api/v1/config")
 */
class ConfigController extends AbstractFOSRestController
{
//    public function __construct()
//    {
//        
//    }
    
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
    
    private function defaultConfig()
    {
        $config = new Config();
        $config->setId(1);
        $config->setSmallFileSize(50);
        $config->setMediumFileSize(100);
        $config->setLargeFileSize(150);
        
        return $config;
    }

//    /**
//     * @Rest\Patch("/{id}")
//     * @return Response
//     */
//    public function patchAction(Request $request, int $id)
//    {
//        $data = $request->request->all();        
//        $category = $this->repository()->find($id);
//
//        if (!$category) {
//            return $this->handleView(
//                $this->view(null, Response::HTTP_NO_CONTENT)
//            );
//        }
//
//        $form = $this->createForm(CategoryType::class, $category);
//        $form->submit($data);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->em()->persist($category);
//            $this->em()->flush();
//
//            return $this->handleView(
//                $this->view('category.edited', Response::HTTP_OK)
//            );
//        }
//
//        return $this->handleView(
//            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
//        );
//    }
    
}