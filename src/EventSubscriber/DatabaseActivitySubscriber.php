<?php
namespace App\EventSubscriber;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsDoctrineListener(event: Events::postUpdate)]
#[AsDoctrineListener(event: Events::postRemove)]

class DatabaseActivitySubscriber
{
    private KernelInterface $appKernel;
    private $rootDir;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->rootDir = $appKernel->getProjectDir();
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postRemove(PostRemoveEventArgs $args): void
    {
        $this->logActivity('remove', $args->getObject());
    }

    public function postUpdate(PostUpdateEventArgs $args): void
    {
        $this->logActivity('update', $args->getObject());
    }

    public function logActivity(string $action, mixed $entity): void
    {
        // dd($entity);

        if(($entity instanceof Product) && $action === 'remove'){
            $imageUrls = $entity->getImageUrls();

            foreach($imageUrls as $imageUrl){
                $filelink = $this->appKernel->getProjectDir()."/public/assets/images/products/". $imageUrl;
                $this->deleteImage($filelink);
            }
        }

        if(($entity instanceof Category) && $action === 'remove'){
            // supprimer l'image
            $filename = $entity->getImageUrl();
            // Avec le kernel :
            $filelink = $this->rootDir."/public/assets/images/categories/". $filename;

            // $result = unlink($filelink);
            // dd($result);
            // if (file_exists($filelink)) {
            //     unlink($filelink);
            // }

            $this->deleteImage($filelink);
        }
        // dd($entity);
    }

    public function deleteImage(string $filelink): void
    {
        try {
            $result = unlink($filelink);
        } catch (\Throwable $th) {

        }
    }
}
