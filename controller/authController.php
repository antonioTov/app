<?
/**
 * Class AuthController
 * Контроллер процесса авторизации
 */
class AuthController extends System_Controller
{

	/**
	 * Инициализация
	 */
	public function init() {
        $this->view->ident = $this->ident;
    }


	/**
	 * Преренаправление на форму авторизации
	 */
	public function indexAction()
    {
        $this->loginAction();
    }


	/**
	 * Процесс авторизации
	 */
	public function loginAction()
    {
        $request = new Component_Request();

        $login = '';
        
		if( $request->post() )
		{
			
            $login = $request->post('login', 'string');
			$pass = $request->post('pass', 'string');

			if ( $login && $pass )
			{
				$model = new System_Model();
				$administrators = $model->administrators;

				if( $data = $administrators->getByUserName($login) )
				{
					if ( $data->login === $login && $data->pass === md5($pass) ) {

						$this->ident->login($data);

						$this->redirect('/');

					} else {
						// выводим сообщение об ошибке
						$this->view->error = true;
					}

				} else {
					// выводим сообщение об ошибке
					$this->view->error = true;
				}

			} else {
				// выводим сообщение об ошибке
				$this->view->error = true;
			}
		}

		// передаем логин в форму
        $this->view->login = $login;

    }


	/**
	 * Разлогинивание
	 */
	public function logoutAction()
    {
        $this->ident->logout();
        $this->redirect('/');
    }

}