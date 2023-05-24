<?php

class Request
{

    private $url;
    private $method;
    private $params;
    private $result;
    private $error;
    private $info;

    function __construct($request_data)
    {
        $this->url = $request_data['url'];
        $this->method = $request_data['method'];

        if (count($request_data['request_params']) > 0) {
            foreach ($request_data['request_params'] as $request_params) {
                $this->params[$request_params['name']] = $request_params['params'];
            }
        }
    }

    public function curlRequest(): void
    {

        $parse_url = parse_url($this->url);
        $scheme = $parse_url['scheme'] ?: 'https';
        $fragment = $parse_url['fragment'] ? '#' . $parse_url['fragment'] : '';
        $query = $parse_url['query'] ? '?' . $parse_url['query'] : '';

        if (isset($this->params['CURLOPT_URL']) && count($this->params['CURLOPT_URL']) > 0) {
            $query_add = http_build_query($this->params['CURLOPT_URL']);

            if ($query == '') {
                $query = '?' . $query_add;
            } else {
                $query .= '&' . $query_add;
            }
        }

        $this->url = $scheme . '://' . $parse_url['host'] . $parse_url['path'] . $query . $fragment;

        $curlopt_httpheader = [];
        if (isset($this->params['CURLOPT_HTTPHEADER']) && count($this->params['CURLOPT_HTTPHEADER']) > 0) {
            foreach ($this->params['CURLOPT_HTTPHEADER'] as $header_data_key => $header_data_value) {
                $curlopt_httpheader[] = "{$header_data_key}:{$header_data_value}";
            }
        }

        $curlopt_postfields = "";
        if (isset($this->params['CURLOPT_POSTFIELDS']) && count($this->params['CURLOPT_POSTFIELDS']) > 0) {
            $curlopt_postfields = http_build_query($this->params['CURLOPT_POSTFIELDS']);
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => getenv("SSL_VERIFICATION"),
            CURLOPT_SSL_VERIFYPEER => getenv("SSL_VERIFICATION"),
            CURLOPT_CUSTOMREQUEST => $this->method,
            CURLOPT_POSTFIELDS => $curlopt_postfields,
            CURLOPT_HTTPHEADER => $curlopt_httpheader,
        ]);

        $this->result = curl_exec($curl);
        $this->info = curl_getinfo($curl);
        if (curl_errno($curl)) {
            $this->error = curl_error($curl);
        }
        curl_close($curl);
    }

    public function getData(): array
    {
        return [
            'url' => $this->url,
            'result' => $this->result,
            'error' => $this->error,
            'info' => $this->info
        ];
    }
}
