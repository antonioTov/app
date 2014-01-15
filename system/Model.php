<?
/**
 * Дает доступ к моделям через свойства екземпляра класса
 *   Пример:
 *      $model = new System_Model();
 *      $users = $model->users;
 *
 * Создает подключение к БД
 *
 * @author Anton Tovstenko
 */
class  System_Model
{

    /**
     * Директория хранения моделей
     * @var string
     */
    private $_dir = '/model/';


    /**
     * Экземпляр компонента для работи в ДБ
     * @var Component_Db
     */
    protected  $db;


    /**
     * Созданные объекты моделей
     * @var array
     */
    private static $objects = array();


    /**
     * Конструктор модели
     */
    public function __construct()
    {
        $this->db = Component_Db::getInstance();
    }


	/**
	 * Очистка данных
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
	 * Экранирование
	 * @param $val
	 * @return string
	 */
	public function escape( $val )
	{
		return  mysql_real_escape_string( htmlspecialchars( strip_tags( trim( $val ) ) ) );
	}

    /**
     * Магический метод, создает нужный объект Модели
     * @param $name
     * @return mixed
     */
    public function __get( $name )
	{
		// Если такой объект уже существует, возвращаем его
		if ( isset( self::$objects[$name] ) ) {
			return( self::$objects[$name] );
		}
		
		// Определяем имя нужного класса
		$class = ucfirst( strtolower( $name ) );
        $path  = ROOT.$this->_dir.$class.'.php';
        
        if( ! file_exists( $path ) ) {
			die("Модель <strong>$class</strong> не существует!");
		}

		// Подключаем его
		include_once( $path );

		// Сохраняем для будущих обращений к нему
		self::$objects[$name] = new $class();

		// Возвращаем созданный объект
		return self::$objects[$name];
	}   


}
