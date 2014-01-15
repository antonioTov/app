<?
/**
 * Валидатор данных
 */
class Component_Validator
{

	/**
	 * Массив с пустыми полями
	 * @var array
	 */
	private $_arr 	= array();

	/**
	 * Массив с обработанными данными
	 * @var array
	 */
	private $_data 	= array();


	/**
	 * Проверка данных
	 * @param array $data
	 * @return bool
	 */
	public function is_valid( array $data )
	{
		$this->_data = $data;

		if ( !empty( $this->_data ) )
		{
			foreach( $this->_data as $key => $val ) {

				if ( empty( $val ) ) {
					$this->_arr[$key] 	= true;
				} else {
					$this->_data[$key] = mysql_real_escape_string( htmlspecialchars( strip_tags( trim( $val ) ) ) );
				}

			}

			if( empty( $this->_arr ) ) {
				return true;
			}
		}

		return false;
	}


	/**
	 * Пустые поля
	 * @return array
	 */
	public function getInfo()
	{
		return $this->_arr;
	}


	/**
	 * Обработанные данные
	 * @return array
	 */
	public function getData()
	{
		return $this->_data;
	}


	/**
	 * Валидация Email
	 * @param $email
	 * @return bool
	 */
	public function checkEmail( $email )
	{
		if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			return true;
		}
		return false;
	}


	/**
	 * проверка длинны поля
	 * @param $name
	 * @return bool
	 */
	public function checkLength( $name )
	{
		$maxLen = System_Config::get('input_max_len');

		if ( strlen( $name ) > $maxLen) {
			return false;
		}
		return true;
	}

}