<?php

class PeriodMaker
{
    protected $_inData;


    public function load($data)
    {
        $this->_inData = $data;
    }

    public function __set($name, $value)
    {
        $method = "set" . ucfirst($name);
        if (!method_exists($this, $method)) {
            throw new UnexpectedValueException("Invalid Entry set class property: \"$name\"");
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = "get" . ucfirst($name);
        if (!method_exists($this, $method)) {
            throw new UnexpectedValueException("Invalid Entry get class property: \"$name\"");
        }
        return $this->$method();
    }

    public function getInData()
    {
        return $this->_inData;
    }

    public function getTypes()
    {
        $types = array();
        foreach ($this->_inData as $data) {
            if (!in_array($data["type"], $types)) {
                $types[] = $data["type"];
            }
        }
        asort($types);
        $types = array_slice($types, 0);
        return $types;
    }

    public function getFirstBegin()
    {
        $newArray = array();
        foreach ($this->_inData as $item) {
            $newArray[] = $item["begin"];
        }
        asort($newArray);
        return reset($newArray);
    }

    public function getLastEnd()
    {
        $newArray = array();
        foreach ($this->_inData as $item) {
            $newArray[] = $item["end"];
        }
        arsort($newArray);
        return reset($newArray);
    }

    public function getPoints($type)
    {
        $points = array();
        foreach ($this->_inData as $data) {
            if ($data["type"] == $type) {
                $points[] = array("id" => $data["id"], "date" => $data["begin"]);
                $points[] = array("id" => $data["id"], "date" => $data["end"]);
            }
        }
        return $points;
    }

    public function getPeriodRowInType($begin, $type)
    {
        $period = null;
        foreach ($this->_inData as $data) {
            if (($data["type"] === $type) and ($data["begin"] === $begin)) {
                if (is_null($period)) {
                    $period = $data;
                } else {
                    if ($period["id"] > $data["id"]) {
                        $period = $data;
                    }
                }
            }
        }
        return $period;
    }

    public function getPeriod($begin)
    {
        $end = $this->getLastEnd();
        foreach ($this->getTypes() as $type) {
            $periodRow = $this->getPeriodRowInType($begin, $type);
            if ($end > $periodRow["end"]) {
                $end = $periodRow["end"];
            }
        }
        return $end;
    }

    public function getCurrentEnd($begin, $type)
    {
        $points = $this->getPoints($type);
        //найти рассматриваемый период
        $end["date"] = $begin;
        $end["id"] = 10;
        foreach($points as $point) {
            //echo ($point["date"]."<".$end["date"] .":". $point["id"]." < ".$end["id"]."\n");
            if ($point["date"] < $end["date"] or $point["id"] < $end["id"]){
                $end["date"] = $point["date"];
                $end["id"] = $point["id"];
                //var_dump($end);
                //break;
            }
        }
        return $end["date"];
    }

}