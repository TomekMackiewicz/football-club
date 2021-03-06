<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RequestStack;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * @Route("/api/v1/files")
 */
class FileController extends AbstractFOSRestController
{
    private $baseUrl;
    private $fileSystem;
    
    public function __construct(RequestStack $requestStack)
    {
        $this->baseUrl = $requestStack->getCurrentRequest()->getSchemeAndHttpHost();
        $this->fileSystem = new Filesystem();       
    }
    
    private function root()
    {
        return $this->getParameter('kernel.project_dir').'/public/files/';
    }

    /**
     * @Rest\Get("")
     * @return Response
     */
    public function cgetAction()
    {
        $dir = $this->getParameter('kernel.project_dir').'/public/files/';
        $finder = new Finder();
        $finder->in($dir);
        $files = [];

        $i = 0;
        foreach ($finder as $file) {
            $parts = explode('/', $file->getRelativePathname());
            $name = array_values(array_slice($parts, -1))[0];
            $parent = sizeof($parts) > 1 ? array_values(array_slice($parts, -2))[0] : 'root';             
            $files[$i]['id'] = $this->generateUuid();
            $files[$i]['name'] = $name;
            $files[$i]['parent'] = $parent;
            $files[$i]['isFolder'] = strpos($name, '.') !== false ? false : true;
            $files[$i]['path'] = $file->getRelativePath();
            $i++;
        }

        foreach ($files as &$file) {
            if ($file['parent'] !== 'root') {
                $file['parent'] = $this->addParent($file['parent'], $files);
            }
        }

        foreach ($files as &$file) {
            if ($file['isFolder']) {
                $file['children'] = $this->addChildren($file, $files);
            }
        }
        
        $response['files'] = $files;

        return $this->handleView(
            $this->view($response, Response::HTTP_OK)
        );
    }

    /**
     * Add file / directory.
     *
     * @Rest\Post("")
     * @param Request $request
     * @return Response
     * @throws IOException
     */
    public function postAction(Request $request)
    {       
        $root = $this->getParameter('kernel.project_dir').'/public/files/';

        // File
        if ($request->files->count() > 0) {
            $dir = $request->request->get('data');
            foreach ($request->files as $file) {
                $file->move($root.$dir, $file->getClientOriginalName());
            }
        // Directory
        } else {
            $fileSystem = new Filesystem();
            $file = $request->request->get('fileElement');           
            $path = $root.$file['path'].$file['name'];

            if ($fileSystem->exists($path)) {
                return $this->handleView(
                    $this->view('file.already_exists', Response::HTTP_BAD_REQUEST)
                );
            }
 
            try {
                $fileSystem->mkdir($path, 0777); // TODO set proper
            } catch (IOException $ex) {           
                return $this->handleView(
                    $this->view($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR)
                );
            }          
        }

        return $this->handleView(
            $this->view('file.created', Response::HTTP_OK)
        );
    }     

    /**
     * Rename file / folder.
     *
     * @Rest\Patch()
     * @param Request $request
     * @return Response
     * @throws IOException
     */
    public function patchAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);        
        
        try {
            isset($data['moveTo']) ? $this->moveFiles($data) : $this->renameFile($data);
        } catch (IOException $ex) {
            return $this->handleView(
                $this->view($ex->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR)
            );
        }            

        return $this->handleView(
            $this->view('file.updated', Response::HTTP_OK)
        );
    }

    private function moveFiles($data)
    {
        foreach ($data['files'] as $file) {
            $oldPath = $this->root().$file['path'].'/'.$file['oldName'];
            if ($data['moveTo'] === 'root') {
                $newPath = $this->root().'/'.$file['name']; 
            } else {
                $newPath = $this->root().$data['moveTo']['path'].'/'.$data['moveTo']['name'].'/'.$file['name']; 
            }           
            $this->fileSystem->rename($oldPath, $newPath);          
        }
    }

    private function renameFile($data)
    {
        $oldPath = $this->root().$data['file']['path'].$data['oldName'];
        $newPath = $this->root().$data['file']['path'].$data['file']['name']; 
        $this->fileSystem->rename($oldPath, $newPath, true);
    }

    /**
     * Delete files
     *
     * @Rest\Delete("")
     * @param Request $request
     * @return Response
     */
    public function deleteAction(Request $request)
    {
        $fileSystem = new Filesystem();
        $files = json_decode($request->getContent(), true);
        
        foreach ($files as $file) {
            $fileSystem->remove($this->root().$file['path'].$file['name']);
            if ($fileSystem->exists($this->root().$file['path'].$file['name'])) {
                return $this->handleView(
                    $this->view('file.delete_error', Response::HTTP_BAD_REQUEST)
                );
            }            
        }
    }

    private function addChildren($file, $files)
    {
        $children = [];
        foreach ($files as $f) {
            if ($f['parent'] === 'root') {
                continue;
            }
            if ($f['parent']['id'] === $file['id']) {
                $children[] = $f;
            }
        }

        return $children;
    }

    private function addParent($parent, $files)
    {
        foreach ($files as $file) {
            if ($file['name'] == $parent) {
                return $file;
            }
        }
    }

    private function generateUuid()
    {
        return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }    
    
