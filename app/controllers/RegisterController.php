<?php

/**
 * Description of RegisterController
 *
 * @author jrcsc
 */
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Db\Adapter\Pdo\Mysql;

class RegisterController extends ControllerBase {

    private $tipo;
    private $name;
    private $username;
    private $tipoId;
    private $numeroId;
    private $email;
    
    public function initialize() {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function revisaQuickbooks() {
        $valido = array();
        if ($this->tipo == 1) { // empleado
            $usuarios = Employee::find(
                  [
                     "conditions" => "AccountNumber = ?1",
                     "bind" => [
                        1 => $this->numeroId
                     ]
                  ]
            );
        } elseif ($this->tipo == 2) { // cliente
            $usuarios = Customer::find(
                  [
                     "conditions" => "AccountNumber = ?1",
                     "bind" => [
                        1 => $this->numeroId
                     ]
                  ]
            );
        } elseif ($this->tipo == 3) { // proveedor
            $usuarios = Vendor::find(
                  [
                     "conditions" => "AccountNumber = ?1",
                     "bind" => [
                        1 => $this->numeroId
                     ]
                  ]
            );
        }
        $valido['quickbooks'] = "No existe tal vendedor o cliente o empleado en nuestra base de datos";
        foreach ($usuarios as $quickbooks) {
            $valido = array();
        }
        return $valido;
    }

    public function usuarioIgual($reqPost) {
        $valido = array();
        foreach ($reqPost as $usuario) {
            if ($usuario->name === $this->name) {
                $valido['name'] = 'El nombre de usuario ya existe, debe ingresar un nombre diferente';
            }
            if ($usuario->username === $this->username) {
                $valido['username'] = 'La identificacion de usuario ya existe, debe ingresar una identificacion de usuario diferente';
            }
            if ($usuario->email === $this->email) {
                $valido['email'] = 'Esta direccion de correo electronico ya existe debe ingresar una diferente';
            }
        }
        return $valido;
    }

    /**
     * Action to register a new user
     */
    public function indexAction() {
        $form = new RegistrarteForm;
        if ($this->request->isPost()) {
            $this->tipo = $this->request->getPost('tipo');
            $this->tipoId = $this->request->getPost('tipoId');
            $this->numeroId = $this->request->getPost('numeroId');
            $this->name = $this->request->getPost('name', array('string', 'striptags'));
            $this->username = $this->request->getPost('username', 'alphanum');
            $this->email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $reqPost = array();
            $reqPost = $this->request->getPost();
            $users = Users::find(
                  [
                     "conditions" => "name = ?1 OR username = ?2 OR email = ?3",
                     "bind" => [
                        1 => $this->name,
                        2 => $this->username,
                        3 => $this->email
                     ]
                  ]
            );
            $valido = $this->usuarioIgual($users);
            $valida = $this->revisaQuickbooks();

            if (count($valida) == 0 && count($valido) == 0) {
                $user = new Users();
                $user->numeroId = $this->numeroId;
                $user->tipoId = $this->tipoId;
                $user->tipo = $this->tipo;
                $user->username = $this->username;
                $user->password = sha1($password);
                $user->name = $this->name;
                $user->email = $this->email;
                $user->created_at = new Phalcon\Db\RawValue('now()');
                $user->active = 'N';

                if ($user->save() === false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->tag->setDefault('email', '');
                    $this->tag->setDefault('password', '');
                    $this->flash->success('Gracias por registrarse a vuelta de correo usted recibira la notificacion de que su cuenta esta habilitada');

                    return $this->dispatcher->forward(
                          [
                             "controller" => "session",
                             "action" => "index",
                          ]
                    );
                }
            } else {
                foreach ($valido as $mensaje) {
                    $this->flash->error((string) $mensaje);
                }
                foreach ($valida as $mensaje) {
                    $this->flash->error((string) $mensaje);
                }
            }
        }
        $this->view->form = $form;
    }

}
