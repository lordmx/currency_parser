<?php

class Api_Service_Currency
{
    const RUB = 'RUB';

    /**
     * @var Api_CurrencyParser_Interface
     */
    private $_parser;

    /**
     * @var Api_Repository_Interface
     */
    private $_repository;

    /**
     * @var string
     */
    private $_sourceIso;

    /**
     * @param Api_Repository_Interface $repository
     * @param Api_CurrencyParser_Interface $parser
     * @param string $sourceIso
     */
    public function __construct(
        Api_Repository_Interface $repository,
        Api_CurrencyParser_Interface $parser,
        $sourceIso = self::RUB
    ) {
        $this->_repository = $repository;
        $this->_parser = $parser;
        $this->_sourceIso = $sourceIso;
    }

    /**
     * Create a new model using data from DTO
     *
     * @param Api_Dto_Currency $dto
     * @return Api_Model_Currency
     * @throws Api_Exception_NoArgument
     * @throws Api_Exception_AlreadyExists
     */
    public function create(Api_Dto_Currency $dto)
    {
        if (!$dto->iso) {
            throw (new Api_Exception_NoArgument())->setName('iso');
        }

        $models = $this->_repository->findByDto($dto);

        if ($models) {
            throw (new Api_Exception_AlreadyExists())->setIso($dto->iso);
        }

        $currency = Api_Model_Currency::createFromDto($dto);
        $this->_repository->persist($currency);

        return $this->updateRate($currency);
    }

    /**
     * Get currency models via DTO criteria
     *
     * @param Api_Dto_Currency $dto
     * @param int $limit
     * @param int $offset
     * @return Api_Model_Currency[]
     */
    public function findByDto(Api_Dto_Currency $dto, $limit = 10, $offset = 0)
    {
        return $this->_repository->findByDto($dto, $limit, $offset);
    }

    /**
     * Get count of models that can be returned via DTO criteria
     *
     * @param Api_Dto_Currency $dto
     * @return int
     */
    public function countByDto(Api_Dto_Currency $dto)
    {
        return $this->_repository->countByDto($dto);
    }

    /**
     * Get currency model via ID
     *
     * @param int $id
     * @return Api_Model_Currency|null
     */
    public function findById($id)
    {
        return $this->_repository->findById($id);
    }

    /**
     * Get currency model via ISO code
     *
     * @param string $iso
     * @return Api_Model_Currency|null
     */
    public function findByIso($iso)
    {
        $dto = new Api_Dto_Currency();
        $dto->setIso($iso);

        $currencies = $this->findByDto($dto, 1, 0);

        if (!$currencies) {
            return null;
        }

        return reset($currencies);
    }

    /**
     * Update currency model using data from DTO
     *
     * @param Api_Model_Currency $currency
     * @param Api_Dto_Currency $dto
     * @return Api_Model_Currency
     * @throws Api_Exception_AlreadyExists
     */
    public function update(Api_Model_Currency $currency, Api_Dto_Currency $dto)
    {
        $currency->setTitle($dto->title);

        if ($dto->iso && $dto->iso != $currency->getIso()) {
            if ($this->findByIso($dto->iso)) {
                throw (new Api_Exception_AlreadyExists())->setIso($dto->iso);
            }

            $currency->setIso($dto->iso);
            $currency->setRate(null);
        }

        $this->_repository->persist($currency);
        $this->updateRate($currency);

        return $currency;
    }

    /**
     * Delete currency model
     *
     * @param Api_Model_Currency $currency
     * @return bool
     */
    public function delete(Api_Model_Currency $currency)
    {
        return $this->_repository->delete($currency);
    }

    /**
     * Find models, which have no date of last update or expired (last updated less than 24 hours ago)
     *
     * @return Api_Model_Currency[]
     */
    public function findExpired()
    {
        $result = [];
        $currencies = $this->_repository->findAll();

        foreach ($currencies as $currency) {
            if (!$currency->getLastUpdatedAt() || $currency->isExpired()) {
                $result[] = $currency;
            }
        }

        return $result;
    }

    /**
     * Update currencies rate from the source
     *
     * @param Api_Model_Currency $currency
     * @return Api_Model_Currency
     */
    public function updateRate(Api_Model_Currency $currency)
    {
        $rate = $this->_parser->getRate($currency->getIso(), $this->_sourceIso);

        $currency->setRate($rate);
        $currency->setLastUpdatedAt(new \DateTime());

        $this->_repository->persist($currency);

        return $currency;
    }
}
