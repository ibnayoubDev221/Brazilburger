<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Complement;
use App\Form\ComplementType;
use App\Repository\ComplementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ComplementController extends AbstractController
{
    #[Route('/gestionnaire/complement', name: 'app_complement')]
    public function index(ComplementRepository $repository, Request $request,PaginatorInterface $paginator): Response
    {
        $complements = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt ( 'page' , 1 ),
            2
        ) ;
        return $this->render('gestionnaire/listeComplement.html.twig', [
            'controller_name' => 'ComplementController',
            'complements'=> $complements,
        ]);
    }

    #[Route('/gestionnaire/editComplement/{id}', name:'edit_complement')]
    public function edit_complement(Request $request,?Complement $complement, EntityManagerInterface $manager): Response
    {
        $form=$this->createForm(ComplementType::class, $complement);
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
                $complement->addImage($img);
            }
            $complement = $form->getData();
            $manager->persist($complement);
            $manager->flush();
            return $this->redirectToRoute('app_complement');
        }
        return $this->render('gestionnaire/editComplement.html.twig',[
            'complement'=>$complement,
            'form'=>$form->createView()

        ]);
    }

    #[Route('/gestionnaire/addComplement  ', name:'add_complement', methods:['GET','POST'])]
    public function add(?Complement $complement, Request $request, EntityManagerInterface $manager):Response
    {
            $complement = new Complement();
        $form=$this->createForm(ComplementType::class, $complement);
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
                $complement->addImage($img);
            }
            $complement = $form->getData();
            $manager->persist($complement);
            $manager->flush();
            return $this->redirectToRoute('app_complement');
        }
        return $this->render('gestionnaire/addComplement.html.twig',[
            'form'=>$form->createView()

        ]);
    }
}
