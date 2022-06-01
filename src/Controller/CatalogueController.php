<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Payement;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use App\Repository\BurgerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_catalogue')]
    public function showBurgerMenu(BurgerRepository $repository, Menurepository $menu, Request $request): Response
    {
        $burgers = $repository->findBy(['etat'=>'non_archiiver']);
        $menus = $menu->findAll();
        return $this->render('catalogue/index.html.twig', [
            'burger' => $burgers,
            'menu' => $menus,
        ]);
    }

    #[Route('/panier',name:'app_panier')]
    public function panier(SessionInterface $session,BurgerRepository $burger,Request $request,MenuRepository $menu,UserRepository $user,EntityManagerInterface $entityManager)
    {
        $method = $request->getMethod();
        $commande = new Commande();
        $payement = new Payement();
        $panier=$session->get('panier',[]);
        $panierData = [];
        $idBurger=[];
        $idMenu=[];        
        foreach($panier as $id => $quantity){
            if(str_contains($id , 'burger')){
                $idBurger [] = (int) filter_var($id,FILTER_SANITIZE_NUMBER_INT);
            }else{
                $idMenu [] = (int) filter_var($id,FILTER_SANITIZE_NUMBER_INT);
            }


            $panierData[] = [
                'burger'=>str_contains($id, "burger") ? $burger->find($id) :$menu->find($id),
                'quantity'=> $quantity,
            ]; 
        }
        
        $total=0;
        foreach ($panierData as $data) {
           
            $totalData = $data['burger']->getPrix() * $data['quantity'];
             $total += $totalData;
        }

         
            if ($method == 'POST') {
                $date = date_format(date_create() , 'd-m-Y');
                $idUser = array_values((array)$this->getUser())[0];
                $user = $user->find($idUser);
                $payement-> setMotant(0);
                $commande->setDate($date) 
                        //->setNumero(rand())
                        ->setEtat('encours')
                        ->setValider(0)
                        ->setUser($user)
                        ->setPayements($payement);
                if(count($idBurger)>0){
                    foreach ($idBurger as $val) {
                        $commande->addBurger($burger->find($val));
                    }
                }       
                if(count($idMenu)>0){
                    foreach ($idMenu as $val2) {
                        $commande->addMenu($menu->find($val2));
                    }
                } 
                
                $entityManager->persist($payement);
                $entityManager->persist($commande);
                $entityManager->flush();
                $session->remove('panier',[]);

                return $this->redirectToRoute("commandeUser");
            }
      //  dd($panierData);
        return $this -> render('catalogue/panier.html.twig',[
         'datas'=>$panierData,
         'total'=>$total,
        ]);
    }

   /*  #[Route('/commander', name:'app_commander', methods:['GET','POST'])]
    public function commander(){
        return $this->redirectToRoute('app_catalogue');
    } */

    #[Route('/panier/add/{id}',name:'app_add_panier')]
    public function addBurgerPanier( $id, SessionInterface $session)
    {
            $panier=$session->get('panier',[]);

           if(!empty($panier[$id])){

               $panier[$id]++;
           }else{

                $panier[$id] = 1;        
           }
           $session->set('panier', $panier);
          // dd($session->get('panier'));
          return $this->redirectToRoute('app_catalogue');
    }

    #[Route('/panier/remove/{id}',name:'app_remove_panier')]
    public function remove_panier( $id, SessionInterface $session)
    {
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id])){

           unset( $panier[$id]);
        }
        $session->set('panier', $panier);
        return $this-> redirectToRoute('app_panier');
    }
  

}