//    /**
//     * @return GameRepository
//     */
//    private function repository()
//    {
//        return $this->getDoctrine()->getRepository(Game::class);
//    }
//
//    /**
//     * @return ObjectManager
//     */
//    private function em()
//    {
//        return $this->getDoctrine()->getManager();
//    }
//
//    /**
//     * @Rest\Get("")
//     * @return Response
//     */
//    public function cgetAction(Request $request)
//    {       
//        $page = $request->query->get('page');
//        $size = (int) $request->query->get('size');
//        $sort = $request->query->get('sort');
//        $order = $request->query->get('order');
//        $offset = ($page-1) * $size;
//        $filters = json_decode($request->query->get('filters'), true);
//
//        $games = $this->repository()->findGames($size, $sort, $order, $offset, $filters);
//
//        if (!$games) {
//            return $this->handleView(
//                $this->view(null, Response::HTTP_NO_CONTENT)
//            );
//        }
//
//        $response['games'] = $games;
//        $response['total_count'] = $this->repository()->countGames();
//
//        return $this->handleView(
//            $this->view($response, Response::HTTP_OK)
//        );
//    }
//
//    /**
//     * @Rest\Get("/{id}")
//     * @return Response
//     */
//    public function getAction(int $id)
//    {
//        $game = $this->repository()->findOneBy(['id' => $id]);
//
//        if (!$game) {
//            return $this->handleView(
//                $this->view(null, Response::HTTP_NO_CONTENT)
//            );
//        }
//
//        return $this->handleView(
//            $this->view($game, Response::HTTP_OK)
//        );
//    }
//
//    /**
//     * @Rest\Post("")
//     * @return Response
//     */
//    public function postAction(Request $request)
//    {
//        $game = new Game();
//        $form = $this->createForm(GameType::class, $game);
//        $form->submit($request->request->all());
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->em()->persist($game);
//            $this->em()->flush();
//
//            return $this->handleView(
//                $this->view('game.added', Response::HTTP_CREATED)
//            );
//        }
//
//        return $this->handleView(
//            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
//        );
//    }
//
//    /**
//     * @Rest\Patch("/{id}")
//     * @return Response
//     */
//    public function patchAction(Request $request, int $id)
//    {
//        $data = $request->request->all();
//        $game = $this->repository()->find($id);
//
//        if (!$game) {
//            return $this->handleView(
//                $this->view(null, Response::HTTP_NO_CONTENT)
//            );
//        }
//
//        $form = $this->createForm(GameType::class, $game);
//        $form->submit($data);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->em()->persist($game);
//            $this->em()->flush();
//
//            return $this->handleView(
//                $this->view('game.edited', Response::HTTP_OK)
//            );
//        }
//
//        return $this->handleView(
//            $this->view($form->getErrors(true), Response::HTTP_BAD_REQUEST)
//        );
//    }
//
//    /**
//     * Delete one or multiple games
//     * @Rest\Delete("")
//     * @return Response
//     */
//    public function deleteAction(Request $request)
//    {
//        $games = $this->repository()->findGamesByIds($request->request->all());
//
//        if (!$games) {
//            return $this->handleView(
//                $this->view(null, Response::HTTP_NO_CONTENT)
//            );
//        }
//
//        foreach ($games as $game) {
//            $this->em()->remove($game);
//            $this->em()->flush();
//        }
//
//        return $this->handleView(
//            $this->view('games.deleted', Response::HTTP_OK)
//        );
//    }

}