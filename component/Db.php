<?
/**
 * Компонент для работы с Базой Данных
 */
class Component_Db
{ 
    private $db_user;
    private $db_pass;
    private $db_name;
    private $db_location;
    private $cache_size;
    private $charset;
    private $show_error;
    
    private $db_id = false;
    public  $res_id = '';
    private $connected = false;
    
    private  static $instance;
    
    public function __construct()
    {
        $this->connect();
    }


    public static function getInstance()
    {
        if(isset(self::$instance)){
            return self::$instance;
        }
        self::$instance = new self();

        return self::$instance;
    }


    function connect()
	{
        // При повторном вызове возвращаем существующий линк
		if(!empty($this->db_id))
			return $this->db_id;

        $config =  System_Config::getInstance();
         
        if(!$config->db)
           return false;
        
        $this->db_user 		= $config->db->user;
        $this->db_pass 		= $config->db->pass;
        $this->db_name 	= $config->db->name;
        $this->charset 		= $config->db->charset;
        $this->db_location 	= $config->db->location;
        $this->show_error 	= $config->db->show_error;
        //$this->cache_size  	= $config->db->cache_size;
       
        if(!$this->db_id = @mysql_connect($this->db_location, $this->db_user, $this->db_pass)) {
			if($this->show_error == 1) { 
				$this->display_error(mysql_error(), mysql_errno());
			} else { 
				return false;
			}
		} 

		if(!@mysql_select_db($this->db_name, $this->db_id)) {
			if($this->show_error == 1) {
				$this->display_error(mysql_error(), mysql_errno());
			} else {
				return false;
			}
		}
        
        if($this->charset) {
            mysql_query("SET NAMES $this->charset");
        }

        mysql_query('SET TIME_ZONE=\'+3:00\'');
        //mysql_query('SET GLOBAL query_cache_size = '.$this->cache_size);


        $this->connected = true;
		return $this->db_id;
	}
   
    public function close(){

		if (is_resource($this->db_id)) {
			mysql_close($this->db_id);
		}

    }
    
   
    /**
    * Запрос к БД
    */ 
    public function query($query)
    {
        if(!($this->res_id = mysql_query($query))) {

			if($this->show_error == 1) {
				$this->display_error(mysql_error(), mysql_errno(), $query);
			}
		}
        else {
            return $this->res_id;
        }
        
    }
    
    
	/**
	 * Возвращает результаты запроса. 
     * Необязательный второй аргумент указывает какую колонку возвращать вместо всего массива колонок
	 */
    public function results($field = null)
    {
        $results = array();
        
        if(!$this->res_id)
            return false;
        
        if($this->num_rows() == 0)
			return array();
            
 		while($row = mysql_fetch_object($this->res_id))
		{
			if(!empty($field) && isset($row->$field))
				array_push($results, $row->$field);				
			else
				array_push($results, $row);
		}
		return $results;
    }
    
    
	/**
	 * Возвращает первый результат запроса. 
     * Необязательный второй аргумент указывает какую колонку возвращать вместо всего массива колонок
	 */
	public function result($field = null)
	{
		$result = array();
        
		if(!$this->res_id)
			return false;

		$row = mysql_fetch_object($this->res_id);
		if(!empty($field) && isset($row->$field))
			return $row->$field;
		elseif(!empty($field) && !isset($row->$field))
			return false;
		else
			return $row;
	}

	
    /**
	 * Возвращает последний вставленный id 
	 */   
   	public function insert_id()
	{
		return mysql_insert_id();
	}
    
    
	/**
	 * Возвращает количество выбранных строк
	 */   	
    public function num_rows()
	{
		return mysql_num_rows($this->res_id);
	}


	/**
	 * Возвращает количество затронутых строк
	 */
	public function affected_rows()
	{
		return mysql_affected_rows();
	}
    
	/**
	 * Освобождает память после запроса
	 */
	public function free()
	{
		@mysql_free_res_id($this->res_id);
	}
    
   	function display_error($error, $error_num, $query = '')
	{
		if($query) {
			// Safify query
			$query = preg_replace("/([0-9a-f]){32}/", "********************************", $query); // Hides all hashes
			$query_str = "$query";
		}
		
		echo '<?xml version="1.0" encoding="iso-8859-1"?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
		<title>MySQL Fatal Error</title>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
		<style type="text/css">
		<!--
		body {
			font-family: Verdana, Arial, Helvetica, sans-serif;
			font-size: 12px;
			font-style: normal;
			color: #000000;
		}
		-->
		</style>
		</head>
		<body>
			<font size="3">MySQL Error!</font> 
			<br />------------------------<br />
			<br />
			
			<u>The Error returned was:</u> 
			<br />
				<strong>'.$error.'</strong>

			<br /><br />
			</strong><u>Error Number:</u> 
			<br />
				<strong>'.$error_num.'</strong>
			<br />
			
			<div>'.$query_str.'</div>

		</body>
		</html>';
		
		exit();
	}
        
}


