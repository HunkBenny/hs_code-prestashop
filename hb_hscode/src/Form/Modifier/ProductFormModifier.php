<?php

declare(strict_types=1);


namespace Prestashop\Module\HB_Hscode\Form\Modifier;

use Product;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use PrestaShop\Module\HB_Hscode\Repository\ProductHSCodeRepository;
use PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode;

final class ProductFormModifier
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormBuilderModifier
     */
    private $formBuilderModifier;

    /**
     * @var ProductHSCodeRepository
     */
    private $repository;

    /**
     * @param TranslatorInterface $translator
     * @param FormBuilderModifier $formBuilderModifier
     */
    public function __construct(
        TranslatorInterface $translator,
        FormBuilderModifier $formBuilderModifier,
        ProductHSCodeRepository $repository
    ) {
        $this->translator = $translator;
        $this->formBuilderModifier = $formBuilderModifier;
        $this->repository = $repository;
    }

    /**
     * @param ProductId|null $productId 
     * @param FormBuilderInterface $productFormBuilder
     */
    public function modify(
        ?ProductId $productId,
        FormBuilderInterface $productFormBuilder
    ): void {
        $idValue = $productId ? $productId->getValue() : null;
        if ($idValue == null) {
            return;
        }
        $product = new Product($idValue);
        $this->modifyDetailsTab($product, $productFormBuilder);
    }

    private function modifyDetailsTab(Product $product, FormBuilderInterface $productFormBuilder) : void {
        $detailsReferencesTab = $productFormBuilder->get('details')->get('references');

        $hs_code = '';
        /** @var HbProductHSCode $product_hs_code */
        $product_hs_code = $this->repository->find($product->id);

        if ($product_hs_code) {
            $hs_code = $product_hs_code->getHsCode();
        }

        $this->formBuilderModifier->addAfter($detailsReferencesTab,
        'isbn',
        'hs_code',
        TextType::class,
        [
            // you can remove the label if you dont need it by passing 'label' => false
            'label' => $this->translator->trans('HS-code', [], 'Modules.HBHscode.Admin'),
            // customize label by any html attribute
            'label_attr' => [
                'title' => 'h2',
                'class' => 'text-info',
            ],
            'data' => $hs_code,
            'empty_data' => '',
            'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
        ]
    );
    }
}
