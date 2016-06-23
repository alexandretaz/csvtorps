<?php

/**
 * Created by PhpStorm.
 * User: alexandre
 * Date: 02/05/16
 * Time: 15:18
 */
class Rps
{
    private $itens;
    private $registerKind = 2;
    private $kind = "RPS";
    private $serie = "slvip";
    private $number = "0";
    private $creationDate;
    private $situation = "T";
    private $totalValue = 0;
    private $deductionsvalue = 0;
    private $serviceCode = 0;
    private $percentage = 0.00;
    private $issPayed = 2;
    private $fiscalDocumentType = 3;
    private $fiscalDocumentNumber = null;
    private $municipalDocument = null;
    private $stateDocument = null;
    private $name = null;
    private $addressKind = null;
    private $address = null;
    private $addressNumber = null;
    private $addressComplement =  null;
    private $region = null;
    private $city = null;
    private $state = null;
    private $postalCode = null;
    private $email = null;
    private $servicesDetails = "";

    public function __construct($comanda, &$numberToAssign, $dadosEnvio, $dados) {
        $this->setItens($comanda->servicos)
            ->setNumber($numberToAssign++)
            ->setCreationDate(\DateTime::createFromFormat('Ymd', $comanda->data_emissao))
            ->setTotalValue($comanda->total)
            ->setServiceCode(str_ireplace('.', '', $dadosEnvio["item_lista_servico"]))
            ->setPercentage($dadosEnvio["aliquota"])
            ->setServicesDetails($comanda->servicos);
            if( isset($comanda->cnpj) && !empty($comanda->cnpj)) {
                if(strlen($comanda->cnpj)>11) {
                    $this->setFiscalDocumentType(2);
                    $this->setFiscalDocumentNumber($comanda->cnpj);
                }
                elseif( strlen($comanda->cnpj)>=10 && strlen($comanda->cnpj)<=11) {
                    $this->setFiscalDocumentType(1);
                    $this->setFiscalDocumentNumber($comanda->cnpj);
                }
                else{
                    $this->setFiscalDocumentType(3);
                }
                
            }
            if(isset($comanda->tipoEndereco) && !empty($comanda->tipoEndereco)) {
                $this->setAddressKind($comanda->tipoEndereco);
            }
            if(isset($comanda->endereco) && !empty($comanda->endereco)) {
                $this->setAddress($comanda->endereco);
            }
            if(isset($comanda->enderecoNumero) && !empty($comanda->enderecoNumero)) {
                $this->setAddressNumber($comanda->enderecoNumero);
            }
            if(isset($comanda->enderecoComplemento) && !empty($comanda->enderecoComplemento)) {
                $this->setAddressComplement($comanda->enderecoComplemento);
            }
            if(isset($comanda->enderecoBairro) && !empty($comanda->enderecoBairro)) {
                $this->setRegion($comanda->enderecoBairro);
            }
            if(isset($comanda->enderecoCidade) && !empty($comanda->enderecoCidade)) {
                $this->setCity($comanda->enderecoCidade);
            }
            if(isset($comanda->enderecoUF) && !empty($comanda->enderecoUF)) {
                $this->setState($comanda->enderecoUF);
            }
            if(isset($comanda->enderecoCEP) && !empty($comanda->enderecoCEP)) {
                $this->setPostalCode($comanda->enderecoCEP);
            }
            if(isset($comanda->enderecoEmail) && !empty($comanda->enderecoEmail)) {
                $this->setEmail($comanda->enderecoEmail);
            }
    }

    /**
     * @return array
     */
    public function getItens()
    {
        return $this->itens;
    }

    /**
     * @param array $itens
     */
    public function setItens($itens)
    {
        $this->itens = $itens;
        return $this;
    }

    /**
     * @return int
     */
    public function getRegisterKind()
    {
        return $this->registerKind;
    }

    /**
     * @param int $registerKind
     * @return RPS
     */
    public function setRegisterKind($registerKind)
    {
        $this->registerKind = $registerKind;
        return $this;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     * @return RPS
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
        return $this;
    }

