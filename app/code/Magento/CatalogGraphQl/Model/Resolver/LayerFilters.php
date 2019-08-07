<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\CatalogGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
//use Magento\CatalogGraphQl\DataProvider\Product\LayeredNavigation\LayerBuilder;

/**
 * Layered navigation filters resolver, used for GraphQL request processing.
 */
class LayerFilters implements ResolverInterface
{
    /**
     * @var Layer\DataProvider\Filters
     */
    private $filtersDataProvider;

//    /**
//     * @var LayerBuilder
//     */
//    private $layerBuilder;

    /**
     * @param \Magento\CatalogGraphQl\Model\Resolver\Layer\DataProvider\Filters $filtersDataProvider
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Magento\CatalogGraphQl\Model\Resolver\Layer\DataProvider\Filters $filtersDataProvider,
        \Magento\Framework\Registry $registry
        //LayerBuilder $layerBuilder

    ) {
        $this->filtersDataProvider = $filtersDataProvider;
        $this->registry = $registry;
        //$this->layerBuilder = $layerBuilder;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($value['layer_type'])) {
            return null;
        }
        $aggregations = $this->registry->registry('aggregations');
        return [];
        //return $this->layerBuilder->build($aggregations, 1);
    }
}
