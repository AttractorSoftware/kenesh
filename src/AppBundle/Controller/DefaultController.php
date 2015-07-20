<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\Deputy;
use AdminBundle\Entity\Faction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller {

    private $factionList = null;

    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/load/factions", name="load-factions")
     */
    public function loadFactionsAction(Request $request) {
        $result = array('status' => 'ok');
        $em = $this->getDoctrine()->getManager();
        $factions = json_decode($request->getContent());
        $readyFactions = $em->getRepository('AdminBundle:Faction')->findAll();
        try {
            foreach($factions as $faction) {
                $ready = false;
                foreach($readyFactions as $readyFaction) {
                    if($readyFaction->getTitle() == $faction->title) {
                        $ready = true;
                    }
                }
                if(!$ready) {
                    $newFaction = new Faction();
                    $newFaction->setTitle($faction->title);
                    $em->persist($newFaction);
                }
            } $em->flush();
        } catch(ContextErrorException $error) {
            $result = array('status' => 'fail', 'error' => $error->getMessage());
        } return new JsonResponse($result);
    }

    /**
     * @Route("/load/deputies", name="load-deputies")
     */
    public function loadDeputiesAction(Request $request) {
        $result = array('status' => 'ok');
        $em = $this->getDoctrine()->getManager();
        $deputies = json_decode($request->getContent());
        $readyDeputies = $em->getRepository('AdminBundle:Deputy')->findAll();
        try {
            foreach($deputies as $deputy) {
                $ready = false;
                foreach($readyDeputies as $readyDeputy) {
                    if($readyDeputy->getFullName() == $deputy->full_name) {
                        $ready = true;
                    }
                }
                if(!$ready) {
                    $newDeputy = new Deputy();
                    $newDeputy->setFullName($deputy->full_name);
                    $newDeputy->setBiography($deputy->bio);
                    $newDeputy->setKeneshLink($deputy->kenesh_url);
                    $newDeputy->setPhotoUrl($deputy->photo_url);
                    $newDeputy->setFaction($this->findFactionByTitle($deputy->faction));
                    $em->persist($newDeputy);
                }
            } $em->flush();
        } catch(ContextErrorException $error) {
            $result = array('status' => 'fail', 'error' => $error->getMessage());
        } return new JsonResponse($result);
    }

    public function findFactionByTitle($factionTitle) {
        if (!$this->factionList) {
            $this->factionList = $this->getDoctrine()
                ->getManager()
                ->getRepository('AdminBundle:Faction')
                ->findAll();
        }
        foreach($this->factionList as $faction) {
            if($faction->getTitle() == $factionTitle) {
                return $faction;
            }
        } return null;
    }
}
