<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DomainNameType;
use App\Form\HebergementType;
use App\Entity\DomaineName;
use App\Entity\Hebergement;
use Iodev\Whois\Factory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/dashbord", name="admin_dashbord")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
    }

    /**
     * @Route("/admin/calendar", name="admin_calendar")
     */
    public function calendar(): Response
    {
        return $this->render('admin/calendar.html.twig', [

        ]);
    }
    /**
     * @Security("is_granted('ROLE_AUTHOR')")
     * @Route("/admin/list_domainName", name="admin_list_domainName")
     */
    public function liste_domainName(){
        $repos = $this->getDoctrine()->getRepository(Hebergement::class);
        $whois = Factory::get()->createWhois();
        $hebergement = $repos->findAll();
        $i = 0;
        $j = 0;
        $date = [];

       foreach ($hebergement as $data){

               $info = $whois->loadDomainInfo($data->getName());

                $date[$i]["expiration"] = date("Y-m-d", $info->expirationDate);
               $date[$i]["creation"] = date("Y-m-d", $info->creationDate);
             $date[$i]["owner"] =  $info->registrar;
              foreach ($data->getDomaineName() as $dd){
                  $infodd = $whois->loadDomainInfo($dd->getName());
                  $date[$i][$j]["expiration"] = date("Y-m-d", $infodd->expirationDate);
                  $date[$i][$j]["creation"] = date("Y-m-d", $infodd->creationDate);
                  $date[$i][$j]["owner"] =  $infodd->registrar;
                  $j++;
              }
               $i++;


       }

        return $this->render('admin/list_hebergement.html.twig',[
            'hebergements' =>$hebergement,
            'dateInfo'=>$date
        ]);
    }
    /**
     * @Security("is_granted('ROLE_AUTHOR')")
     * @Route ("/admin/create_domainName", name="create_domainName")
     * @Route("/admin/{id}/edit_domainName",name="admin_edit_domainName")
     */
    public function create_edit(DomaineName $domaineName = null, Request $request)
    {
        if(!$domaineName){
            $domaineName = new DomaineName();
        }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(DomainNameType::class, $domaineName);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$domaineName->getId()){
                $domaineName->setCreatedAt(new \DateTime());
            }
            $domaineName->setUser($this->getUser());
            $manager->persist($domaineName);
            $manager->flush();
            return $this->redirectToRoute('admin_list_domainName');
        }
        return $this->render('admin/create_domainName.html.twig',[
            'formDomainName' => $form->createView(),
            'editMode' => $domaineName->getId() !== null
        ]);

    }
    /**
     * @Security("is_granted('ROLE_AUTHOR')")
     * @Route("/admin/remove_domainName/{id}", name="admin_remove_domainName")
     */
    public function remove_domainName(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $domainName = $entityManager->getRepository(DomaineName::class)->find($id);

        if (!$domainName) {
            throw $this->createNotFoundException(
                'Pas de nom de domaine avec ce  '.$id
            );
        }

        $entityManager->remove($domainName);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_domainName');

    }



    /**
     * @Security("is_granted('ROLE_AUTHOR')")
     * @Route ("/admin/create_hebergement", name="create_hebergement")
     * @Route("/admin/{id}/edit_hbergement",name="admin_edit_hebergement")
     */
    public function create_hebergement(Hebergement $hebergement = null, Request $request)
    {
        if(!$hebergement){
            $hebergement = new Hebergement();
        }
        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(!$hebergement->getId()){
                $hebergement->setCreatedAt(new \DateTime());
            }
            $hebergement->setUser($this->getUser());
            $manager->persist($hebergement);
            $manager->flush();
            return $this->redirectToRoute('admin_list_hebergement');
        }
        return $this->render('admin/create_hebergement.html.twig',[
            'formHebergement' => $form->createView(),
            'editMode' => $hebergement->getId() !== null
        ]);

    }
    /**
     * @Security("is_granted('ROLE_AUTHOR')")
     * @Route("/admin/remove_hebergement/{id}", name="admin_remove_hebergement")
     */
    public function remove_hebergement(int $id){
        $entityManager = $this->getDoctrine()->getManager();
        $hebergement = $entityManager->getRepository(Hebergement::class)->find($id);

        if (!$hebergement) {
            throw $this->createNotFoundException(
                'Pas d\'hebergement avec ce  '.$id
            );
        }

        $entityManager->remove($hebergement);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list_hebergement');

    }
}