    /**
     * @return string
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * @param string $serie
     * @return RPS
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return RPS
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param mixed $creationDate
     * @return RPS
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * @param string $situation
     * @return RPS
     */
    public function setSituation($situation)
    {
        $this->situation = $situation;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalValue()
    {
        return $this->totalValue;
    }

    /**
     * @param int $totalValue
     * @return RPS
     */
    public function setTotalValue($totalValue)
    {
        $this->totalValue = $totalValue;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeductionsvalue()
    {
        return $this->deductionsvalue;
    }

    /**
     * @param int $deductionsvalue
     * @return RPS
     */
    public function setDeductionsvalue($deductionsvalue)
    {
        $this->deductionsvalue = $deductionsvalue;
        return $this;
    }

    /**
     * @return int
     */
    public function getServiceCode()
    {
        return $this->serviceCode;
    }

    /**
     * @param int $serviceCode
     * @return RPS
     */
    public function setServiceCode($serviceCode)
    {
        $this->serviceCode = $serviceCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param float $percentage
     * @return RPS
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        return $this;
    }

    /**
     * @return int
     */
    public function getIssPayed()
    {
        return $this->issPayed;
    }

    /**
     * @param int $issPayed
     * @return RPS
     */
    public function setIssPayed($issPayed)
    {
        $this->issPayed = $issPayed;
        return $this;
    }

    /**
     * @return int
     */
    public function getFiscalDocumentType()
    {
        return $this->fiscalDocumentType;
    }

    /**
     * @param int $fiscalDocumentType
     * @return RPS
     */
    public function setFiscalDocumentType($fiscalDocumentType)
    {
        $this->fiscalDocumentType = $fiscalDocumentType;
        return $this;
    }

    /**
     * @return int
     */
    public function getFiscalDocumentNumber()
    {
        return $this->fiscalDocumentNumber;
    }

    /**
     * @param int $fiscalDocumentNumber
     * @return RPS
     */
    public function setFiscalDocumentNumber($fiscalDocumentNumber)
    {
        $this->fiscalDocumentNumber = $fiscalDocumentNumber;
        return $this;
    }

    /**
     * @return null
     */
    public function getMunicipalDocument()
    {
        return $this->municipalDocument;
    }

    /**
     * @param null $municipalDocument
     * @return RPS
     */
    public function setMunicipalDocument($municipalDocument)
    {
        $this->municipalDocument = $municipalDocument;
        return $this;
    }

    /**
     * @return null
     */
    public function getStateDocument()
    {
        return $this->stateDocument;
    }

    /**
     * @param null $stateDocument
     * @return RPS
     */
    public function setStateDocument($stateDocument)
    {
        $this->stateDocument = $stateDocument;
        return $this;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     * @return RPS
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return null
     */
    public function getAddressKind()
    {
        return $this->addressKind;
    }

    /**
     * @param null $addressKind
     * @return RPS
     */
    public function setAddressKind($addressKind)
    {
        $this->addressKind = $addressKind;
        return $this;
    }

    /**
     * @return null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param null $address
     * @return RPS
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return null
     */
    public function getAddressNumber()
    {
        return $this->addressNumber;
    }

    /**
     * @param null $addressNumber
     * @return RPS
     */
    public function setAddressNumber($addressNumber)
    {
        $this->addressNumber = $addressNumber;
        return $this;
    }

    /**
     * @return null
     */
    public function getAddressComplement()
    {
        return $this->addressComplement;
    }

    /**
     * @param null $addressComplement
     * @return RPS
     */
    public function setAddressComplement($addressComplement)
    {
        $this->addressComplement = $addressComplement;
        return $this;
    }

    /**
     * @return null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param null $region
     * @return RPS
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return RPS
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return RPS
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return null
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * @param null $postalCode
     * @return RPS
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null $email
     * @return RPS
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getServicesDetails()
    {
        return $this->servicesDetails;
    }

    /**
     * @param string $servicesDetails
     * @return RPS
     */
    public function setServicesDetails($servicesDetails)
    {
        $this->servicesDetails = $servicesDetails;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $baseString = $this->getRegisterKind() . str_pad($this->getKind(), 5, " ") .
        str_pad($this->getSerie(), 5, " ") .  str_pad($this->getNumber(), 12, "0", STR_PAD_LEFT).
        str_pad($this->getCreationDate()->format('Ymd'), 8, 0) . $this->getSituation().
        str_pad($this->getTotalValue()*100, 15, 0, STR_PAD_LEFT) . str_pad($this->getDeductionsvalue()*100, 15, 0, STR_PAD_LEFT).
        str_pad($this->getServiceCode(), 5, 0, STR_PAD_LEFT) . str_pad($this->getPercentage()*100, 4, 0, STR_PAD_LEFT).
        $this->getIssPayed() . $this->getFiscalDocumentType() . str_pad($this->getFiscalDocumentNumber() , 14, 0, STR_PAD_LEFT) . str_pad($this->getMunicipalDocument(), 8, " ").
        str_pad($this->getStateDocument(), 12, " ") . str_pad($this->getName(), 75, " ") . 
        str_pad($this->getAddressKind(), 3, " ") . str_pad($this->getAddress(), 50, " ") .
        str_pad($this->getAddressNumber(), 10, " ") . str_pad($this->getAddressComplement(), 30, " ") . 
        str_pad($this->getRegion(), 30, " ") . str_pad($this->getCity(), 50, " ") .
        str_pad($this->getState(), 2, " ") . str_pad($this->getPostalCode(), 8, 0, STR_PAD_LEFT) .  
        str_pad($this->getEmail(), 75, " ") . str_pad($this->getServicesDetails(), 1000, " ").PHP_EOL;

        return $baseString;
    }

}
