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

    public function isInRangeWithBegin($date, $begin, $end)
    {
        if ($date >= $begin and $date < $end) {
            return true;
        } else {
            return false;
        }
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
            if (($data["type"] === $type) and
                ($this->isInRangeWithBegin($begin, $data["begin"], $data["end"]))
            ) {
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

    /**
     * return nearest end for given begin
     * @param string $begin
     * @return string
     */
    public function getCurrentFullEnd($begin)
    {
        $end = $this->getLastEnd();
        foreach ($this->getTypes() as $type) {
            $periodRow = $this->getPeriodRowInType($begin, $type);
            if (!is_null($periodRow) and $end > $periodRow["end"]) {
                $end = $periodRow["end"];
            }
        }
        return $end;
    }


    public function getPeriods()
    {
        $periods = array();
        $begin = $this->getFirstBegin();
        for($i=10; $i<100; $i++) {
            $fullEnd = $this->getCurrentFullEnd($begin);
            /**
             * @todo определили максимальный отрезок $begin - $end, теперь необходимо проверить есть ли начало более высокого отрезка на этом если есть - сделать его начало - концом
             *
             */

            /*$periods[] = array("begin" => $begin, "end"=>$end);
            $begin = $end;
            if ($end === $this->getLastEnd()) {
                break;
                //echo ("i:= $i \n");
            }*/
        }
        return $periods;
    }

}