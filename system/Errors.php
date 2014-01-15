<?
/**
 * ����� ��� ������ � ��������
 *
 * @author 	Anton Tovstenko
 */
class  System_Errors
{

    /**
     * ������ ��� �������� ������
     * errorName => errorMessage
     * @var array
     */
    private static $errors = array();


    /**
     * ���������� ������
     * @param $name
     * @param $mess
     */
    public static function add( $name , $mess )
    {
        self::$errors[$name] = $mess;
    }


    /**
     * ��������� ��������� ������ �� �� ��������
     * @param $name
     * @return bool
     */
    public static function get($name)
    {
        if ( isset( self::$errors[$name] ) ) {
            return self::$errors[$name];
        }
        return false;
    }


    /**
     * �������� ������� ������
     * @return bool
     */
    public static function isErrors()
    {
        if ( !empty( self::$errors ) ) {
            return true;
        }
        return false;
    }


    /**
     * ��������� ����� ������� ������
     * @return array
     */
    public static function getAll()
    {
        return self::$errors;
    }


    /**
     * ������ 404
     */
    public static function error404()
    {
		System_App::init(array('controller' =>'error', 'action' => 'error404'));
		System_App::run();
        exit;
    }

	/**
	 * ������ 403
	 */
	public static function error403()
	{
		System_App::init(array('controller' =>'error', 'action' => 'error403'));
		System_App::run();
		exit;
	}


}