<?php

namespace App\Controller;

use App\Entity\Command;
use App\Entity\Product;
use App\Repository\CommandRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProductRepository $productsRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;
        $itemsQty = 0;

        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrice() * $quantite;
            $itemsQty += $quantite;
        }

        return $this->render('cart/index.html.twig', compact("dataPanier", "total", "itemsQty"));
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Product $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }


    #[Route('/cart/order', name: 'order')]
    public function order(Security $security, CommandRepository $commandRepository, ProductRepository $productRepository, SessionInterface $session, ManagerRegistry $doctrine): Response
    {
        $user = $security->getUser();
        $cart = $session->get('panier', []);

        if ($cart === []) {
            return $this->redirectToRoute('cart');
        }

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        foreach ($cart as $id => $quantite) {
            $cartWithData[] = [
                'product' => $productRepository->find($id),
                'quantity' => $quantite
            ];
        }

        $total = 0;

        foreach ($cartWithData as $item) {
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        if ($user === null) {
            return $this->redirectToRoute('cart');
        }

        $entityManager = $doctrine->getManager();
        
        $command = new Command();
        $command->setDate(new \DateTime());
        $command->setPrice($total);
        $command->setUser($user);

        foreach ($cart as $id => $quantite) {
            $command->addProduct($productRepository->find($id));
        }

        $entityManager->persist($command);
        $entityManager->flush();

        $session->set('panier', []);

        return $this->render('cart/order.html.twig');
    }
    

}