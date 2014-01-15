<?
/**
 * Модель таблицы Administrators
 */
class Administrators extends System_Model
{
	/**
	 * Название таблицы
	 * @var string
	 */
	private $_table = 'administrators';


	/**
	 * Получение данных записи администратора
	 * @param $name
	 * @return array|bool
	 */
	public function getByUserName( $name ) {

		$name = $this->escape( $name );

		$this->db->query("SELECT * FROM " . $this->_table . " WHERE login = '" . $name . "'  LIMIT 1");
		return $this->db->result();
	}


	/**
	 * Метод возвращает имя таблицы
	 * @return string
	 */
	public function table()
	{
		return $this->_table;
	}



}
