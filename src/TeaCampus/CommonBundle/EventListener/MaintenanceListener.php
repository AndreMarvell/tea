<?php

namespace TeaCampus\CommonBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MaintenanceListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $maintenance = $this->container->hasParameter('maintenance') ? $this->container->getParameter('maintenance') : false;

        $debug = in_array($this->container->get('kernel')->getEnvironment(), array('test', 'dev'));

        if ($maintenance && !$debug) {
            
            if (isset($_SERVER['HTTP_CLIENT_IP'])
                || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
                || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1','193.49.161.211','192.168.1.45','78.199.144.159', '78.227.171.80')) || php_sapi_name() === 'cli-server')
            ) {
                $engine = $this->container->get('templating');
                $content = $engine->render('TwigBundle:Exception:error503.html.twig');
                $event->setResponse(new Response($content, 503));
                $event->stopPropagation();
            }
            
        }

    }
}