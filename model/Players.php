<?
/**
 * Модель таблицы Players
 */
class Players extends System_Model
{
	/**
	 * Название таблицы
	 * @var string
	 */
	private $_table = 'players';


	/**
	 * Количество найденных результатов
	 * @var
	 */
	public $count;


	/**
	 * Получение данных игроков
	 * @return array|bool
	 */
	public function getAll()
	{
		return $this->find();
	}



	/**
	 * Поиск игроков
	 * @param $condition
	 * @return array|bool
	 */
	public function search( $condition )
	{
		return $this->find($condition);
	}



	/**
	 * Служебный метод, для поиска
	 * @param null $condition
	 * @return array|bool
	 */
	private function find( $condition = null )
	{
		$model = new System_Model();
		$admin = new $model->administrators;
		$adminTable = $admin->table();
		$table = $this->_table;

		if ( $condition ) {
			$condition = " WHERE ".$condition;
		}

		$this->db->query('
									SELECT
										' . $table . '.id,
										' . $table . '.username,
										' . $table . '.first_name,
										' . $table . '.last_name,
										' . $table . '.birth_date,
										' . $table . '.email,
										' .$adminTable . '.login as adminLogin
									FROM ' . $table . '
									LEFT JOIN ' .$adminTable . '
									ON ' . $adminTable . '.id = ' . $table . '.admin_id'.
									$condition
									);

		$this->count = $this->db->num_rows();

		return $this->db->results();
	}

	/**
	 * Добавление игрока в ДБ
	 * @param array $data
	 * @return int
	 */
	public function add( array $data )
	{
		$this->db->query("
									INSERT INTO " . $this->_table . " SET
									username 	= '" .$data['username']. "',
									first_name = '" .$data['first_name']. "',
									last_name 	= '" .$data['last_name']. "',
									birth_date 	= '" .$this->dateFormat( $data['birth_date'] ) . "',
									email 		= '" .$data['email']. "',
									admin_id	= '" .$data['admin_id']. "'
		 							");

		return $this->db->insert_id();
	}


	/**
	 * Редактирование данных игрока
	 * @param array $data
	 * @return bool
	 */
	public function edit( array $data )
	{
		$this->db->query("
									UPDATE " . $this->_table . " SET
									first_name = '" .$data['first_name']. "',
									last_name 	= '" .$data['last_name']. "',
									birth_date 	= '" .$this->dateFormat( $data['birth_date'] ) . "',
									email 		= '" .$data['email']. "'
									WHERE id 	= '" .$data['player_id']. "'
		 							");

		return true;
	}


	/**
	 * Удаление игрока
	 * @param $id
	 * @return int
	 */
	public function delete($id)
	{
		$id = $this->escape($id);
		$this->db->query("DELETE FROM  " . $this->_table . " WHERE id = ".$id );
		return $this->db->affected_rows();
	}


	/**
	 * Удаление нескольких игроков
	 * @param $ids
	 * @return int
	 */
	public function deleteByIds($ids)
	{
		$ids = $this->escape($ids);
		$this->db->query("DELETE FROM  " . $this->_table . " WHERE id IN (".$ids.")" );
		return $this->db->affected_rows();
	}


	/**
	 * Получение игрока по его username
	 * @param $name
	 * @return bool|object|stdClass
	 */
	public function getByName($name)
	{
		$name = $this->escape($name);

		$this->db->query("SELECT * FROM " . $this->_table . " WHERE username = '" . $name . "' LIMIT 1");
		return $this->db->result();
	}


	/**
	 * Получение игрока по его ID
	 * @param $id
	 * @return bool|object|stdClass
	 */
	public function getById($id)
	{
		$id = (int) $id;

		$this->db->query("SELECT * FROM " . $this->_table . " WHERE id = '" . $id . "' LIMIT 1");
		return $this->db->result();
	}


	/**
	 * Получение поле	й таблицы
	 * @return array
	 */
	public function getColumns()
	{
		$this->db->query("SHOW COLUMNS FROM " . $this->_table );
		$res = $this->db->results();

		$data = array();

		foreach ($res as $item) {
			$data[] = $item->Field;
		}

		return $data;
	}


	/**
	 * Преобразование даты в стандартный вид
	 * @param $date
	 * @return string
	 */
	public function dateFormat( $date )
	{
		$date  = new DateTime( $date );
		return $date->format('Y-m-d');
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
