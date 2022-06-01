<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Burger;
use App\Form\BurgerType;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use ContainerBb8l4vi\PaginatorInterface_82dac15;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BurgerController extends AbstractController
{
    #[Route('/gestionnaire/burger', name: 'app_burger')]
    public function index(BurgerRepository $repository,  Request $request, PaginatorInterface $paginator): Response
    {   
        $burgers = $paginator->paginate (
            $repository->findBy(['etat'=>'non_archiver']),
            $request->query->getInt ( 'page' , 1 ),
            2
        );
        return $this->render('gestionnaire/listeBurger.html.twig', [
            'burger' => $burgers,
        ]);

    }

    #[Route('/gestionnaire/burger/archiver', name: 'liste_burger_archive')]
    public function listeBurgerArchiver(BurgerRepository $repository,  Request $request, PaginatorInterface $paginator): Response
    {   
        $burgers = $paginator->paginate (
            $repository->findBy(['etat'=>'archiver']),
            $request->query->getInt ( 'page' , 1 ),
            2
        );
        return $this->render('gestionnaire/listeBurgerArchiver.html.twig', [
            'burger' => $burgers,
        ]);

    }

    #[Route('/burger/detail/{id}',name: 'app_burger_detail')]
    public function detail_burger(int $id, BurgerRepository $burger)
    {   
        $burgers= $burger->findBy(['id'=> $id]);
        return $this->render('burger/detailBurger.html.twig',[
            'burger'=>$burgers,
        ]);
    }


    #[Route('/gestionnaire/editBurger/{id}', name:'edit_burger', methods:['GET','POST'])]
    public function edit_burger(Request $request,?Burger $burgers, EntityManagerInterface $manager): Response
    {
        $form=$this->createForm(BurgerType::class, $burgers);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $images=$form->get('image')->getData();

            foreach($images as $image){
                $fichier=md5(uniqid()). '.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Image();
                $img->setNom($fichier);
                $burgers->addImage($img);
            }
            $burgers = $form->getData();
            $manager->persist($burgers);
            $manager->flush();
            return $this->redirectToRoute('app_burger');
        }
        return $this->render('gestionnaire/editBurger.html.twig',[
            'burgers'=>$burgers,
            'form'=>$form->createView()

        ]);
    }
     #[Route('/supprime/image/{id}',name:'burger_delete_image',methods:['DELETE'] )]
    public function deleteImage(Image $image, Request $request, EntityManagerInterface $manager){
        $data = json_decode($request->getContent(), true);
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){

            $nom = $image->getNom();
            unlink($this->getParameter('images_directory'). '/' .$nom);
            $em = $manager;
            $em->remove($image);
            $em -> flush();
            return new JsonResponse(['success'=>1]);       
        }else{
            return new JsonResponse(['token invalid'], 404);
        }
    } 



    #[Route('/gestionnaire/addBurger  ', name:'add_burger', methods:['GET','POST'])]
    public function add(?Burger $burger, Request $request, EntityManagerInterface $manager):Response
    {
    
        $burger = new Burger();
        $burger->setEtat('non_archiver');
        $form=$this->createForm(BurgerType::class, $burger);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $images=$form->get('image')->getData();

            foreach($images as $image){
                $fichier=md5(uniqid()). '.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $img = new Image();
                $img->setNom($fichier);
                $burger->addImage($img);
            }
            $burger = $form->getData();
            $manager->persist($burger);
            $manager->flush();
            return $this->redirectToRoute('app_burger');
        }
        return $this->render('gestionnaire/addBurger.html.twig',[
            'form'=>$form->createView()

        ]);
    }

    #[Route('/gestionnaire/archiverBurger/{id}', name:'app_archiver_burger')]
    public function archiver_burger(Burger $burger,EntityManagerInterface $manager):Response
    {
        $burger->setEtat('archiver');
        $manager->persist($burger);
        $manager->flush();
        return $this->redirectToRoute('liste_burger_archive');
    }
    #[Route('/gestionnaire/desarchiverBurger/{id}', name:'app_désarchiver')]
    public function désarchiver_burger(Burger $burger,EntityManagerInterface $manager):Response
    {
        $burger->setEtat('non_archiver');
        $manager->persist($burger);
        $manager->flush();
        return $this->redirectToRoute('app_burger');
    }
}
