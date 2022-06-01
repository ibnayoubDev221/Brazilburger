<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        $roles = $token->getRoleNames();
        $rolesTab = array_map(function ($role) {
            return $role;
        }, $roles);
        if (in_array('ROLE_GESTIONNAIRE', $rolesTab, true)) {
            // c'est un gestionnaire : on le rediriger vers l'espace gestionnaire
            $redirection = new RedirectResponse($this->urlGenerator->generate('app_gestionnaire'));
        } elseif (in_array('ROLE_USER', $rolesTab, true)) {
            // c'est un utilisaeur lambda : on le rediriger vers l'accueil
            $redirection = new RedirectResponse($this->urlGenerator->generate('app_catalogue'));
        }

        return $redirection;
        //return new RedirectResponse($this->urlGenerator->generate('app_catalogue'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

   /* public function logout(){
        session_start();
        // $response=$this->cartService->distroyAllSessions();
        // if ($response) {

        //     return $this->redirectToRoute('product_index');
        // } 
        session_destroy();
        $_SESSION=[];
    }
    */
    /*

    fonction  publique add ( Cart  $ cart , Request  $ request ): Réponse
    {
        $ form = $ this -> createForm ( OrderType :: class, null , [
            'utilisateur' => $ this -> getUser ()
        ]);

        $ form -> handleRequest ( $ request );

        if ( $ form -> isSubmitted () && $ form -> isValid ()) {
            $ date = nouveau \ DateHeure ();
            // Notre formulaire possède les données carriers -> on le récupère
            $ carriers = $ form -> get ( 'carriers' )-> getData ();
            $ livraison = $ formulaire -> get ( 'adresses' )-> getData ();
            // Construction chaîne de caractères pour les données 'adresses'
            $ delivery_content = $ delivery -> getFirstName () . ' ' . $ livraison -> getLastName ();
            $ delivery_content .= '<br />' . $ livraison -> getPhone ();

            if ( $ livraison -> getCompany ()) {
                $ delivery_content .= '<br />' . $ livraison -> getCompany (); // Rappel => nom de l'entreprise = optionnel
            }

            $ delivery_content .= '<br />' . $ livraison -> getAddress ();
            $ delivery_content .= '<br />' . $ livraison -> getPostcard () . ' ' . $ livraison -> getCity ();
            $ delivery_content .= '<br />' . $ livraison -> getCountry ();

            // Enregistrement de la commande => entité Order()
            $ commande = nouvelle  commande ();
            // Création d'une référence unique pour chaque commande
            $ référence = $ date -> format ( 'dmY' ). '-' . unique ();

            $ commande -> setReference ( $ référence );
            $ order -> setUser ( $ this -> getUser ());
            $ commande -> setCreateAt ( $ date ); // Stockage de la date actuelle
            $ commande -> setCarrierName ( $ transporteurs -> getName ());
            $ commande -> setCarrierPrice ( $ transporteurs -> getPrice ());
            $ commande -> setDelivery ( $ delivery_content );
            // Statut non validé = 0
            $ commande -> setState ( 0 );

            $ this -> entityManager -> persist ( $ order );

            // Enregistrement des produits => entité OrderDetails()
            foreach ( $ cart -> getFullCart () as  $ product ) {
                $ orderDetails = new  OrderDetails ();
                $ orderDetails -> setMyOrder ( $ order ); // setMyOrder => ManyToOne => prend en paramètre notre commande
                $ orderDetails -> setProduct ( $ product [ 'product' ]-> getName ()); // On récupère le nom du produit (tableau)
                $ orderDetails -> setQuantity ( $ produit [ 'quantité' ]); // 'quantité' se trouve dans notre quantité d'entrée
                $ orderDetails -> setPrice ( $ product [ 'product' ]-> getPrice ());
                $ orderDetails -> setTotal ( $ product [ 'product' ]-> getPrice () * $ product [ 'quantity' ]);
                $ this -> entityManager -> persist ( $ orderDetails );
            }

            $ this -> entityManager -> flush ();

            return  $ this -> render ( 'order/add.html.twig' , [
                'cart' => $ cart -> getFullCart (),
                'transporteur' => $ transporteurs ,
                'delivery' => $ delivery_content ,
                'reference' => $ commande -> getReference ()
            ]);
        }

    */
}
