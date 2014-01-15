<?
/**
 * Class AuthController
 * ���������� �������� �����������
 */
class AuthController extends System_Controller
{

	/**
	 * �������������
	 */
	public function init() {
        $this->view->ident = $this->ident;
    }


	/**
	 * ���������������� �� ����� �����������
	 */
	public function indexAction()
    {
        $this->loginAction();
    }


	/**
	 * ������� �����������
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
						// ������� ��������� �� ������
						$this->view->error = true;
					}

				} else {
					// ������� ��������� �� ������
					$this->view->error = true;
				}

			} else {
				// ������� ��������� �� ������
				$this->view->error = true;
			}
		}

		// �������� ����� � �����
        $this->view->login = $login;

    }


	/**
	 * ��������������
	 */
	public function logoutAction()
    {
        $this->ident->logout();
        $this->redirect('/');
    }

}