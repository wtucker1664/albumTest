<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Serializer\FormErrorSerializer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Imbo\BehatApiExtension\Exception\AssertionFailedException;

class AlbumController extends AbstractController
{
    private $entityManager;
    private $formErrorSerializer;
    private $albumRepository;
    public function __construct(EntityManagerInterface $entityManager,FormErrorSerializer $formErrorSerializer, AlbumRepository $albumRepository) {
        $this->entityManager = $entityManager;
        $this->formErrorSerializer = $formErrorSerializer;
        $this->albumRepository = $albumRepository;
    }
    /**
     * @Route("/album/{id}", name="get_album", methods={"GET"})
     * @return JsonResponse
     */
    public function get($id) {
        $album = $this->findAlbumById($id);
  
        return new JsonResponse(
            $album,
            JsonResponse::HTTP_OK
        );
    }
    /**
     * @Route("/album", name="cget_album", methods={"GET"})
     * @return JsonResponse
     */
    public function cget()
    {
        return new JsonResponse(
           $this->albumRepository->findAll(),
           JsonResponse::HTTP_OK
        );
    }
    /**
     * @Route("/album", name="post_album", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function post(Request $request)
    {
        $data = json_decode($request->getContent(),true);
        
        $form = $this->createForm(AlbumType::class, new Album());
       
        $form->submit($data);
       if(false === $form->isValid()){
           
           return new JsonResponse([
               'status' => 'error',
               'errors'=> $this->formErrorSerializer->convertFormToArray($form)
           ],
            JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $this->entityManager->persist($form->getData());
        $this->entityManager->flush();
        
        return new JsonResponse(
        [
            "status" => "ok",
            
        ],
        JsonResponse::HTTP_CREATED
        );
    }
    /**
     * @Route("/album/{id}", name="put_album", methods={"PUT"}, requirements={"id"="\d+"})
     */
    public function put(Request $request, $id)
    {
        $data = json_decode($request->getContent(),true);
        
        $album = $this->findAlbumById($id);
        
        $form = $this->createForm(AlbumType::class, $album);
        
        $form->submit($data);
        if(false === $form->isValid()){
           
           return new JsonResponse([
               'status' => 'error',
               'errors'=> $this->formErrorSerializer->convertFormToArray($form)
           ],
            JsonResponse::HTTP_BAD_REQUEST);
        }
        
        $this->entityManager->flush();
        
        return new JsonResponse(
        null,
        JsonResponse::HTTP_NO_CONTENT
        );
    }
    
    /**
     * @param $id
     *
     * @return Album|null
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function findAlbumById($id){
        $album = $this->albumRepository->find($id);
        
        if(null === $album){
            throw new NotFoundHttpException();
        }
        return $album;
    }
}
