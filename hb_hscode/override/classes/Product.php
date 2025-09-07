<?php

use PrestaShop\Module\HB_Hscode\Entity\HbProductHSCode;

class Product extends ProductCore
{
    public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context|null $context = null)
    {
        parent::__construct($id_product, $full, $id_lang, $id_shop, $context);
        $this->webserviceParameters['fields']['hs_code'] = [
            'getter' => 'getHSCodews',
            'setter' => 'setHSCodews',
        ];
    }

    public function getHSCodews()
    {
        if (Module::isEnabled('hb_hscode')) {
            $module = Module::getInstanceByName('hb_hscode');
            return $module->getHsCode($this->id);
        }
        return null;
    }

    public function setHSCodews($hs_code)
    {
        if (Module::isEnabled('hb_hscode')) {
            $module = Module::getInstanceByName('hb_hscode');
            $module->setHsCode($this->id, $hs_code);
        }
    }
}
