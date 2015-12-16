<?php

class Api_Model_TableGateway_Currency extends Zend_Db_Table_Abstract
{
    /**
     * @inheritdoc
     */
    protected $_name = 'currencies';

    /**
     * @inheritdoc
     */
    protected $_primary = 'id';
}