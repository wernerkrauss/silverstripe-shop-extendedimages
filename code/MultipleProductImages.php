<?php

namespace SilverShop\ExtendedImages;


use Bummzack\SortableFile\Forms\SortableUploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;
use SilverStripe\ORM\SS_List;


/**
 * Based on the recipe in the recipe for Multiple images in
 * the docs.
 *
 * @author Mark Guinn <mark@adaircreative.com>
 * @date 08.20.2013
 * @package shop_extendedimages
 */
class MultipleProductImages extends DataExtension
{
    private static $many_many = [
        'AdditionalImages' => Image::class,
    ];

    private static $many_many_extraFields = [
        'AdditionalImages' => [
            'SortOrder' => 'Int',
        ],
    ];

    private static $owns = [
        'AdditionalImages'
    ];

    private static $add_additional_images_to_sitemap = false;

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $newfields = [
            new SortableUploadField('AdditionalImages', _t('SHOPEXTENDEDIMAGES.AdditionImages', 'Additional Images')),
            new LiteralField('additionalimagesinstructions', '<p>' . _t('SHOPEXTENDEDIMAGES.Instructions',
                    'You can change the order of the Additional Images by clicking and dragging on the image thumbnail.') . '</p>'),
        ];
        if ($fields->hasTabSet()) {
            $fields->addFieldsToTab('Root.Images', $newfields);
        } else {
            foreach ($newfields as $field) {
                $fields->push($field);
            }
        }
    }

    /**
     * Combines the main image and the secondary images
     * @return ArrayList
     */
    public function AllImages()
    {
        $list = new ArrayList($this->SortedAdditionalImages()->toArray());
        $main = $this->owner->Image();
        if ($main && $main->exists()) {
            $list->unshift($main);
        }
        return $list;
    }

    /**
     * @return DataList
     */
    public function SortedAdditionalImages()
    {
        $list = $this->owner->AdditionalImages()->sort('SortOrder');
        return $list;
    }

    /**
     * @param SS_List $list
     */
    public function updateImagesForSitemap(SS_List $list)
    {
        $cachedImages = [];

        if (!$this->addImagesToSitemap()) {
            return;
        }

        foreach ($this->owner->AdditionalImages() as $image) {
            if ($image && $image->exists() && !isset($cachedImages[$image->ID])) {
                $cachedImages[$image->ID] = true;

                $list->push($image);
            }
        }
    }

    private function addImagesToSitemap()
    {
        return $this->owner->config()->get('add_additional_images_to_sitemap');
    }
}
