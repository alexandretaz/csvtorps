<?php

/**
 * Class LotFile
 * 
 */


require_once __DIR__.'/Rps.php';
class LotFile  implements ArrayAccess, Iterator{


    const instruction = 1;

    private $fileVersion = 1;
    private $container = array();
    private $nextNumber = 1;
    private $data;
    private $startDate;
    private $endDate;
    private $total =0;
    private $totalImpostos;
    private $deducao = 0;

    public function add(RPS $item)
    {
        $this->container[] = $item;
        $this->total+=$item->getTotalValue();
        return $this;
    }

    public function offsetExists($offset)
    {
        $answer = false;
        if(isset($this->container[$offset])) {
            $answer = true;
        }
        return $answer;
    }

    public function offsetGet($offset)
    {
        if($this->offsetExists($offset)) {
            return $this->container[$offset];
        }
        return null;
    }

    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
        return $this;
    }

    public function offsetUnset($offset)
    {
        return $this->container[$offset];
    }

    public function current()
    {
        return current($this->container);
    }

    public function next()
    {
        return next($this->container);
    }

    public function key()
    {
        return key($this->container);
    }

    public function valid()
    {
        return true;
    }

    public function rewind()
    {
        reset($this->container);
    }

    public function generate( array $comandas, $dados, $dadosEnvio = array()) {
        $this->nextNumber = (int) $dados['nota']->prox_rps;
        $this->data = $dados;
        $aliquota = (float) $dadosEnvio['aliquota']/100;
        foreach ($comandas as $comanda) {
            $dateComanda = \DateTime::createFromFormat('Ymd', $comanda->data_emissao);
            if(empty($this->startDate) || $dateComanda < $this->startDate) {
                $this->startDate = $dateComanda;
            }
            if(empty($this->endDate) || $dateComanda>$this->endDate) {
                $this->endDate = $dateComanda;
            }

            $rps = new Rps($comanda, $this->nextNumber, $dadosEnvio, $dados); 
            $this->add($rps);
        }
        $this->totalImpostos = $this->total * $aliquota;

    }

    public function getNextNumber() {
        return $this->nextNumber;
    }

    public function __toString() {
        $strLotFile = str_pad(self::instruction, 1) . str_pad($this->fileVersion, 3, 0, STR_PAD_LEFT) . 
        str_pad($this->data['salao']->inscricao_municipal, 8) . str_pad($this->startDate->format('Ymd'), 8, 0).
        str_pad($this->endDate->format('Ymd'), 8, 0) .PHP_EOL;
        foreach ($this->container as $rps) {
            $strLotFile .= $rps->__toString();
        }
        $strLotFile .= 9;
        $strLotFile .= str_pad(count($this->container), 7, 0, STR_PAD_LEFT) . str_pad(($this->total*100), 15, 0, STR_PAD_LEFT) . 
        str_pad(str_replace('.', '',0), 15, 0, STR_PAD_LEFT);

        return $strLotFile;
    }
 

}
