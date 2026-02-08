<?php

declare(strict_types=1);

use PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode;
use PrestaShop\Module\HB_Hscode\Entity\HBProductHSCode as EntityHBProductHSCode;
use PrestaShop\Module\HB_Hscode\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\HB_Hscode\Repository\ProductHSCodeRepository;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;


if (!defined('_PS_VERSION_')) {
    exit;
}


class HB_Hscode extends Module
{

    public function __construct()
    {
        $this->name = 'hb_hscode';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'HunkBenny ';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6.0',
            'max' => '8.2.9999',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('HS Codes');
        $this->description = $this->l('Manage HS Codes for products.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
        if (!Configuration::get('MYMODULE_NAME')) {
            $this->warning = $this->l('No name provided.');
        }
    }

    public function install()
    {

        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        $this->installDB();
        return parent::install() && $this->registerHook('actionProductFormBuilderModifier') &&
            $this->registerHook('actionAfterCreateProductFormHandler') &&
            $this->registerHook('actionAfterUpdateProductFormHandler') &&
            $this->registerHook('displayPDFInvoice');
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    private function installDB()
    {
        $new_table_name = _DB_PREFIX_ . 'hb_product_hscode';
        $sql = 'CREATE TABLE IF NOT EXISTS ' . $new_table_name . ' (
            id_product int UNSIGNED NOT NULL NOT NULL,
            hs_code VARCHAR(12) DEFAULT NULL,
            PRIMARY KEY (id_product),
            FOREIGN KEY (id_product) REFERENCES ' . _DB_PREFIX_ . 'product(id_product)
            );';
        Db::getInstance()->execute($sql);

        // insert hs_codes
        /**
         * @var array
         */
        $product_ids = Db::getInstance()->executeS('SELECT id_product FROM ' . _DB_PREFIX_ . 'product;');
        $this->insertHSCodes($product_ids);
    }

    /**
     * Create a tuple used by SQL for the HS codes
     * @param int $id_product
     * @param string $hs_code
     * @return string
     */
    private function create_tuple_hscode(int $id_product, string $hs_code): string
    {
        if (!preg_match('/[0-9]{6,12}/', $hs_code) && $hs_code !== 'NULL') {
            throw new InvalidArgumentException('HSCODE: HScode should be between 6 and 12 numbers long and only consist out of digits.');
        }
        $value = '(' . $id_product . ', ' . $hs_code . ')';
        return $value;
    }

    /**
     * Builds query containing all product ids and hs_codes for DB insert
     * 
     * ! This can only be used when creating a new product.
     * @param array $ids_product
     * @param array $hs_codes
     * @throws \InvalidArgumentException
     * @return void
     */
    public function  insertHSCodes(array $ids_product, array $hs_codes = [])
    {
        if (!empty($hs_codes) && (sizeof($ids_product) != sizeof($hs_codes))) {
            throw new InvalidArgumentException('HS_CODES: Product array size is not equal to HS Code array.');
        }
        $number_products = sizeof($ids_product);
        /**
         * Whether array of hs_codes is empty. If this is the case, then we will use NULL
         * @var bool
         */
        $empty_array = empty($hs_codes);

        // Ignore because if the product already has an HS code, then we will not insert it again
        $new_table_name = _DB_PREFIX_ . 'hb_product_hscode';
        $sql = 'INSERT IGNORE INTO ' . $new_table_name . ' (id_product, hs_code)
        VALUES
        ';
        foreach ($ids_product as $index => $id_product_json) {
            $id_product = (int) $id_product_json['id_product'];
            // Use hs_code or NULL if no hs_code is passed.
            $value = $this->create_tuple_hscode($id_product, $empty_array ? 'NULL' : $hs_codes[$index]);
            $sql = $sql . $value;
            if ($index != $number_products - 1) {
                $sql = $sql . ', ';
            }
        }
        $sql = $sql . ';';
        Db::getInstance()->execute($sql);
    }

    /**
     * Inserts or updates the HS code of a product
     * @param int $id_product
     * @param mixed $hs_code
     * @return void
     */
    public function insertOrUpdateHScode(int $id_product, ?string $hs_code = null)
    {
        $new_table_name = _DB_PREFIX_ . 'hb_product_hscode';
        $sql = 'INSERT IGNORE INTO ' . $new_table_name . ' (id_product, hs_code)
            VALUES ' . $this->create_tuple_hscode($id_product, $hs_code) . '
            ON DUPLICATE KEY UPDATE hs_code = ' . $hs_code . ';';
        Db::getInstance()->execute($sql);
    }

    public function hookActionProductFormBuilderModifier(array $params): void
    {

        /** @var ProductFormModifier $productFormModifier */
        $productFormModifier = $this->get(ProductFormModifier::class);
        $productId = new ProductId((int) $params['id']);
        $productFormModifier->modify($productId, $params['form_builder']);
    }

    public function hookActionAfterCreateProductFormHandler(array $params): void
    {
        $hs_code = $params['form_data']['details']['references']['hs_code'];
        $hs_code = empty($hs_code) ? null : $hs_code;
        $product_id = (int) $params['form_data']['id'];

        /** @var ProductHSCodeRepository $repository */
        $repository = $this->get('hb_product_hs_code_repository');
        /**
         * @var HbProductHSCode $product_hs_code
         */
        $product_hs_code = new HbProductHSCode();
        $product_hs_code = $product_hs_code->setIdProduct($product_id)->setHsCode($hs_code);

        $repository->add($product_hs_code);
    }

    public function hookActionAfterUpdateProductFormHandler(array $params): void
    {
        $hs_code = $params['form_data']['details']['references']['hs_code'];
        $hs_code = empty($hs_code) ? null : $hs_code;
        $product_id = (int) $params['form_data']['id'];

        /** @var ProductHSCodeRepository $repository */
        $repository = $this->get('hb_product_hs_code_repository');
        $product = $repository->find($product_id); 
        if ($product === null) {
            // If it does not exist, create a new one
            $product = new HbProductHSCode();
            $product->setIdProduct($product_id)->setHsCode($hs_code);
            $repository->add($product);
        } else {
            $product->setHsCode($hs_code);
            $repository->flush();
        }
        
    }

    public function getHsCode(int $id_product): ?string
    {
        $sql = "SELECT hs_code FROM " . _DB_PREFIX_ . "hb_product_hscode WHERE id_product = " . (int)$id_product;
        $value = Db::getInstance()->getValue($sql);
        return $value ? $value : null;
    }

    public function setHsCode(int $id_product, ?string $hs_code): void
    {
        $this->insertOrUpdateHScode($id_product, $hs_code);
    }

    /**
     * Makes it possible to show the hscodes on PDF invoices. This is important for customs
     * @param array $hookArgs
     * @return void
     */
    public function hookDisplayPDFInvoice(array $hookArgs): void
    {
        $order = new Order($hookArgs['object']->id_order);
        $products = $order->getProducts();
        $hs_codes = [];
        foreach ($products as $product) {
            $hs_code = $this->getHsCode((int) $product['id_product']);
            $hs_codes[$product['id_product']] = $hs_code ? $hs_code : '/';
        }
        $hookArgs['object']->hs_codes = $hs_codes;
    }
}
