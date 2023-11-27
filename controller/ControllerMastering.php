<?php

require_once 'model/Skill.php';
require_once 'model/User.php';
require_once 'model/Mastering.php';

/*
 * list masterings : permet de visualiser la liste des compétences maîtrisées par l'utilisateur.
 * add mastering : permet d'ajouter la maîtrise d'une nouvelle compétence.
 * edit mastering : permet de modifier le niveau de maîtrise d'une compétence.
 * delete mastering : permet de supprimer la maîtrise d'une compétence.
 */

class ControllerMastering extends Controller
{

    public function index()
    {
        $this->mastered_skills();
    }

    public function mastered_skills()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('param1', $_GET) ?
                User::getUserByUserId($_GET["param1"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }

        // if user id giving in URL is invalid redirect to manage users
        if ($user == null) {
            $this->redirect("manageusers");
        }

        // get all mastered skills for the connected user
        $masterings = $user->getMasteredSkills();

        (new View("mastering"))->show(["user" => $user, "masterings" => $masterings, "loggedUser" => $loggedUser]);
    }

    public function delete()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $user = $user->getUserId();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (array_key_exists('userId', $_POST) && array_key_exists('skillId', $_POST)) {
                $mastering = Mastering::getMasteringById($user, $_POST['skillId']);
            } else {
                $this->redirect('main', "error");
            }

            if ($mastering != null) {
                $mastering->delete();
                $this->redirect("mastering", 'index', $user);
            }
        } else {
            $this->redirect('main', "error");
        }
    }

    public function deleteService()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $user = $user->getUserId();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (array_key_exists('userId', $_POST) && array_key_exists('skillId', $_POST)) {
                $mastering = Mastering::getMasteringById($user, $_POST['skillId']);
            } else {
                $this->redirect('main', "error");
            }

            if ($mastering != null) {
                $mastering->delete();
            }
        } else {
            $this->redirect('main', "error");
        }
        return "success";
    }


    /**
     * @throws Exception
     */
    public function delete_confirm()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $userId = $user->getUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (array_key_exists('userId', $_POST) && array_key_exists('skillId', $_POST)) {
                $mastering = Mastering::getMasteringById($userId, $_POST['skillId']);
            } else {
                $this->redirect('main', "error");
            }

            if ($mastering != null && $user != null) {
                (new View("delete_mastering"))->show(["mastering" => $mastering, "user" => $user, "loggedUser" => $loggedUser]);
            }
        } else {
            $this->redirect('main', "error");
        }
    }


    public function addService()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $user = $user->getUserId();

        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (
                array_key_exists('skillId', $_POST) &&
                array_key_exists('level', $_POST) &&
                array_key_exists('userId', $_POST)
            ) {
                $skillId   = $_POST['skillId'];
                $level     = $_POST['level'];
                $mastering = new Mastering($user, $skillId, $level);
                $errors = $mastering->validate();
            } else {
                $this->redirect('main', "error");
            }

            if (empty($errors)) {
                $mastering->insert();
            }
            return "success";
        } else {
            $this->redirect('main', "error");
        }
    }


    public function add()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (
                array_key_exists('skillId', $_POST) &&
                array_key_exists('level', $_POST) &&
                array_key_exists('userId', $_POST)
            ) {
                $user = User::getUserByUserId($_POST['userId']);
                if ($_POST['skillId'] == -1) {
                    $this->redirect("mastering", 'index', $user->getUserId());
                }
                $skillId   = $_POST['skillId'];
                $level     = $_POST['level'];
                $mastering = new Mastering($user->getUserId(), $skillId, $level);
                $errors = $mastering->validate();
            } else {
                $this->redirect('main', "error");
            }

            if (empty($errors)) {
                $mastering->insert();
            }
            $this->redirect("mastering", 'index', $user->getUserId());
        } else {
            $this->redirect('main', "error");
        }
    }

    public function updateLevel()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $user = $user->getUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;
            var_dump($_POST['userId']);
            var_dump($_POST['skillId']);
            var_dump($_POST['level']);

            if (array_key_exists('skillId', $_POST) && array_key_exists('userId', $_POST) && array_key_exists('level', $_POST)) {
                $skillId   = $_POST['skillId'];
                $level = $_POST['level'];
                $mastering = Mastering::getMasteringById($user, $skillId);
            } else {
                $this->redirect('main', "error");
            }

            if ($mastering != null) {
                $mastering->updateLevel($level);
            }
        } else {
            $this->redirect('main', "error");
        }
    }


    public function increase()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $user = $user->getUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (array_key_exists('skillId', $_POST) && array_key_exists('userId', $_POST)) {
                $skillId   = $_POST['skillId'];
                $mastering = Mastering::getMasteringById($user, $skillId);
            } else {
                $this->redirect('main', "error");
            }

            if ($mastering != null) {
                $mastering->increase();
                $this->redirect("mastering", 'index', $user);
            }
        } else {
            $this->redirect('main', "error");
        }
    }

    public function decrease()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ?
                User::getUserByUserId($_POST["userId"]) :
                $this->get_user_or_redirect();
        } else {
            $user = $loggedUser;
        }
        $user = $user->getUserId();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mastering = null;

            if (array_key_exists('skillId', $_POST) && array_key_exists('userId', $_POST)) {
                $skillId   = $_POST['skillId'];
                $mastering = Mastering::getMasteringById($user, $skillId);
            } else {
                $this->redirect('main', "error");
            }

            if ($mastering != null) {
                $mastering->decrease();
                $this->redirect("mastering", 'index', $user);
            }
        } else {
            $this->redirect('main', "error");
        }
    }
}
