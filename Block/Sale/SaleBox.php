<?php

namespace Magenest\HandlePageRedirection\Block\Sale;

use Magento\Framework\View\Element\Template;

class SaleBox extends Template
{
    public function __construct
    (
        Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory  $productCollectionFactory,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productFactory = $productFactory;
        $this->listProduct = $listProduct;
        parent::__construct($context, $data);
    }

    public function getProductSaleOffJsonData()
    {
        $data = [];
        $products = $this->productFactory->create();
        foreach ($products as $product) {
            if ($product->getSpecialPrice() < $product->getPrice() && !empty($product->getSpecialPrice())) {
                $productCollection = $this->productCollectionFactory->create();
                foreach ($productCollection as $item)
                {
                    $productImage = $this->listProduct->getImage($item, 'product_thumbnail_image');
                    $data[] = [
                        'url' => $item->getProductUrl(),
                        'image' => $productImage->getImageUrl(),
                        'name' => $item->getSku()
                    ];
                }
            }
        }
        return json_encode($data);
    }
}
