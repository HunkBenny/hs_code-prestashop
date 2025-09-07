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

namespace PrestaShop\Module\HB_Hscode\CQRS\CommandHandler;
use PrestaShop\Module\HB_Hscode\CQRS\Command\UpdateProductHSCodeCommand;
use PrestaShop\Module\HB_Hscode\Entity\ProductHSCode;
use Doctrine\ORM\EntityManagerInterface;


/**
 * Handles @see UpdateProductHCodeCommand
 */
final class UpdateProductHSCodeCommandHandler
{
    /**
     * This method will be triggered when related command is dispatched
     * (more about cqrs https://devdocs.prestashop.com/8/development/architecture/domain/cqrs/)
     *
     * Note - product form data handler create() method is a little unique
     *
     * @param UpdateProductHScodeCommand $command
     *
     * @see ProductFormDataHandler::create()
     *
     * It will create the product with couple required fields and then call the update method,
     * so you don't actually need to hook on ProductFormDataHandler::create() method
     */
    public function handle(UpdateProductHScodeCommand $command): void
    {
        // Command handlers should contain as less logic as possible, that should be wrapped in dedicated services instead,
        // // but for simplicity of example lets just leave the entity saving logic here
        // $productId = $command->getProductId()->getValue();
        // dump($command);
        // dump($this);
        // /** @var EntityManagerInterface $entityManager */
        // $entityManager = $this->container->get('doctrine.orm.entity_manager');
        
        // $productHSCode = new ProductHSCode();
        // if (empty($productId)) {
        //     // If hscode product is not found it has not been created yet, so we force its ID to match the product ID
        //     $productHSCode->id_product = $productId;
        //     $productHSCode->force_id = true;
        //     $productHSCode->add();
        // } else {
        //     // setFieldsToUpdate can be set to explicitly specify fields for update (other fields would not be updated)
        //     $productHSCode->setFieldsToUpdate($updatedFields);
        //     $productHSCode->update();
        // }
        
        
        // $updatedFields = [];
        // if (null !== $command->getHsCode()) {
        //     $productHSCode->hs_code = $command->getHsCode();
        //     $updatedFields['hs_code'] = true;
        // }

    }
}
