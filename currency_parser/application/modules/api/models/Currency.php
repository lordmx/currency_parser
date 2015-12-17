<?php

class Api_Model_Currency extends Api_Model_Base
{
    /**
     * @var int
     */
    protected $_id;

    /**
     * @var string
     */
    protected $_title;

    /**
     * @var string
     */
    protected $_iso;

    /**
     * @var float
     */
    protected $_rate;

    /**
     * @var DateTime
     */
    protected $_lastUpdatedAt;

    /**
     * @param string $iso
     * @param string $title
     * @param float $rate
     * @throws Api_Exception_NoArgument
     */
    public function __construct($iso, $title = null, $rate = null)
    {
        if (!$iso) {
            throw (new Api_Exception_NoArgument())->setField('iso');
        }

        $this->_iso = $iso;
        $this->_title = $title;

        if (!is_null($rate)) {
            $this->setRate($rate);
        }
    }

    /**
     * Create the model via DTO
     *
     * @param Api_Dto_Currency $dto
     * @return Api_Model_Currency
     */
    public static function createFromDto(Api_Dto_Currency $dto)
    {
        $currency = new static($dto->iso, $dto->title);

        if ($dto->rate) {
            $currency->setRate($dto->rate);
        }

        if ($dto->lastUpdatedAt) {
            $currency->setLastUpdatedAt($dto->lastUpdatedAt);
        }

        return $currency;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->getLastUpdatedAt()->getTimestamp() < time();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = $title;
    }

    /**
     * @return string
     */
    public function getIso()
    {
        return $this->_iso;
    }

    /**
     * @param string $iso
     */
    public function setIso($iso)
    {
        $this->_iso = $iso;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return round((float) $this->_rate, 2);
    }

    /**
     * @param float $rate
     */
    public function setRate($rate)
    {
        $this->_rate = $rate;
    }

    /**
     * @return DateTime
     */
    public function getLastUpdatedAt()
    {
        return $this->_lastUpdatedAt;
    }

    /**
     * @param DateTime $lastUpdatedAt
     */
    public function setLastUpdatedAt($lastUpdatedAt)
    {
        $this->_lastUpdatedAt = $lastUpdatedAt;
    }
}
