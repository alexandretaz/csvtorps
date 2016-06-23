<?php

/*
 * o web service retorna o estado SEM ACENTO, então usamos ele como chave de array 
 * para gerar UF e nome do estado corretamente.
 * para mais informações acesse: http://www.ipinfodb.com/ip_location_api_json.php
 */


class Ipinfodb {
    
    private $url = "https://geoip.maxmind.com/geoip/v2.1/city/"; //?pretty
    //private $key = "68b3e6993155366165c7be666922e211ed38865095d9d326a29e156af262037e";
    private $userID = "98967";
    private $licensekey = "HU8e1ywLcbhZ";
    private $ipTeste = "186.201.26.43";
    private $ip;
    
    public $statusCode;
    public $statusMessage;
    public $ipAddress;
    public $countryCode;
    public $countryName;
    public $regionName = "Sao Paulo";
    public $regionUF = "SP";
    public $cityName;
    public $zipCode;
    public $latitude;
    public $longitude;
    public $timeZone;
    
    /*
     * Esta função não tem mais serventia neste novo web service atual
     */
    public function getRegionNameANTIGONAOUSADO() {
        
        $region = array();
        $region['Acre'] = "Acre";
        $region['Alagoas'] = "Alagoas";
        $region['Amapa'] = "Amapá";
        $region['Amazonas'] = "Amazonas";
        $region['Bahia'] = "Bahia";
        $region['Ceara'] = "Ceará";
        $region['Distrito Federal'] = "Distrito Federal";
        $region['Espirito Santo'] = "Espírito Santo";
        $region['Goias'] = "Goiás";
        $region['Maranhao'] = "Maranhão";
        $region['Mato Grosso'] = "Mato Grosso";
        $region['Mato Grosso do Sul'] = "Mato Grosso do Sul";
        $region['Minas Gerais'] = "Minas Gerais";
        $region['Para'] = "Pará";
        $region['Paraiba'] = "Paraíba";
        $region['Parana'] = "Paraná";
        $region['Pernambuco'] = "Pernambuco";
        $region['Piaui'] = "Piauí";
        $region['Rio Grande do Norte'] = "Rio Grande do Norte";
        $region['Rio Grande do Sul'] = "Rio Grande do Sul";
        $region['Rio de Janeiro'] = "Rio de Janeiro";
        $region['Rondonia'] = "Rondônia";
        $region['Roraima'] = "Roraima";
        $region['Santa Catarina'] = "Santa Catarina";
        $region['Sao Paulo'] = "São Paulo";
        $region['Sergipe'] = "Sergipe";
        $region['Tocantins'] = "Tocantins";
        
        //return $region['Parana'];
        return $region[$this->regionName];
    }
    
    public function getRegionName() {
        
        $region = array();
        $region['AC'] = "Acre";
        $region['AL'] = "Alagoas";
        $region['AP'] = "Amapá";
        $region['AM'] = "Amazonas";
        $region['BA'] = "Bahia";
        $region['CE'] = "Ceará";
        $region['DF'] = "Distrito Federal";
        $region['ES'] = "Espírito Santo";
        $region['GO'] = "Goiás";
        $region['MA'] = "Maranhão";
        $region['MT'] = "Mato Grosso";
        $region['MS'] = "Mato Grosso do Sul";
        $region['MG'] = "Minas Gerais";
        $region['PA'] = "Pará";
        $region['PB'] = "Paraíba";
        $region['PR'] = "Paraná";
        $region['PE'] = "Pernambuco";
        $region['PI'] = "Piauí";
        $region['RN'] = "Rio Grande do Norte";
        $region['RS'] = "Rio Grande do Sul";
        $region['RJ'] = "Rio de Janeiro";
        $region['RO'] = "Rondônia";
        $region['RR'] = "Roraima";
        $region['SC'] = "Santa Catarina";
        $region['SP'] = "São Paulo";
        $region['SE'] = "Sergipe";
        $region['TO'] = "Tocantins";
        
        //return $region['Parana'];
        return $region[$this->regionUF];
    }
    
    /*
     * Esta função não tem mais serventia neste novo web service atual
     */
    public function getRegionUFANTIGONAOUSADO() {
        
        $region = array();
        $region['Acre'] = "AC";
        $region['Alagoas'] = "AL";
        $region['Amapa'] = "AP";
        $region['Amazonas'] = "AM";
        $region['Bahia'] = "BA";
        $region['Ceara'] = "CE";
        $region['Distrito Federal'] = "DF";
        $region['Espirito Santo'] = "ES";
        $region['Goias'] = "GO";
        $region['Maranhao'] = "MA";
        $region['Mato Grosso'] = "MT";
        $region['Mato Grosso do Sul'] = "MS";
        $region['Minas Gerais'] = "MG";
        $region['Para'] = "PA";
        $region['Paraiba'] = "PB";
        $region['Parana'] = "PR";
        $region['Pernambuco'] = "PE";
        $region['Piaui'] = "PI";
        $region['Rio Grande do Norte'] = "RN";
        $region['Rio Grande do Sul'] = "RS";
        $region['Rio de Janeiro'] = "RJ";
        $region['Rondonia'] = "RO";
        $region['Roraima'] = "RR";
        $region['Santa Catarina'] = "SC";
        $region['Sao Paulo'] = "SP";
        $region['Sergipe'] = "SE";
        $region['Tocantins'] = "TO";
        
        //return $region['Parana'];
        return $region[$this->regionName];
    }
    
    public function atualizar() {
        
        
        // qual a diferença de $_server para getenv? getenv retorna false no lugar de undefined.
        $this->ip = getenv('HTTP_CLIENT_IP')?:
                    getenv('HTTP_X_FORWARDED_FOR')?:
                    getenv('HTTP_X_FORWARDED')?:
                    getenv('HTTP_FORWARDED_FOR')?:
                    getenv('HTTP_FORWARDED')?:
                    getenv('REMOTE_ADDR');

        if(!$this->ip || $this->ip == "127.0.0.1") { 
            $this->ip = $this->ipTeste; 
        }

        $resultados = new \stdClass();

        if ( function_exists('curl_init') ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url . $this->ip);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERPWD, "$this->userID:$this->licensekey");
            $output = curl_exec($ch);
            
            /*
             * APENAS PARA DEBUG!! DISPARA O ERRO DO WEB SERVICE CASO OCORRA
            if(curl_errno($ch))
                echo 'error:' . curl_error($ch);
            */
            
            curl_close($ch);
            
            $resultados = !$output ? null : json_decode($output);

        } else if ( ini_get('allow_url_fopen') ) {
            $output = file_get_contents($this->url . $getParam, 'r');
            $resultados = json_decode($output);
        } 
        
        if(!is_null($resultados)) {
            if(isset($resultados->subdivisions) && isset($resultados->subdivisions[0])) {
                if(isset($resultados->subdivisions[0]->iso_code)){
                    $this->regionUF = $resultados->subdivisions[0]->iso_code;
                }
                
                if(isset($resultados->subdivisions[0]->names)){
                    if(isset($resultados->subdivisions[0]->names->en)){
                        $this->regionName = $resultados->subdivisions[0]->names->en;
                    }
                }
            }
        }
        
    }


    public function __construct() {
        
        
        $CITemp =& get_instance();
        $sess_regiao = $CITemp->session->userdata('sess_regiao');
        
        if(!$sess_regiao) {
            
            if ($sess_regiao['sigla']==NULL) {
                
                $this->atualizar();
                
                $uf = $this->regionUF;
                alterarEstado($uf);
            } 
        }
        
    }
    
}
