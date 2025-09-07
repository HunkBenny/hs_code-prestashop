<?php

namespace PrestaShop\Module\HB_Hscode\ValueObject;

class HSCode {
    /** 
     * hscode
     * @var string
     */
    private $hs_code;

    /**
     * @param string $hs_code
     */
    public function __construct(string $hs_code)
    {
        if (!preg_match('/[0-9]{6,12}/', $hs_code) && !empty($hs_code)) {
            throw new \InvalidArgumentException('Invalid HSCode format');
        }
        $this->hs_code = $hs_code;
    }

    /**
     * @return string
     */
    public function getHsCode(): string
    {
        return $this->hs_code;
    }
}