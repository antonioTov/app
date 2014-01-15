<?
/**
 * ���� ������ � ������� ����� �������� ���������� ������
 *   ������:
 *      $model = new System_Model();
 *      $users = $model->users;
 *
 * ������� ����������� � ��
 *
 * @author Anton Tovstenko
 */
class  System_Model
{

    /**
     * ���������� �������� �������
     * @var string
     */
    private $_dir = '/model/';


    /**
     * ��������� ���������� ��� ������ � ��
     * @var Component_Db
     */
    protected  $db;


    /**
     * ��������� ������� �������
     * @var array
     */
    private static $objects = array();


    /**
     * ����������� ������
     */
    public function __construct()
    {
        $this->db = Component_Db::getInstance();
    }


	/**
	 * ������� ������
	 * @param $val
	 * @param $type
	 * @return int|string
	 */
	public function clear( $val, $type )
	{
		if($type == 'string')
			return strval(preg_replace('/[^\p{L}\p{Nd}\d\s_\-\.\%\s]/ui', '', $val));

		if($type == 'integer')
			return intval($val);

	}


	/**
	 * �������������
	 * @param $val
	 * @return string
	 */
	public function escape( $val )
	{
		return  mysql_real_escape_string( htmlspecialchars( strip_tags( trim( $val ) ) ) );
	}

    /**
     * ���������� �����, ������� ������ ������ ������
     * @param $name
     * @return mixed
     */
    public function __get( $name )
	{
		// ���� ����� ������ ��� ����������, ���������� ���
		if ( isset( self::$objects[$name] ) ) {
			return( self::$objects[$name] );
		}
		
		// ���������� ��� ������� ������
		$class = ucfirst( strtolower( $name ) );
        $path  = ROOT.$this->_dir.$class.'.php';
        
        if( ! file_exists( $path ) ) {
			die("������ <strong>$class</strong> �� ����������!");
		}

		// ���������� ���
		include_once( $path );

		// ��������� ��� ������� ��������� � ����
		self::$objects[$name] = new $class();

		// ���������� ��������� ������
		return self::$objects[$name];
	}   


}
