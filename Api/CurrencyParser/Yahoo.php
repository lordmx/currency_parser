<?php

class Api_CurrencyParser_Yahoo implements Api_CurrencyParser_Interface
{
    /**
     * @inheritdoc
     * @throws Api_Exception_ParserError
     */
    public function getRate($targetIso, $sourceIso)
    {
        try {
            $data = json_decode(file_get_contents($this->_getApiUrl($targetIso, $sourceIso)), true);
        } catch (\Exception $e) {
            $data = null;
        }

        if (!$data) {
            throw new Api_Exception_ParserError('No API request');
        }

        if (json_last_error()) {
            throw new Api_Exception_ParserError(json_last_error_msg());
        }

        if (!isset($data['query'])) {
            throw new Api_Exception_ParserError('Wrong APIs request: no body');
        }

        $data = $data['query'];

        if (!isset($data['results']) || empty($data['results']['rate'])) {
            return null;
        }

        $rate = reset($data['results']['rate']);

        if (!isset($rate['Rate'])) {
            throw new Api_Exception_ParserError('Wrong APIs request: wrong structure, no rate');
        }

        return (float) $rate['Rate'];
    }

    /**
     * @param string $targetIso
     * @param string $sourceIso
     * @return string
     */
    private function _getApiUrl($targetIso, $sourceIso)
    {
        return
            'https://query.yahooapis.com/v1/public/yql?q=select+*+from+yahoo.finance.xchange+where+pair+=+%22' .
            strtolower($targetIso . $sourceIso) .
            '%22&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';
    }
}
