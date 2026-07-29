<?php

namespace MageSuite\ProductTile\Model\Tile\Fragment\Gallery;

class Images implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    public function __construct(\Magento\Catalog\Helper\Image $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    public function getMediaGalleryImages($product, $tileImage = false, $tileImage2x = false, $limit = false, $productImage = false, $productImage2x = false)
    {
        $tileImage = $tileImage ?? 'product_tile_gallery';
        $tileImage2x = $tileImage2x ?? 'product_tile_gallery_x2';
        $productImage = $productImage ?? 'category_page_grid';
        $productImage2x = $productImage2x ?? 'category_page_grid_x2';
        $mediaGallery = $product->getMediaGalleryEntries();

        if (!$mediaGallery) {
            return [];
        }

        $hasLimit = $limit && is_numeric($limit);
        $styles = [$tileImage, $tileImage2x, $productImage, $productImage2x];

        [$baseImage, $galleryImages] = $this->collectGalleryImages($product, $mediaGallery, $hasLimit, $limit, $styles);

        if ($baseImage !== null) {
            array_unshift($galleryImages, $baseImage);
        }

        return $this->applyLimit($galleryImages, $hasLimit, $limit);
    }

    protected function collectGalleryImages($product, $mediaGallery, bool $hasLimit, $limit, array $styles): array
    {
        $baseImage = null;
        $galleryImages = [];
        $imagesCount = 0;

        foreach ($mediaGallery as $mediaGalleryImage) {
            if ($this->isBaseImage($mediaGalleryImage)) {
                $baseImage = $this->buildMediaImage($product, $mediaGalleryImage, $styles);
            } elseif ($this->isHidden($mediaGalleryImage)) {
                continue;
            } elseif (!$hasLimit || $imagesCount < $limit) {
                $galleryImages[] = $this->buildMediaImage($product, $mediaGalleryImage, $styles);
                $imagesCount++;
            }

            if ($this->shouldStopCollecting($hasLimit, $baseImage, $imagesCount, $limit)) {
                break;
            }
        }

        return [$baseImage, $galleryImages];
    }

    protected function shouldStopCollecting(bool $hasLimit, ?array $baseImage, int $imagesCount, $limit): bool
    {
        return $hasLimit && $baseImage !== null && $imagesCount >= $limit;
    }

    protected function applyLimit(array $galleryImages, bool $hasLimit, $limit): array
    {
        if ($hasLimit && count($galleryImages) > $limit) {
            array_pop($galleryImages);
        }

        return $galleryImages;
    }

    protected function isBaseImage($mediaGalleryImage): bool
    {
        return in_array('image', $mediaGalleryImage->getTypes()) !== false;
    }

    protected function isHidden($mediaGalleryImage): bool
    {
        return !empty($mediaGalleryImage['disabled']) || !empty($mediaGalleryImage['removed']);
    }

    protected function buildMediaImage($product, $mediaGalleryImage, array $styles): array
    {
        [$tileImage, $tileImage2x, $productImage, $productImage2x] = $styles;

        $tileImageInstance = $this->imageHelper->init($product, $tileImage)
            ->setImageFile($mediaGalleryImage->getFile());

        $tileImage2xUrl = $this->imageHelper->init($product, $tileImage2x)
            ->setImageFile($mediaGalleryImage->getFile())
            ->getUrl();

        $productImageUrl = $this->imageHelper->init($product, $productImage)
            ->setImageFile($mediaGalleryImage->getFile())
            ->getUrl();

        $productImage2xUrl = $this->imageHelper->init($product, $productImage2x)
            ->setImageFile($mediaGalleryImage->getFile())
            ->getUrl();

        return [
            'tileImageSrc' => $tileImage2xUrl,
            'tileImageSrcSet' => sprintf('%s, %s 2x', $tileImageInstance->getUrl(), $tileImage2xUrl),
            'productImageSrc' => $productImage2xUrl,
            'productImageSrcSet' => sprintf('%s, %s 2x', $productImageUrl, $productImage2xUrl),
            'width' => $tileImageInstance->getWidth(),
            'height' => $tileImageInstance->getHeight(),
        ];
    }
}
