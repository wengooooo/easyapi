<?php

namespace EasyApi\Applications\Base\Items;


class ItemOptions
{
    protected $order = 'list_order';
    protected $sort = 'asc';
    protected $limit = 100;
    protected $offset;
    protected $maxImageNo = 20;
    protected $imageSize = 'origin';
    protected $variations;
    protected $images;
    protected $stock;
    protected $title;
    protected $detail;
    protected $price;
    protected $itemTaxType;
    protected $visible;
    protected $identifier;
    protected $listOrder;
    protected $variation;

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    /**
     * @return int
     */
    public function getMaxImageNo(): int
    {
        return $this->maxImageNo;
    }

    /**
     * @param int $maxImageNo
     */
    public function setMaxImageNo(int $maxImageNo)
    {
        $this->maxImageNo = $maxImageNo;
    }

    /**
     * @return string
     */
    public function getImageSize(): string
    {
        return $this->imageSize;
    }

    /**
     * @param string $imageSize
     */
    public function setImageSize(string $imageSize)
    {
        $this->imageSize = $imageSize;
    }

    /**
     * @return mixed
     */
    public function getVariations()
    {
        return $this->variations;
    }

    /**
     * @param mixed $variations
     */
    public function setVariations($variations)
    {
        $this->variations = $variations;
    }

    /**
     * @return mixed
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * @param mixed $images
     */
    public function setImages($images)
    {
        $this->images = $images;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param mixed $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @param mixed $detail
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return mixed
     */
    public function getListOrder()
    {
        return $this->listOrder;
    }

    /**
     * @param mixed $listOrder
     */
    public function setListOrder($listOrder)
    {
        $this->listOrder = $listOrder;
    }

    /**
     * @return mixed
     */
    public function getVariation()
    {
        return $this->variation;
    }

    /**
     * @param mixed $variation
     */
    public function setVariation($variation)
    {
        $this->variation = $variation;
    }

    /**
     * @return mixed
     */
    public function getItemTaxType()
    {
        return $this->itemTaxType;
    }

    /**
     * @param mixed $itemTaxType
     */
    public function setItemTaxType($itemTaxType)
    {
        $this->itemTaxType = $itemTaxType;
    }



    public function getItemsArray() {
        return [
            'order' => $this->getOrder(),
            'sort' => $this->getSort(),
            'limit' => $this->getLimit(),
            'offset' => $this->getOffset(),
            'max_image_no' => $this->getMaxImageNo(),
            'image_size' => $this->getImageSize(),
        ];
    }

    public function getUpdateOrCreateItemsArray() {
        return [
            'title' => $this->getTitle(),
            'detail' => $this->getDetail(),
            'price' => $this->getPrice(),
            'item_tax_type' => $this->getItemTaxType(),
            'stock' => $this->getStock(),
            'visible' => $this->getVisible(),
            'identifier' => $this->getIdentifier(),
            'list_order' => $this->getListOrder(),
        ];
    }

}
