<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Client;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/listeCommande', name: 'app_commande')]
    public function index(CommandeRepository $commande): Response
    {

        $commande = $commande->findBy(['etat'=>'encours']);
           
        return $this->render('gestionnaire/listeCommande.html.twig',[
            'commandes'=>$commande
           
        ]);
    }

    #[Route('liste/commande/valider', name:'app_commande_valider')]
    public function commande_valider(CommandeRepository $commande):Response
    {
        $commande = $commande->findBy(['etat'=>'valider']);
           
        return $this->render('gestionnaire/listeCommandeValider.html.twig',[
            'commandes'=>$commande
           
        ]);   
    }
    #[Route('/commandeUser', name: 'commandeUser')]
    public function commandeUser(CommandeRepository $commande): Response
    {
        $user = array_values((array)$this->getUser())[0];
       
        $commandes = $commande->findBy(['etat'=>'encours' , 'user' => $user]);
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'commandes'=>$commandes,
          
        ]);
    }


#[Route('/valide/commande/{id}',name:'valide_commande')]
public function valider_commande(Commande $commande ,EntityManagerInterface $manager):Response
{     
        $commande->setEtat('valider');
        $commande->setValider(1);
        $manager->persist($commande);
        $manager->flush();
        
        return $this->redirectToRoute('app_commande_valider');
} 
/* 

  #[Route('/gestionnaire/valider', name:'app_valider_commande')]
   public function valide_commande(Request $request,$id,EntityManagerInterface $em){
       $em = $this->getMethod();
   } */

   
}

 