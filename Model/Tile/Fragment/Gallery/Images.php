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
        $galleryImages = [];

        if (!$mediaGallery) {
            return [];
        }

        foreach($mediaGallery as $mediaGalleryImage) {
            $tileImageInstance = $this->imageHelper->init($product, $tileImage)
                ->setImageFile($mediaGalleryImage->getFile());

            $tileImageUrl = $tileImageInstance->getUrl();

            $tileImageWidth = $tileImageInstance->getWidth();

            $tileImageHeight = $tileImageInstance->getHeight();

            $tileImage2xUrl = $this->imageHelper->init($product, $tileImage2x)
                ->setImageFile($mediaGalleryImage->getFile())
                ->getUrl();

            $productImageUrl = $this->imageHelper->init($product, $productImage)
                ->setImageFile($mediaGalleryImage->getFile())
                ->getUrl();

            $productImage2xUrl = $this->imageHelper->init($product, $productImage2x)
                ->setImageFile($mediaGalleryImage->getFile())
                ->getUrl();

            $mediaImage = [
                'tileImageSrc' => $tileImage2xUrl,
                'tileImageSrcSet' => sprintf('%s, %s 2x', $tileImageUrl, $tileImage2xUrl),
                'webpTileImageSrcSet' => sprintf('%s.webp, %s.webp 2x', $tileImageUrl, $tileImage2xUrl),
                'productImageSrc' => $productImage2xUrl,
                'productImageSrcSet' => sprintf('%s, %s 2x', $productImageUrl, $productImage2xUrl),
                'width' => $tileImageWidth,
                'height' => $tileImageHeight,
            ];

            if (in_array('image', $mediaGalleryImage->getTypes()) !== false){
                array_unshift($galleryImages, $mediaImage);
                continue;
            }

            if (!empty($mediaGalleryImage['disabled']) || !empty($mediaGalleryImage['removed'])) {
                continue;
            }

            $galleryImages[] = $mediaImage;
        }

        if($limit and is_numeric($limit)){
            $galleryImages = array_slice($galleryImages, 0, $limit);
        }

        return $galleryImages;
    }
}
