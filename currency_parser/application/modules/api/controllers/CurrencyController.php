<?php

class Api_CurrencyController extends Api_Controller_Base
{
    /**
     * @var Api_Service_Currency
     */
    private $_service;

    /**
     * @var string
     */
    protected $_resourceName = 'currencies';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $gateway = new Api_Model_TableGateway_Currency(['db' => Zend_Db_Table::getDefaultAdapter()]);
        $repository = new Api_Repository_Currency($gateway);
        $parser = new Api_CurrencyParser_Yahoo();

        $this->_service = new Api_Service_Currency($repository, $parser);
    }

    /**
     * @param Api_Dto_Currency $dto
     * @return Api_Controller_Response_Result
     */
    public function indexAction(Api_Dto_Currency $dto)
    {
        $currencies = $this->_service->findByDto($dto, $this->getLimit(), $this->_request->getOffset());
        $meta = $this->_createMeta($this->_service->countByDto($dto));

        return $this->_createResult($currencies, $meta);
    }

    /**
     * @return Api_Controller_Response_Result
     * @throws Api_Exception_NotFound
     */
    public function getAction()
    {
        $currency = $this->_service->findById($this->_request->getId());

        if (!$currency) {
            throw new Api_Exception_NotFound();
        }

        return $this->_createResult([$currency], $this->_createMeta(1));
    }

    /**
     * @return Api_Controller_Response_Result
     * @throws Api_Exception_NotFound
     */
    public function deleteAction()
    {
        $currency = $this->_service->findById($this->_request->getId());

        if (!$currency) {
            throw new Api_Exception_NotFound();
        }

        $this->_service->delete($currency);

        return $this->_createResult([$currency], null, Api_Controller_Response_Result::NO_CONTENT);
    }

    /**
     * @param Api_Dto_Currency $dto
     * @return Api_Controller_Response_Result
     * @throws Api_Exception_NotFound
     */
    public function putAction(Api_Dto_Currency $dto)
    {
        $currency = $this->_service->findById($this->_request->getId());

        if (!$currency) {
            throw new Api_Exception_NotFound();
        }

        $currency = $this->_service->update($currency, $dto);

        return $this->_createResult([$currency], $this->_createMeta(1));
    }

    /**
     * @param Api_Dto_Currency $dto
     * @return Api_Controller_Response_Result
     */
    public function postAction(Api_Dto_Currency $dto)
    {
        $currency = $this->_service->create($dto);
        $meta = $this->_createMeta(1);
        $meta->addParam('rate', $currency->getRate());

        return $this
            ->_createResult([['id' => $currency->getId()]], $meta)
                ->setLocation($this->_getUrl() . $currency->getId());
    }
}
