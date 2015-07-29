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
            
            $iPs = array(
                '127.0.0.1',
                'fe80::1',
                '::1',
                '193.49.161.211',
                '192.168.1.45',
                '78.199.144.159',
                '78.227.171.80',
                '154.72.166.174',
                '193.52.102.13',
                '193.52.102.11',
                '83.192.149.141',
                '31.39.90.231',
                '37.160.131.240',
                '82.246.179.247',
                '82.243.157.115',
                '154.72.166.4',
                '193.52.200.72',
                '172.16.92.243',
                '41.205.22.44',
                '109.16.182.148',
                '93.20.177.194'
            );
            
            if(
                !(in_array(@$_SERVER['REMOTE_ADDR'], $iPs) || php_sapi_name() === 'cli-server')
            ){
            
                $iPsDHCP = array(
                    '83.192.149',
                    '83.192.145',
                    '193.52.102',
                    '10.33.2',
                    '154.72.166',
                    '154.72.135',
                    '193.52.200',
                    '172.16.92',
                    '93.20.177'
                );
                $ipParts = explode('.', @$_SERVER['REMOTE_ADDR']);
                $ipPart  = $ipParts[0].'.'.$ipParts[1].'.'.$ipParts[2];
                
                if(!(in_array($ipPart, $iPsDHCP))){
                    $engine = $this->container->get('templating');
                    $content = $engine->render('TwigBundle:Exception:error503.html.twig');
                    $event->setResponse(new Response($content, 503));
                    $event->stopPropagation();
                }
            }
            
        }

    }
}