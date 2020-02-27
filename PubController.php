<?php

namespace PubBundle\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use EventBundle\Entity\Event;
use EventBundle\Entity\Reclamation;
use EventBundle\Form\EventType;
use Knp\Component\Pager\PaginatorInterface;
use PubBundle\Entity\Pub;
use PubBundle\Form\PubType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PubController extends Controller
{
    public function createAction(Request $request)
    {
        //1.a Créer un objet vide
        $club=new Pub();
        //1.b Créer le formulaire
        $form=$this->createForm(PubType::class,$club);
        //1.c Envoie du formulaire à l'utilsateur

        //2.a Récupération des données
        $form=$form->handleRequest($request);
        //3.a Test sur les données
        if( $form->isValid()){
            //4.a Création d'un obet doctrin
            $em=$this->getDoctrine()->getManager();
            //4.b persister les données dans ORM
            $em->persist($club);
            //5.b  sauvegarder les donénes dans la BD
            $em->flush();
            //6.a redirect to route
            return $this->redirectToRoute('create');
        }
        return $this->render('@Pub/pub/create.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function readAction( Request $request)
    {
        $em=$this->getDoctrine();
        $tab=$em->getRepository(Pub::class)->FindAll();

        /**
         * @var $pagination \Knp\Component\Pager\Paginator
         */
        $paginator=$this->get('knp_paginator');
        $result=$paginator->paginate($tab,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',2));
        return $this->render('@VBack/Template/gestion_publicite.html.twig', array(
            'projets' => $result,
        ));




    }
    public function deleteAction($id)
    {
        //1.a Recupération de notre objet selon l'id envoyé par l'user
        $em=$this->getDoctrine()->getManager();
        $club=$em->getRepository(Pub::class)->find($id);
        $em->remove($club);
        $em->flush();
        return $this->redirectToRoute("gestion_publicite");
    }
    public function modifierAction($id,Request $request)
    {
        //1.a Recupération de notre objet selon l'id envoyé par l'user
        $em=$this->getDoctrine()->getManager();
        $club=$em->getRepository(Pub::class)->find($id);
        //1.b Créer le formulaire
        $form=$this->createForm(PubType::class,$club);
        //1.c Envoie du formulaire à l'utilsateur

        //2.a Récupération des données
        $form=$form->handleRequest($request);
        //3.a Test sur les données
        if( $form->isValid()){
            $em->flush();
            //6.a redirect to route
            return $this->redirectToRoute('gestion_publicite');
        }
        return $this->render('@Pub/pub/modifier.html.twig', array(
            "form"=>$form->createView()
        ));
    }
}
