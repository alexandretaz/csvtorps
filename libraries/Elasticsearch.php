<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

// http://www.elasticsearch.com/docs/elasticsearch/rest_api/

class Elasticsearch {

    public $index;
    private $server;

    function __construct($server = ELASTICSEARCH_SERVER) {
        $this->index = 'salaovip';
        $this->server = $server;
    }

	private function call($path, $http = array()){
	  if (!$this->index) throw new Exception('$this->index needs a value');

        $username = 'svip';
        $password = 'svip2015';

        $http['header'] = "Connection: close\r\n" .
                "Content-type: application/x-www-form-urlencoded\r\n" .
                "Authorization: Basic " . base64_encode("$username:$password");


        $context = stream_context_create(array('http' => $http));

        return json_decode(
                file_get_contents(
                    $this->server . $this->index . $path, 
                    NULL, 
                    stream_context_create(array('http' => $http))
                )
        );
    }

    //curl -X PUT http://localhost:9200/{INDEX}/
    public function create() {
        $dados = $this->call(NULL, array('method' => 'PUT'));
    }

    //curl -X DELETE http://localhost:9200/{INDEX}/
    public function drop($type) {
        $this->call($type, array('method' => 'DELETE'));
    }

    //curl -X GET http://localhost:9200/{INDEX}/_status
    public function status() {
        return $this->call('_status');
    }

    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_count -d {matchAll:{}}
    public function count($type) {
        return $this->call($type . '/_count', array('method' => 'GET', 'content' => '{ matchAll:{} }'));
    }

    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/_mapping -d ...
    public function map($type, $data) {
        return $this->call($type . '/_mapping', array('method' => 'PUT', 'content' => $data, 'header' => 'Content-Type: application/x-www-form-urlencoded\r\n'));
    }

    //curl -X PUT http://localhost:9200/{INDEX}/{TYPE}/{ID} -d ...
    public function add($type, $id, $data) {
        return $this->call($type . '/' . $id, array('method' => 'PUT', 'content' => $data, 'header' => 'Content-Type: application/x-www-form-urlencoded\r\n'));
    }

    //curl -X GET http://localhost:9200/{INDEX}/{TYPE}/_search?q= ...
    public function query($type, $q) {
        return $this->call($type . '/_search?' . http_build_query(array('q' => $q)));
    }

    public function query_post($type, $data) {
        return $this->call($type . '/_search', array('method' => 'POST', 'content' => $data, 'header' => 'Content-Type: application/x-www-form-urlencoded\r\n'));
    }

}
