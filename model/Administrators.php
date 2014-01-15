<?
/**
 * ������ ������� Administrators
 */
class Administrators extends System_Model
{
	/**
	 * �������� �������
	 * @var string
	 */
	private $_table = 'administrators';


	/**
	 * ��������� ������ ������ ��������������
	 * @param $name
	 * @return array|bool
	 */
	public function getByUserName( $name ) {

		$name = $this->escape( $name );

		$this->db->query("SELECT * FROM " . $this->_table . " WHERE login = '" . $name . "'  LIMIT 1");
		return $this->db->result();
	}


	/**
	 * ����� ���������� ��� �������
	 * @return string
	 */
	public function table()
	{
		return $this->_table;
	}



}
