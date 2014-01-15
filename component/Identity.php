<?
/**
 * �������������
 */
class Component_Identity
{

	/**
	 * �������� �����������
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
	 * �������������
	 * @param $data
	 */
	public function login( $data )
    {
		System_Session::setParam('logged', true);
		System_Session::setParam('admin_id', $data->id);
		System_Session::setParam('admin_login', $data->login);
    }


	/**
	 * ��������� ID �����������������
	 * @return int
	 */
	public function getId()
	{
		return System_Session::getParam('admin_id');
	}


	/**
	 * ��������� ������
	 * @return string
	 */
	public function getLogin()
	{
		return System_Session::getParam('admin_login');
	}


	/**
	 * �����
	 */
	public function Logout()
    {
		System_Session::destroy();
    }


	/**
	 * ��������� ���� �������
	 * @param $params
	 */
	public function setAccess($params)
	{
		foreach ($params as $name => $access) {
			System_Session::setParam($name, $access);
		}
	}


	/**
	 * �������� ������� � ����������� � ��������
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
