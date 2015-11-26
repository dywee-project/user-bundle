<?php

namespace Dywee\UserBundle\Controller;

use Dywee\UserBundle\Form\Type\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dywee\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function tableAction($role = 'user')
    {
        $ur = $this->getDoctrine()->getManager()->getRepository('DyweeUserBundle:User');
        if($role == 'admin')
            $userList = $ur->findBy(array('roles' => 'ROLE_ADMIN'));
        else $userList = $ur->findAll();
        return $this->render('DyweeUserBundle:User:table.html.twig', array('userList' => $userList));
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public function addAction()
    {
        return new Response('User:add');
    }

    public function listAction($type = 'all')
    {
        $r = $this->getDoctrine()->getManager()->getRepository('DyweeUserBundle:User');
        if($type == 'all')
        {
            $list = $r->findAll();
            return $this->render('DyweeUserBundle:User:roughList.html.twig', array('userList' => $list));
        }
    }

    public function navsideAction()
    {
        return $this->render('DyweeUserBundle:CMS:navside.html.twig');
    }

    public function homePageAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
        {
            return $this->redirect($this->generateUrl('dywee_admin_homepage'));
        }
        else if($this->get('security.authorization_checker')->isGranted('ROLE_USER'))
            return $this->redirect($this->generateUrl('dywee_user_dashboard'));
        else throw AccessDeniedException('Accès limité aux membres.');
    }

    public function dashboardAction()
    {
        $or = $this->getDoctrine()->getManager()->getRepository('DyweeOrderBundle:BaseOrder');
        $os = $or->findBy(array('billingUser' => $this->getUser()));

        return $this->render('DyweeUserBundle:User:dashBoard.html.twig', array('orderList' => $os));
    }

    public function adminHomePageAction($websiteId = null)
    {
        $em = $this->getDoctrine()->getManager();
        $wr = $em->getRepository('DyweeWebsiteBundle:Website');

        $activeWebsite = $this->get('session')->get('activeWebsite');
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $validatedWebsite = false;

        //Si un site est passé en paramètre (sélectionné par l'user)
        if($websiteId != null)
        {
            $website = $wr->findOneById($websiteId);
            if($website != null && ($website->getOwner() == $user || $website->hasContributor($user))) {
                $activeWebsite = $website;
                $validatedWebsite = true;
                $this->get('session')->set('activeWebsite', $activeWebsite);
            }
            else{
                $this->get('session')->getFlashBag()->add('warning', 'Vous n\'avez pas accès à ce site');
            }
        }
        //Si un site est stocké en session, on vérifie quand même que l'user peut y accéder
        else if($activeWebsite)
        {
            $websiteToTest = $wr->findOneById($activeWebsite);
            if($websiteToTest != null && ($websiteToTest->getOwner() == $user || $websiteToTest->hasContributor($user))) {
                $validatedWebsite = true;
            }
        }
        //Sinon on regarde dans la liste si l'user n'a qu'un site, pour le rendre directement actif
        else {
            $ws = $wr->getFromUser($user);
            if(count($ws) == 1)
            {
                $website = $ws[0];
                $activeWebsite = $website;
                $validatedWebsite = true;
                $this->get('session')->set('activeWebsite', $activeWebsite);
            }
            //return $this->render('DyweeWebsiteBundle:Website:list.html.twig', array('websiteList' => $ws));
        }

        if($validatedWebsite)
        {
            return $this->render('DyweeWebsiteBundle:Admin:index.html.twig');
        }
        else
        {
            $ws = $wr->findByOwner($this->get('security.token_storage')->getToken()->getUser());
            return $this->render('DyweeWebsiteBundle:Website:list.html.twig', array('websiteList' => $ws));
        }

    }
}
