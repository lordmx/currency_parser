<?php

class Api_Repository_Currency implements Api_Repository_Interface
{
    /**
     * @var Api_Model_TableGateway_Currency
     */
    protected $_gateway;

    /**
     * @param Api_Model_TableGateway_Currency $gateway
     */
    public function __construct(Api_Model_TableGateway_Currency $gateway)
    {
        $this->_gateway = $gateway;
    }

    /**
     * @inheritdoc
     */
    public function findById($id)
    {
        $rows = $this->_gateway->find($id);

        if (!$rows->count()) {
            return null;
        }

        return $this->_createModel(reset($rows));
    }

    /**
     * @inheritdoc
     * @param Api_Dto_Currency $dto
     */
    public function findByDto($dto, $limit = 10, $offset = 0)
    {
        $query = $this->_populateQuery($dto)
            ->limit($limit, $offset)
            ->order('id ASC');

        $rows = $this->_gateway->fetchAll($query);

        if (!$rows->count()) {
            return [];
        }

        $result = [];

        foreach ($rows as $row) {
            $result[] = $this->_createModel($row->toArray());
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function findAll()
    {
        $rows = $this->_gateway->fetchAll();

        if (!$rows->count()) {
            return [];
        }

        foreach ($rows as $row) {
            $result[] = $this->_createModel($row->toArray());
        }

        return $result;
    }

    /**
     * @inheritdoc
     * @param Api_Dto_Currency $dto
     */
    public function countByDto($dto)
    {
        $row = $this->_populateQuery($dto)
            ->from('currencies', [new Zend_Db_Expr('COUNT(*)')])
            ->query()
            ->fetch();

        if (!$row) {
            return 0;
        }

        return (int)reset($row);
    }

    /**
     * @inheritdoc
     * @param Api_Model_Currency $model
     */
    public function delete($model)
    {
        $where = $this->_gateway->getAdapter()->quoteInto('id = ?', (int)$model->getId());

        return (bool) $this->_gateway->delete($where);
    }

    /**
     * @inheritdoc
     * @param Api_Model_Currency $model
     */
    public function persist($model)
    {
        $data = [
            'iso'   => $model->getIso(),
            'title' => $model->getTitle(),
            'rate'  => $model->getRate()
        ];

        if ($model->getId()) {
            $data['last_updated_at'] = $model->getLastUpdatedAt()->format(DATE_W3C);
            $criteria = ['id = ?' => (int) $model->getId()];
            $this->_gateway->update($data, $criteria);
        } else {
            $this->_gateway->insert($data);
            $model->setId($this->_gateway->lastInsertValue);
        }

        return $model;
    }

    /**
     * @param Api_Dto_Currency $dto
     * @return Zend_Db_Table_Select
     */
    private function _populateQuery(Api_Dto_Currency $dto)
    {
        $query = $this->_gateway->select();

        if ($dto->iso) {
            $query->where('iso = ?', $dto->iso);
        }

        if ($dto->title) {
            $query->where('title LIKE ?', $dto->title . '%');
        }

        return $query;
    }

    /**
     * @param array $data
     * @return Api_Model_Currency
     * @throws Api_Exception_NoArgument
     */
    private function _createModel(array $data = [])
    {
        if (empty($data['id'])) {
            throw (new Api_Exception_NoArgument())->setField('id');
        }

        $currency = new Api_Model_Currency(
            isset($data['iso']) ? $data['iso'] : null,
            isset($data['title']) ? $data['title'] : null,
            isset($data['rate']) ? $data['rate'] : null
        );

        if (isset($data['last_updated_at']) && $data['last_updated_at'] != '0000-00-00 00:00:00') {
            $currency->setLastUpdatedAt(new \DateTime($data['last_updated_at']));
        }

        if (isset($data['id'])) {
            $currency->setId((int)$data['id']);
        }

        return $currency;
    }
}
