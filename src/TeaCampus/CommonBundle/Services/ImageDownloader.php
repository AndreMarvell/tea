<?php

namespace TeaCampus\CommonBundle\Services;

use Application\Sonata\MediaBundle\Entity\Media;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImageDownloader
{
    private $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    
    public function downloadFile ($url, $nom, $context, $basename) {
        $newfname = __DIR__.'/../../../../web/uploads/'.$basename;
        
        $file = fopen ($url, "rb");
        
        if ($file) {
          $newf = fopen ($newfname, "wb");
          if ($newf)
          while(!feof($file)) {
            fwrite($newf, fread($file, 1024 * 8 ), 1024 * 8 );
          }
        }
        $media = new Media;
        $media->setBinaryContent($newfname);
        $media->setContext($context);
        $media->setProviderName('sonata.media.provider.image');
        $media->setName($nom);
         
        $this->container->get('sonata.media.manager.media')->save($media);
        
        if ($file) 
            fclose($file);
        if ($newf) 
            fclose($newf);
        
        unlink($newfname);
        
        return $media;
    }


}