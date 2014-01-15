<?php
/**
 * Конструктор SQL-запрсов
 * 
 * Example
 * 
 * $sql = new Sql;
 * $sql->select()
    ->from('test')
    ->join('test2')
    ->on("test.id = test2.id")
    ->where("title = 'aaaa'")
    ->group('date')
    ->order('id')
    ->limit(0,20);
    echo $sql->getQuery();

  * */
class Component_Sql
{
    private $_query;
    
    public function __construct(){
        $this->_query = new stdClass;
    } 
    
    
    public function getQuery(){
        return $this->_query;
    }
    
    public function select($fields = '*'){
        $this->_query = "SELECT $fields FROM ";
        return $this;
    }
    
    public function from($table){
        $this->_query .= $table.' ';
        return $this;
    }
    
    public function join($table){
        $this->_query .= ' JOIN '.$table.' ';
        return $this;
    }
    
    
    public function on($condition){
        $this->_query .= ' ON '.$condition.' ';
        return $this;
    }
    
    
    public function where($condition){
        $this->_query .= ' WHERE '.$condition.' ';
        return $this;
    }
    
    
    public function order($field,$direction = 'ASC'){
        $this->_query .= ' ORDER BY '.$field.' '.$direction;
        return $this;
    }
    
    public function group($field){
        $this->_query .= ' GROUP BY '.$field.' ';
        return $this;
    }
    
    public function limit($start,$limit = ''){
        $this->_query .= ' LIMIT '.$start.', '.$limit;
        return $this;
    }
    
}


