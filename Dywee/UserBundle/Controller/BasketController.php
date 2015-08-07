<?php

namespace Dywee\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BasketController extends Controller
{
    public function billingHelperAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ar = $em->getRepository('DyweeAddressBundle:Address');
        $user = $this->getUser();
        $as = $ar->findBy(array('user' => $user));

        $data = array('addressList' => $as);

        return $this->render('DyweeUserBundle:Basket:facturationShower.html.twig', $data);
    }

    public function shippingHelperAction()
    {
        $em = $this->getDoctrine()->getManager();
        $ar = $em->getRepository('DyweeAddressBundle:Address');
        $user = $this->getUser();
        $as = $ar->findBy(array('user' => $user));

        $data = array('addressList' => $as);

        return $this->render('DyweeUserBundle:Basket:shippingAddressShower.html.twig', $data);
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
