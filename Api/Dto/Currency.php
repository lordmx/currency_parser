<?php

class Api_Dto_Currency
{
    /**
     * Currencies ID
     *
     * @var int
     * @response
     */
    public $id;

    /**
     * Currencies ISO code
     *
     * @var string
     * @request
     * @response
     * @required
     */
    public $iso;

    /**
     * Currencies name
     *
     * @var string
     * @request
     * @response
     * @required
     */
    public $title;

    /**
     * Rate of the currency
     *
     * @var double
     * @response
     * @required
     */
    public $rate;

    /**
     * Date of last updated
     *
     * @var DateTime
     * @response last_updated_at
     */
    public $lastUpdatedAt;
}
