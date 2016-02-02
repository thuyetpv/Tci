<?php

namespace Tci\Gridthumbs\Block\Adminhtml\Catalog\Tab;

use Magento\Catalog\Block\Adminhtml\Category\Tab\Product;

class ProductImage extends Product
{
    /**
     * @return Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(['in_category' => 1]);
        }
        $collection = $this->_productFactory->create()->getCollection()->addAttributeToSelect(
            'name'
        )->addAttributeToSelect(
            'image'
        )->addAttributeToSelect(
            'sku'
        )->addAttributeToSelect(
            'price'
        )->addStoreFilter(
            $this->getRequest()->getParam('store')
        )->joinField(
            'position',
            'catalog_category_product',
            'position',
            'product_id=entity_id',
            'category_id=' . (int)$this->getRequest()->getParam('id', 0),
            'left'
        );
        $this->setCollection($collection);

        if ($this->getCategory()->getProductsReadonly()) {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
        }
        return \Magento\Backend\Block\Widget\Grid::_prepareCollection();
    }
    
    /**
     * @return Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumnAfter('image', [
            'header' => __('Image'),
            'align' => 'center',
            'sortable' => true,
            'index' => 'image',
            'renderer' => 'Tci\Gridthumbs\Template\Grid\Renderer\Image'
        ], 'name');
        return parent::_prepareColumns();
    }
}