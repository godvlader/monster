<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/User.php';

class ControllerUser extends Controller
{

    //page d'accueil. 
    public function index()
    {
        if (isset($_GET["param1"])) {
            $this->redirect('profile');
        }
    }

    public function logout()
    {
        Controller::logout();
    }


    public function check_birth()
    {
        if (isset($_GET["param1"], $_GET["param2"]) && !strtotime($_GET["param1"]) || !is_numeric($_GET["param2"])) {
            $this->redirect('main', "error");
        }
        if (null !== isset($_GET["param1"], $_GET["param2"])) {
            $user = User::getUserByUserId($_GET["param2"]);
            $check = $user->check_birthdate_service($_GET["param1"], $user);
            //return the result of the check
            echo $check;
        }
    }


    public function check_today()
    {
        if (isset($_GET["param1"]) && !strtotime($_GET["param1"])) {
            $this->redirect('main', "error");
        }
        if (null !== isset($_GET["param1"])) {
            $value = new DateTime($_GET['param1']);
            $today = new DateTime();
            if ($today < $value) {
                echo "false";
            } else {
                echo "true";
            }
        }
    }


    public function get_bdate_service()
    {
        if (isset($_POST['user'])) {
            $user = $_POST['user'];
            $compare = User::get_birthdate_service($user);
            echo $compare;
        }
    }

    /*
 * manage users : permet à un admin de visualiser la liste des utilisateurs enregistrés dans le système.
 * A partir de ce UC, il peut demander à visualiser, pour chaque utilisateur, la liste des compétences
 * maîtrisées (list masterings) et la liste des expériences (list experiences) de l'utilisateur en question.
 *
 * edit user : permet à un admin de modifier le signalétique d'un utilisateur (les données de la table user,
 * à l'exception de la clef primaire et des colonnes RegisteredAt et Password).
 * L'admin couramment connecté ne peut pas changer son rôle.
 *
 * delete user : permet à un admin de supprimer un utilisateur, moyennant une demande de confirmation explicite.
 * L'admin couramment connecté ne peut pas se supprimer lui-même.
 */

    public function delete(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedUser->isAdmin()) {
            if (array_key_exists('id', $_POST)) {
                $user = User::getUserByUserId($_POST['id']);
            }

            if ($user !== null && $user->getUserId() !== $loggedUser->getUserId()) {
                $user->delete();
                $this->redirect("manageusers");
            }
        }
    }

    public function delete_confirm(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = array_key_exists('param1', $_GET) ? User::getUserByUserId($_GET['param1']) : $loggedUser;

        if ($user != null && $user->getUserId() !== $loggedUser->getUserId()) {
            (new View("delete_user"))->show(["loggedUser" => $loggedUser, "user" => $user]);
        } else {
            $this->redirect("manageusers");
        }
    }


    public function edit_profile()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $user       = array_key_exists('param1', $_GET) && $loggedUser->isAdmin() ?
            User::getUserByUserId($_GET['param1']) : $loggedUser;
        $errors     = [];
        $success    = array_key_exists('param2', $_GET) && $_GET['param2'] === 'ok' ?
            "Your profile has been successfully updated." : "";



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                array_key_exists("mail", $_POST)
                && array_key_exists("fullName", $_POST)
                && array_key_exists("title", $_POST)
                && array_key_exists("birthdate", $_POST)
                && array_key_exists("role", $_POST)
            ) {
                $mail = $user->getMail();
                $fullName = $user->getFullName();
                $title = $user->getTitle();
                $birthdate = $user->getBirthdate();
                $role = $user->getRole();

                if (isset($_POST["mail"])) {
                    $mail = $_POST["mail"];
                }


                if (isset($_POST["fullName"])) {
                    $fullName = $_POST["fullName"];
                }

                if (isset($_POST["title"])) {
                    $title = $_POST["title"];
                }

                if (isset($_POST["birthdate"])) {
                    $birthdate = $_POST["birthdate"];
                }

                if (isset($_POST["role"]) && $loggedUser->getUserId() !== $user->getUserId()) {
                    $role = $_POST["role"];
                }


                $user->setMail($mail);
                $user->setFullName($fullName);
                $user->setTitle($title);
                $user->setBirthdate(new DateTime($birthdate . " 00:00:00"));
                $user->setRole($role);
            } else {
                $this->redirect('main', "error");
            }

            $errors = $user->validate();
            var_dump($errors);
            if (empty($errors)) {
                $user->update();
            }
        }

        $this->redirect("manageusers");
    }
}
