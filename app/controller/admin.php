<?php
/**
 * Controller das galerias do app
 *
 * @author Joubert <eu@redrat.com.br>
 */

namespace App;

class Admin
{
    public function __construct()
    {
        if (!\App\Session::userAuth() && strpos($_SERVER['QUERY_STRING'], 'login') === false) {
            header('Location: '.\App\Functions::getAppUrl('admin/login'));
        }
    }

    public function index()
    {
        \App\View::call('admin\index');
    }

    /**
     * Ação de listagem de galeria
     *
     * @return void
     */
    public function gallery()
    {
        $galleries = \App\Gallery::getAll();

        \App\View::call('admin\galleries\index', ['galleries' => $galleries]);
    }

    /**
     * Ação de inclusão de galeria
     *
     * @return void
     */
    public function galleryNew()
    {
        if ($_POST) {
            $Gallery = new \App\Gallery();
            $Gallery->name = $_POST['name'];
            $Gallery->save();

            header('Location: '.\App\Functions::getAppUrl('admin/gallery'));
        }

        $data['id'] = '';
        $data['name'] = '';
        \App\View::call('admin\galleries\form', $data);
    }

    /**
     * Ação de edição de galeria
     *
     * @return void
     */
    public function galleryEdit($id)
    {
        if ($_POST) {
            $Gallery = new \App\Gallery($_POST['id']);
            $Gallery->name = $_POST['name'];
            $Gallery->save();

            header('Location: '.\App\Functions::getAppUrl('admin/gallery'));
        }

        $Gallery = new \App\Gallery($id);
        $data['id'] = $Gallery->id;
        $data['name'] = $Gallery->name;
        \App\View::call('admin\galleries\form', $data);
    }

    /**
     * Ação de remoção de galeria
     *
     * @return void
     */
    public function galleryRemove($id)
    {
        $Gallery = new \App\Gallery($id);
        $Gallery->delete();

        header('Location: '.\App\Functions::getAppUrl('admin/gallery'));
    }

    /**
     * Ação de listagem de fotos de uma galeria
     *
     * @return void
     */
    public function photo($id)
    {
        $photos = \App\Photo::getAll($id);
        $Gallery = new \App\Gallery($id);

        \App\View::call('admin\photos\index', ['photos' => $photos, 'Gallery' => $Gallery]);
    }

    /**
     * Ação de inclusão de foto de galeria
     *
     * @return void
     */
    public function photoNew($id)
    {
        if ($_POST && $_FILES) {
            $Photo = new \App\Photo();
            $Photo->setGallery(
                new \App\Gallery($_POST['id'])
            );

            $Photo->save();

            header('Location: '.\App\Functions::getAppUrl('admin/photo/'.$_POST['id']));
        }

        $Gallery = new \App\Gallery($id);
        $data['id'] = $Gallery->id;
        \App\View::call('admin\photos\form', $data);
    }

    /**
     * Ação de remoção de foto da galeria
     *
     * @return void
     */
    public function photoRemove($id)
    {
        $Photo = new \App\Photo($id);
        $Photo->delete();

        header('Location: '.\App\Functions::getAppUrl('admin/photo/'.$Photo->getGallery()->id));
    }

    /**
     * Ação de ordem de foto da galeria
     *
     * @return void
     */
    public function photoUp($id)
    {
        $Photo = new \App\Photo($id);
        $Photo->upPosition();

        header('Location: '.\App\Functions::getAppUrl('admin/photo/'.$Photo->getGallery()->id));
    }

    /**
     * Ação de ordem de foto da galeria
     *
     * @return void
     */
    public function photoDown($id)
    {
        $Photo = new \App\Photo($id);
        $Photo->downPosition();

        header('Location: '.\App\Functions::getAppUrl('admin/photo/'.$Photo->getGallery()->id));
    }

    /**
     * Ação de listagem de usuários
     *
     * @return void
     */
    public function user()
    {
        $users = \App\User::getAll();

        \App\View::call('admin\users\index', ['users' => $users]);        
    }

    /**
     * Ação de inclusão de usuário
     *
     * @return void
     */
    public function userNew()
    {
        if ($_POST) {
            $User = new \App\User();
            $User->username = $_POST['username'];
            $User->password = $_POST['password'];
            $User->save();

            header('Location: '.\App\Functions::getAppUrl('admin/user'));
        }

        $data['id'] = '';
        $data['username'] = '';
        \App\View::call('admin\users\form', $data);
    }

    /**
     * Ação de edição de usuário
     *
     * @return void
     */
    public function userEdit($id)
    {
        if ($_POST) {
            $User = new \App\User($_POST['id']);
            $User->password = $_POST['password'];
            $User->save();

            header('Location: '.\App\Functions::getAppUrl('admin/user'));
        }

        $User = new \App\User($id);
        $data['id'] = $User->id;
        $data['username'] = $User->username;
        \App\View::call('admin\users\form', $data);
    }

    public function login()
    {
        if ($_POST) {
            $User = \App\User::auth($_POST['username'], $_POST['password']);

            if ($User) {
                \App\Session::startSession($User);

                header('Location: '.\App\Functions::getAppUrl('admin'));
            }

        }

        \App\View::call('admin\login');
    }

    public function logout()
    {
        \App\Session::finishSession();

        header('Location: '.\App\Functions::getAppUrl('admin/login'));
    } 
}