<?php
/**
 * Created by PhpStorm.
 * User: Marvell
 * Date: 08/01/16
 * Time: 00:01
 */

namespace TeaCampus\CommonBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;



class TeaCampusCacheListener {

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('max-age', 0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);
    }
} 