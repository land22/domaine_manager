<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\DomainNameType;
use App\Entity\DomaineName;
use Iodev\Whois\Factory;

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
     * @Route("/admin/list_domainName", name="admin_list_domainName")
     */
    public function liste_domainName(){
        $repos = $this->getDoctrine()->getRepository(DomaineName::class);
        $whois = Factory::get()->createWhois();
        $domainName = $repos->findAll();
        $i = 0;
        $date = [];
        dump($domainName);
       foreach ($domainName as $data){
           $info = $whois->loadDomainInfo($data->getName());
            $date[$i]["expiration"] = $info->expirationDate;
           $date[$i]["creation"] = $info->creationDate;
           $i++;
       }
        return $this->render('admin/list_domainName.html.twig',[
            'domainNames' =>$domainName,
            'dateInfo'=>$date
        ]);
    }
    /**
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
}
