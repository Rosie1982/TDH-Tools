<?php
/**
 * 
 *        
 * 
 *   
 */
class Description extends Data {
    
    
    public static function fromTable($tablename, $DataName = "dataname", $Description = "description", $MoreInformation = "moreinfo", $URI = "uri",$dataname_is = null)
    {

        $where = "";
        if (!is_null($dataname_is))
        {
            $where = " where {$DataName} = E'{$dataname_is}'";
        }
        
        $q = "select {$DataName},{$Description},{$MoreInformation},{$URI} from {$tablename} {$where} ";
        
        $result = DBO::Query($q, $DataName);        
        if ($result instanceof ErrorMessage) 
            return ErrorMessage::Stacked (__METHOD__,__LINE__
                                        ,"Failed to get Description from Table 
                                          tablename = {$tablename}\n
                                          DataName  = {$DataName}\n
                                          Description = {$Description}\n
                                          MoreInformation = {$MoreInformation}\n 
                                          URI = {$URI}\n"
                                        ,true
                                        ,$result);

        $row = util::first_element($result);
                                          
        $desc = new Description();
        $desc->DataName        (array_util::Value($row, $DataName));
        $desc->Description     (array_util::Value($row, $Description));
        $desc->MoreInformation (array_util::Value($row, $MoreInformation));
        $desc->URI             (array_util::Value($row, $URI));
                                          
        
        return $desc;
    
    }
    
    
    
    public function __construct() {
        parent::__construct();
        $this->DataName(__CLASS__);

        $this->Filename();
        $this->Description();
        $this->Source();
        $this->MoreInformation();
        $this->URI();

    }
    
    public function __destruct() {

        parent::__destruct();
    }

    /**
     * Called with (null) return Filename<br>
     * Called with ($arg)    set Filename<br>
     *
     * @return string Filename
     *
     */
    public function Filename() {
        if (func_num_args() == 0)
        return $this->getProperty();
        return $this->setProperty(func_get_arg(0));
    }

    /**
     * Called with (null) return Description<br>
     * Called with ($arg)    set Description<br>
     *
     * @return string Description
     *
     */
    public function Description() {
        if (func_num_args() == 0) return $this->getProperty();
        
        $value = func_get_arg(0);
        $value = str_replace("'","\'", $value);
        $value = str_replace('"','\"', $value);
        
        $this->setProperty($value);
        
        return $this->setProperty($value);
    }

    /**
     * Called with (null) return Source (string)<br>
     * Called with ($arg)    set value of property to $arg<br>
     *
     * @return string Source
     *
     */
    public function Source() {
        if (func_num_args() == 0) return $this->getProperty();
        
        return $this->setProperty(func_get_arg(0));
    }


    /**
     * Called with (null) return MoreInformation<br>
     * Called with ($arg)    set MoreInformation<br>
     *
     * @return string
     *
     */
    public function MoreInformation() {
        if (func_num_args() == 0) return $this->getProperty();
        $value = func_get_arg(0);
        $value = str_replace("'","\'", $value);
        $value = str_replace('"','\"', $value);
        
        $this->setProperty($value);
        
        return $this->setProperty($value);
    }

    /**
     * Intened to be used to store ther URI / URL to connect toanother data source
     *
     * Called with (null) return URI<br>
     * Called with ($arg)    set URI<br>
     *
     * @return string URI
     *
     */
    public function URI() {
        if (func_num_args() == 0) return $this->getProperty();
        
        $value = func_get_arg(0);
        $value = str_replace("'","\'", $value);
        $value = str_replace('"','\"', $value);
        
        $this->setProperty($value);
        
        return $this->setProperty($value);
    }

}
?>