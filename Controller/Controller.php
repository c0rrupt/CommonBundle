<?php

namespace Corrupt\CommonBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class Controller extends BaseController
{
    protected function redirectToRoute($route, $parameters = array())
    {
        return $this->redirect($this->generateUrl($route, $parameters));
    }

    /**
     * @return FlashBag
     */
    protected function getFlashBag()
    {
        return $this->get('session')->getFlashBag();
    }

    /**
     * Add flash message
     *
     * @param $type
     * @param $message
     */
    protected function addFlash($type, $message = null, array $placeholders = array())
    {
        $message = $message ?: sprintf('%s.%s', $this->getRequest()->attributes->get('_route'), $type);
        $this->getFlashBag()->add($type, $this->get('translator')->trans($message, $placeholders));
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }

    /**
     * @param $entity
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($entity)
    {
        return $this->getEntityManager()->getRepository(is_object($entity) ? get_class($entity) : $entity);
    }

    protected function persist($entity, $flush = false)
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->flush();
        }
    }

    protected function remove($entity, $flush = false)
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->flush();
        }
    }

    protected function flush($entity = null)
    {
        $this->getEntityManager()->flush($entity);
    }
}