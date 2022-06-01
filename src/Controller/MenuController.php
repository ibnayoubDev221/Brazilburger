<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Entity\Image;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/gestionnaire/menu', name: 'app_menu')]
    public function index(MenuRepository $repository,PaginatorInterface $paginator,  Request $request): Response
    {
         
        $menu = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt ( 'page' , 1 ),
            2
        );
        return $this->render('gestionnaire/listeMenu.html.twig', [
            'menu' => $menu,
        ]);
    }

    #[Route('/panierMenu',name:'app_panier_menu')]
    public function panier(SessionInterface $session,MenuRepository $menuRepository)
    {
        $panier=$session->get('panier',[]);
        $panierData = [];
        foreach($panier as $id => $quantity){
            $panierData[] = [
                'menu'=>$menuRepository->find($id),
                'quantity'=> $quantity,
            ];
        }
        $total=0;
        foreach ($panierData as $data) {
            $totalData = $data['menu'] -> getMontant() * $data['quantity'];
            $total += $totalData;
        }
      //  dd($panierData);
        return $this -> render('catalogue/panierMenu.html.twig',[
         'datas'=>$panierData,
         'total'=>$total,
        ]);
    }

    #[Route('/panierMenu/addMenu/{id}',name:'app_add_panierMenu')]
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


    #[Route('/gestionnaire/editMenu/{id}', name:'edit_menu')]
    public function edit_menu(Request $request,?Menu $menus, EntityManagerInterface $manager): Response
    {
        $form=$this->createForm(MenuType::class, $menus);
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
                $menus->addImage($img);
            }
            $menus = $form->getData();
            $manager->persist($menus);
            $manager->flush();
            return $this->redirectToRoute('app_menu');
        }
        return $this->render('gestionnaire/editMenu.html.twig',[
            'menus'=>$menus,
            'form'=>$form->createView()

        ]);
    }

    #[Route('/panierMenu/remove/{id}',name:'app_remove_panier_menu')]
    public function remove_panier(int $id, SessionInterface $session)
    {
        $panier=$session->get('panier',[]);
        if(!empty($panier[$id])){

           unset( $panier[$id]);
        }
        $session->set('panier', $panier);
        return $this-> redirectToRoute('app_panier_menu');
    }

    #[Route('/menu/detail/{id}',name: 'app_menu_detail')]
    public function detail_menu(int $id, MenuRepository $menu)
    {   
        $menus = $menu->findBy(['id'=> $id]);
        return $this->render('menu/detailMenu.html.twig',[
            'menus'=>$menus,
        ]);
    }

    #[Route('/gestionnaire/addMenu', name:'add_menu', methods:['GET','POST'])]
    public function add(?Menu $menu, Request $request, EntityManagerInterface $manager):Response
    {
        
            $menu = new Menu();
    
        $form=$this->createForm(MenuType::class, $menu);
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
                $menu->addImage($img);
            }
            $menu = $form->getData();
            $manager->persist($menu);
            $manager->flush();
            return $this->redirectToRoute('app_menu');
        }
        return $this->render('gestionnaire/addMenu.html.twig',[
            'form'=>$form->createView()

        ]);
    }

}
