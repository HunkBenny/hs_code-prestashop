<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\HB_Hscode\CQRS\Command;

use PrestaShop\Module\HB_Hscode\ValueObject\HSCode;
use PrestaShop\Decimal\DecimalNumber;
use PrestaShop\Module\DemoProductForm\CQRS\CommandBuilder\CustomProductCommandsBuilder;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShop\Module\HB_Hscode\Entity\ProductHSCode;
/**
 * Product form is quite big so we have multiple command handlers that saves the fields and performs other required actions
 * This means you can also add your command handler to handle some custom fields added by your module.
 * To do that you will need to create your commandsBuilder which will build commands from product form data
 *
 * @see CustomProductCommandsBuilder
 *
 * It is example command, you can call it whatever you need depending on use case.
 * Command is used to pass the information and call related handler, it doesnt actually do anything by itself.
 * The name of command should reflect the actual use case and should be handled by a handler
 * @see UpdateCustomProductCommandHandler
 */
final class UpdateProductHSCodeCommand
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var HSCode
     */
    private $hs_code;

    /**
     * @param int $productId
     * @param string $hs_code
     */
    public function __construct(int $productId)
    {
        $this->productId = new ProductId($productId);
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }
    /**
     * @param string $hs_code
     *
     * @return static
     */
    public function setHsCode(string $hs_code): self
    {
        $this->hs_code = new HSCode($hs_code);

        return $this;
    }

    /**
    * @return HSCode $hs_code
     */
    public function getHsCode(): HSCode
    {
        return $this->hs_code;
    }
}
