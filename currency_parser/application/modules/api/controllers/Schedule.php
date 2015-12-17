<?php

class Api_Controller_Schedule extends Zend_Controller_Action
{
    /**
     * @var Api_Service_Currency
     */
    private $_service;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $gateway = new Api_Model_TableGateway_Currency(['db' => Zend_Db_Table::getDefaultAdapter()]);
        $this->_service = new Api_Service_Currency($gateway);
    }

    public function indexAction()
    {
        $currencies = $this->_service->findExpired();

        foreach ($currencies as $currency) {
            $this->_service->updateRate($currency);
        }
    }
}
