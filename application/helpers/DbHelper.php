<?php


class DbHelper {
    
    private $db;


    public function __construct($db) {
        $this->db = $db;
    }

    public function countRows($field, $table, $key = NULL, $value = NULL){
        if($key === NULL && $value === NULL){
            $sql = "SELECT COUNT(".$field.") FROM ".$table."";
        }else{
            $sql = "SELECT COUNT(".$field.") FROM ".$table." WHERE ".$key." = '".$value."'";
        }
        $count = $this->db->query($sql);
        $rows = $count->fetch(PDO::FETCH_NUM)[0];
        return (int)$rows;
    }
    
    public function resultInArray($sql){
        $result = array();
        $query = $this->db->query($sql);
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $result[] = $row;
        }
        return $result;
    }
    
    
}
