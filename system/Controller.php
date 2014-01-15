<?
/**
 * Class Controller
 * Супер-класс для определения базовых параметров,
 * с которыми будут работать классы-наследники - контроллеры действий
 *
 * @author Anton Tovstenko
 */
class System_Controller
{


    /**
     * Экземпляр класса View
     * Передает данные из контролера в шаблон представления
     * Передача даных реализована двумя способами:
     *      1. $this->view->assign('varName', $varValue);
     *      2. $this->view->varName = $varValue;
     * Управляет кешировашием страницы
     * @var object
     */
    public $view;


	/**
	 * Компонент идентификации пользовапеля
	 * @var object
	 */
	public $ident;



    /**
     * Статическая переменная хранит значение текущего язика локали
     * @var
     */
    private  static $lang;



    public function __construct()
	{
        $this->view            	= new System_View();
        $this->view->cache   	= new System_Cache();
		$this->ident 				= new Component_Identity();
		$url    						= new System_Url();
        $this->view->title     	=  System_Config::getInstance()->title;

        // очистка кеша
		if ( isset( $_GET['clear_cache'] ) ) {
            $this->view->cache->clear();
        }

		//неавторизированных перенаправляем на форму авторизации
		if ( !$this->ident->isAuth() && $url->get(1) != 'login') {
			$this->redirect('/auth/login');
		}

    }



    /**
     * Установление текущего язика.
     * Вызивается в процесе инициализации смещения Url-параметров в классе UrlOffsetLanguage
     * @param $lang
     */
    public static function setLanguage( $lang ) {
        self::$lang = $lang;
    }


    /**
     * Получение текущего язика
     * @return mixed
     */
    public function getLanguage() {
        return self::$lang;
    }



    /**
     * Построение правильного Url-адреса с учетом язиковой локали
     * @param array $params
     * @return string
     */
    public function createUrl( array $params )
    {
        $lang = null;
        if( self::$lang ) {
            $lang = '/' . self::$lang;
        }
        
        $url = '';
        foreach ( $params as $param ) {
            $url .= '/' . $param;
        }
        return $lang . $url;
    }


    /**
     * Перенаправление на указанный адрес
     * @param $url
     */
    public function redirect($url)
    {
        header('location:'.$url);
        exit;
    }


    /**
     * Функция, вызывающая указанное действие контроллера.
     * !!! Запрещено вызивать текущее действие текущего контроллера, то есть самого себя
     * @param array $params
     */
    public function call( array $params )
    {
		System_App::init($params);
		System_App::run();
    }


    /**
     * Определение смещения Url-параметров в связи с добавлением параметра язика локали
     * @return int
     */
    private  function _getOffset()
    {
        return System_App::$offset;
    }


    /**
     * Получение параметров из адресной строки
     * отсчет начинается после Action, с нуля
     * @param $urlOffset
     * @param null $type
     * @return bool
     */
    public function getUrlParam( $urlOffset, $type = null )
    {
        /**
         *смещение для /controller/action
         * @var $default_offset int
		 */
        $default_offset = 2;

        $url = new System_Url();
        return $url->get( $urlOffset + $this->_getOffset() + $default_offset, $type);
    }



    /**
     * Завершение работы контроллера
     * Закрытие соединения с БД
     */
    public function __destruct()
    {
        Component_Db::getInstance()->close();
    }


}