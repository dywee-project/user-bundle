<?php

namespace Dywee\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dywee\UserBundle\Entity\User;

class UserController extends Controller
{
    public function tableAction($role = 'user')
    {
        $ur = $this->getDoctrine()->getManager()->getRepository('DyweeUserBundle:User');
        if($role == 'admin')
            $userList = $ur->findBy(array('roles' => 'ROLE_ADMIN'));
        else $userList = $ur->findBy(array('roles' => 'ROLE_USER'));
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
            return $this->redirect($this->generateUrl('dywee_admin_homepage'));
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

    public function adminHomePageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sr = $em->getRepository('DyweeWebsiteBundle:Website');
        $siteList = $sr->findAll();

        $cr = $em->getRepository('DyweeOrderBundle:BaseOrder');
        $activeOrders = $cr->findByState(2);

        $pr = $em->getRepository('DyweeProductBundle:Product');
        $activeProducts = $pr->countByState(1);

        return $this->render('DyweeCoreBundle:Admin:index.html.twig', array('siteList' => $siteList, 'activeOrders' => count($activeOrders), 'activeProducts' => $activeProducts));
    }
}
