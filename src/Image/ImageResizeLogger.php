<?php
/**
 * @package   ImageUsageBundle
 * @author    Media Motion AG
 * @license   MEMO
 * @copyright Media Motion AG
 */

namespace Memo\ImageUsageBundle\Image;

use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\Image\DeferredResizer as ImageResizer;
use Contao\Image\DeferredImageInterface;
use Contao\CoreBundle\Image\LegacyResizer;
use Contao\Image\ImageInterface;
use Contao\Image\ResizeConfiguration;
use Contao\Image\ResizeOptions;
use Imagine\Image\ImagineInterface;
use Contao\Image\ImageDimensions;
use Contao\Image\ImportantPart;
use Contao\Image\DeferredResizerInterface;

class ImageResizeLogger implements DeferredResizerInterface
{
   private $parent;

    public function __construct(
        DeferredResizerInterface $parent
    ) {
        $this->parent = $parent;
    }
    
    public function resize(ImageInterface $image, ResizeConfiguration $config, ResizeOptions $options): ImageInterface
    {
        $this->parent->resize($image, $config, $options);
    }
    
    public function getDeferredImage(string $targetPath, ImagineInterface $imagine): ?DeferredImageInterface
    {
        $this->parent->getDeferredImage($targetPath, $imagine);
    }
    
    public function resizeDeferredImage(DeferredImageInterface $image, bool $blocking = true): ?ImageInterface
    {
        $this->parent->resizeDeferredImage($image, $blocking);
    }
} 