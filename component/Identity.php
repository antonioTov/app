<?
/**
 * Идентификатор
 */
class Component_Identity
{

	/**
	 * Проверка авторизации
	 * @return bool
	 */
	public function isAuth()
    {
        if ( System_Session::getParam('logged')) {
            return true;
        }
        return false;
    }


	/**
	 * Залогинивание
	 * @param $data
	 */
	public function login( $data )
    {
		System_Session::setParam('logged', true);
		System_Session::setParam('admin_id', $data->id);
		System_Session::setParam('admin_login', $data->login);
    }


	/**
	 * Получение ID авторизовавшегося
	 * @return int
	 */
	public function getId()
	{
		return System_Session::getParam('admin_id');
	}


	/**
	 * Получение логина
	 * @return string
	 */
	public function getLogin()
	{
		return System_Session::getParam('admin_login');
	}


	/**
	 * Выход
	 */
	public function Logout()
    {
		System_Session::destroy();
    }


	/**
	 * Установка прав доспупа
	 * @param $params
	 */
	public function setAccess($params)
	{
		foreach ($params as $name => $access) {
			System_Session::setParam($name, $access);
		}
	}


	/**
	 * Проверка доступа к контроллеру и действию
	 * @return bool
	 */
	public function checkAccess()
	{
		$controllerName 		= System_App::getControllerName();
		$actionName 			= System_App::setActionName();
		$controllerAccess 	= Session::getParam($controllerName);
		$actionAccess			= Session::getParam($actionName);

		if ( $controllerAccess !== null && $controllerAccess === false  ) {
			System_Errors::error403();
		}

		if ( $actionAccess !== null && $actionAccess === false ) {
			System_Errors::error403();
		}

		return true;
	}
}
