<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/property", name="property.index")
     * @return Response
     */
    public function index(): Response
    {
        // $property = new Property();
        // $property->setTitle('Mon premier bien')
        //     ->setPrice('20000')
        //     ->setRooms(4)
        //     ->setBedrooms(3)
        //     ->setDescription('coucou')
        //     ->setSurface(60)
        //     ->setFloor(2)
        //     ->setHeat(1)
        //     ->setCity('Monpellier')
        //     ->setAddress('15 rue Gambetta')
        //     ->setPostalCode('34000');
        // $em = $this->getDoctrine()->getManager();
        // $em->persist($property);
        // $em->flush();
        // $property = $this->repository->findAllVisible();
        // dump($property);
        // $property[0]->setSold(false);
        $this->em->flush();
        return $this->render('property/index.html.twig', [
            'controller_name' => 'PropertyController',
            'current_menu' => 'properties',
        ]);
    }

    /**
     * @Route("/property/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @return Response
     */
    public function show(Property $property, string $slug): Response
    {
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
        ]);
    }
}
