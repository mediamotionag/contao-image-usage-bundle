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


class ImageResizeLogger implements FrameworkAwareInterface
{
   private $parent;

    public function __construct(
        FrameworkAwareInterface $parent
    ) {
        $this->parent = $parent;
    }
    
    public function resize(ImageInterface $image, ResizeConfiguration $config, ResizeOptions $options)
    {
        $this->parent->resize($image, $config, $options);
    }
    
    public function resizeDeferredImage(DeferredImageInterface $image, bool $blocking = true)
    {
        $this->parent->resizeDeferredImage($image, $blocking);
    }
    
    protected function executeResize(ImageInterface $image, ResizeCoordinates $coordinates, string $path, ResizeOptions $options)
    {
        $this->parent->executeResize($image, $coordinates, $path, $options);
    }
    
    private function hasExecuteResizeHook()
    {
        $this->parent->hasExecuteResizeHook();
    }
    
    private function hasGetImageHook()
    {
        $this->parent->hasGetImageHook();
    }
    
    private function enhanceImagineException(ImagineRuntimeException $exception, ImageInterface $image)
    {
        $this->parent->enhanceImagineException($exception, $image);
    }
    
    private function formatIsSupported(string $format, ImagineInterface $imagine)
    {
        $this->parent->formatIsSupported($format, $imagine);
    }
} 