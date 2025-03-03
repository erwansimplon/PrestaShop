<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace PrestaShop\PrestaShop\Core\Form\IdentifiableObject\CommandBuilder\Product;

use PrestaShop\PrestaShop\Core\Domain\Product\Command\UpdateProductPricesCommand;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\CommandBuilder\CommandBuilder;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\CommandBuilder\CommandBuilderConfig;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\CommandBuilder\DataField;

/**
 * Builder used to build UpdateProductPricesCommand
 */
class PricesCommandsBuilder implements MultiShopProductCommandsBuilderInterface
{
    /**
     * @var string
     */
    private $modifyAllNamePrefix;

    /**
     * @param string $modifyAllNamePrefix
     */
    public function __construct(string $modifyAllNamePrefix)
    {
        $this->modifyAllNamePrefix = $modifyAllNamePrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCommands(ProductId $productId, array $formData, ShopConstraint $singleShopConstraint): array
    {
        if (!isset($formData['pricing'])) {
            return [];
        }

        $priceData = $formData['pricing'];
        $config = new CommandBuilderConfig($this->modifyAllNamePrefix);
        $config
            ->addMultiShopField('[retail_price][price_tax_excluded]', 'setPrice', DataField::TYPE_STRING)
            ->addMultiShopField('[retail_price][ecotax]', 'setEcotax', DataField::TYPE_STRING)
            ->addMultiShopField('[retail_price][tax_rules_group_id]', 'setTaxRulesGroupId', DataField::TYPE_INT)
            ->addMultiShopField('[on_sale]', 'setOnSale', DataField::TYPE_BOOL)
            ->addMultiShopField('[wholesale_price]', 'setWholesalePrice', DataField::TYPE_STRING)
            ->addMultiShopField('[unit_price][price_tax_excluded]', 'setUnitPrice', DataField::TYPE_STRING)
            ->addMultiShopField('[unit_price][unity]', 'setUnity', DataField::TYPE_STRING)
        ;

        $commandBuilder = new CommandBuilder($config);
        $shopCommand = new UpdateProductPricesCommand($productId->getValue(), $singleShopConstraint);
        $allShopsCommand = new UpdateProductPricesCommand($productId->getValue(), ShopConstraint::allShops());

        return $commandBuilder->buildCommands($priceData, $shopCommand, $allShopsCommand);
    }
}
