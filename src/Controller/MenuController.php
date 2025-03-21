<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Menu;

final class MenuController extends AbstractController
{
    #[Route('/menus', name: 'app_menu', methods: ["GET"])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les menus depuis la base de données
        $menus = $entityManager->getRepository(Menu::class)->findAll();

        // Préparer les données pour la réponse JSON
        $menusData = [];
        foreach ($menus as $menu) {
            $menusData[] = [
                'id' => $menu->getId(),
                'plate' => $menu->getPlate(),
                'description' => $menu->getDescription(),
                'image' => $menu->getImage(),
            ];
        }

        // Retourner les données sous forme de JSON
        return $this->json($menusData);
    }

    #[Route('/menus/{id}', name: 'app_menu_show', methods: ['GET'])]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $menu = $entityManager->getRepository(Menu::class)->find($id);

        if (!$menu) {
            throw $this->createNotFoundException('No menu found for id ' . $id);
        }

        $menuData = [
            'id' => $menu->getId(),
            'plate' => $menu->getPlate(),
            'description' => $menu->getDescription(),
            'image' => $menu->getImage(),
        ];

        return $this->json($menuData);
    }

    #[Route('/menus/menu-dummy-data', name: "menu-data")]
    public function addDummyData(EntityManagerInterface $entityManager): Response
    {
        $menuData = 7;

        $plateArray = [
            "Hello World Burger",
            "404 Not Found Fries",
            "JSON Nuggets",
            "Git Pull Tacos",
            "Front-end Salad",
            "Back-End Brownie",
            "Full Stack Menu"
        ];

        $descriptionArray = [
            "Un cheeseburger classique (pain, steak, fromage, salade, sauce).",
            "Des frites maison avec une sauce mystère (choisie aléatoirement par le backend !).",
            "Nuggets de poulet avec 3 sauces au choix (ketchup, mayo, barbecue).",
            "Un taco simple avec poulet, salade, fromage et sauce.",
            "Une salade légère avec tomates, feta et vinaigrette maison.",
            "Un brownie moelleux au chocolat.",
            "Un combo burger, frites et boisson."
        ];

        $imageArray = [
            "🍔",
            "🍟",
            "🍗",
            "🌮",
            "🥗",
            "🍫",
            "🥗",
        ];

        for ($i = 0; $i < $menuData; $i++) {
            $menu = new Menu();

            $menu->setPlate($plateArray[$i]);
            $menu->setDescription($descriptionArray[$i]);
            $menu->setImage($imageArray[$i]);

            $entityManager->persist($menu);
        }

        $entityManager->flush();

        return new Response('Created lots of dummy data');
    }
}