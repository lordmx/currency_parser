<?php

interface Api_CurrencyParser_Interface
{
    /**
     * Get a rate of the currency from external source (API)
     *
     * @param string $targetIso
     * @param string $sourceIso
     * @return float|null
     */
    public function getRate($targetIso, $sourceIso);
}
