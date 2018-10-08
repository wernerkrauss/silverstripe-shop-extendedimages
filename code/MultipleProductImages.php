<?php

namespace SilverShop\ExtendedImages;


use Bummzack\SortableFile\Forms\SortableUploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\DataList;


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

}
