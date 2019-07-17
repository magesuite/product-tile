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

    public function getMediaGalleryImages($product, $image2x = false, $image = false, $limit = false)
    {
        $image = $image ? $image : 'category_page_grid';
        $image2x = $image2x ? $image2x : 'category_page_grid@2x';

        $galleryImages = [];

        $mediaGallery = $product->getMediaGalleryEntries();

        if(!$mediaGallery){
            return [];
        }

        foreach($mediaGallery as $mediaGalleryImage) {
            $thumbUrl = $this->imageHelper->init($product, $image)
                ->setImageFile($mediaGalleryImage->getFile())
                ->getUrl();

            $smallImageUrl = $this->imageHelper->init($product, $image2x)
                ->setImageFile($mediaGalleryImage->getFile())
                ->getUrl();

            $mediaImage = [
                'thumb' => $thumbUrl,
                'small_image' => $smallImageUrl,
                'srcset' => sprintf('%s, %s 2x', $thumbUrl, $smallImageUrl),
                'webpSrcset' => sprintf('%s.webp, %s.webp 2x', $thumbUrl, $smallImageUrl),
            ];

            if(in_array('image', $mediaGalleryImage->getTypes()) !== false){
                array_unshift($galleryImages, $mediaImage);
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
