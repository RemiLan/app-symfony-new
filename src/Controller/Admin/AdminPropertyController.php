<?php

namespace App\Controller\Admin;

use App\Repository\PropertyRepository;
use App\Entity\Property;
use App\Form\PropertyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        // $this->container->get('security');
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     * @param Request $request
     */
    public function new(Request $request)
    {
        $coco = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'label' => 'pseudo',
                'data' => 'coco',
                'required' => false,
                'attr' => [
                    'class' => 'coucou',
                    'placeholder' => 'zoulou',
                ]
            ])
            ->add('title2', TextType::class, [
                'label' => 'pseudo',
                'data' => 'coco',
                'required' => false,
                'attr' => [
                    'class' => 'coucou',
                    'placeholder' => 'zoulou',
                ]
            ])
            ->add('save', SubmitType::class)
            ->addEventListener('form.pre_submit', function(FormEvent $f) {
                // dd($f);
                $f->getForm()->addError(new FormError('nooooooooo'));
            })
            ->setMethod('POST')
            ->getForm();
        if ($request->isMethod('POST')) $coco->handleRequest($request);
        
        $coco->getErrors(true);
        // $coco = $this->createFormBuilder()
        //     ->add('title', ChoiceType::class, [
        //         'label' => 'pseudo',
        //         'required' => false,
        //         'attr' => [
        //             'class' => 'coucou',
        //         ],
        //         'choices' => [
        //             'oui' => 1,
        //             'non' => 0
        //         ],
        //         'expanded' => true
        //     ])
        //     ->getForm();
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succés');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            'property' => compact('property'),
            'form' => $form->createView(),
            'coco' => $coco->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.property.edit")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succés');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/edit.html.twig', [
            'property' => compact('property'),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods={"POST"})
     * @param Property $property
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete(Property $property, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            // dd($request->isXmlHttpRequest());
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succés');
        }
        // dd($request->isXmlHttpRequest());
        // $this->em->remove($property);
        // $this->em->flush();
        // return new Response('Suppression');
        return $this->redirectToRoute('admin.property.index');
    }
}
