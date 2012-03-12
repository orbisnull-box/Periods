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

    public static function isInRange($date, $begin, $end)
    {
        if ($date > $begin and $date < $end) {
            return true;
        } else {
            return false;
        }
    }

    public static function isInRangeWithBegin($date, $begin, $end)
    {
        if ($date >= $begin and $date < $end) {
            return true;
        } else {
            return false;
        }
    }

    public static function isRangeInRange($firstBegin, $firstEnd, $secondBegin, $secondEnd)
    {
        //echo "\n $firstBegin >= $secondBegin and $firstEnd <= $secondEnd \n";
        if ($firstBegin >= $secondBegin and $firstEnd <= $secondEnd) {
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

    public function getBeginInPeriodType($begin, $end, $type)
    {
        $point = null;
        foreach ($this->_inData as $data) {
            if (($data["type"] === $type) and
                ($this->isInRange($data["begin"], $begin, $end))
            ) {
                if (is_null($point)) {
                    $point = $data;
                } else {
                    if ($point["id"] > $data["id"]) {
                        $point = $data;
                    }
                }
            }
        }
        return $point;
    }

    public function isBeginTopRowType($row)
    {
        $result = true;
        foreach ($this->_inData as $data) {
            if ($data["type"] === $row["type"]) {
                if ($this->isInRange($row["begin"], $data["begin"], $data["end"])
                    and ($row["id"] > $data["id"])
                ) {
                    $result = false;
                    break;
                }
            }
        }
        return $result;
    }

    public function getBeginInPeriod($begin, $end)
    {
        $point = $end;
        foreach ($this->getTypes() as $type) {
            $row = $this->getBeginInPeriodType($begin, $end, $type);
            if (!is_null($row) and $this->isBeginTopRowType($row)) {
                $newPoint = $row["begin"];
                if ($point > $newPoint) {
                    $point = $newPoint;
                }
            }
        }
        return $point;
    }

    public function getPeriods()
    {
        $periods = array();
        $begin = $this->getFirstBegin();
        /**
         * @todo change to use while (now protect from unlimited repeat)
         */
        for ($i = 0; $i < 25; $i++) {
            $fullEnd = $this->getCurrentFullEnd($begin);

            $end = $this->getBeginInPeriod($begin, $fullEnd);

            $periods[] = array("begin" => $begin, "end" => $end);

            $begin = $end;
            if ($end == $this->getLastEnd()) {
                break;
            }
        }
        return $periods;
    }

    public function calcOnePeriodType($begin, $end, $type)
    {
        $row = null;
        foreach ($this->_inData as $data) {
            if ($data["type"] === $type) {
                if ($this->isRangeInRange($begin, $end, $data["begin"], $data["end"])) {
                    if ($row === null) {
                        $row = $data;
                    }
                    if ($row["id"] > $data["id"]) {
                        $row = $data;
                    }
                }
            }
        }
        return $row["summa"];
    }

    public function calcPeriods($periods)
    {
        $sumPeriods = array();
        foreach ($periods as $period) {
            $sumPeriod = 0;
            foreach ($this->getTypes() as $type) {
                $sumPeriod = $sumPeriod + $this->calcOnePeriodType($period["begin"], $period["end"], $type);
            }
            $sumPeriods[] = array("begin" => $period["begin"], "end" => $period["end"], "summa" => $sumPeriod);
        }
        return $sumPeriods;
    }


}