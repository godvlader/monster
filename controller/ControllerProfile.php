<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

/*  view profile : permet à l'utilisateur de visualiser ses données signalétiques.
    (afficher les attrebuts de l'objet currant User)
    edit profile : permet à l'utilisateur de modifier ses données signalétiques
    (les données de la table user, à l'exception de la clef primaire et des colonnes
    RegisteredAt et Password).
    change password : permet à l'utilisateur de modifier son mot de passe. Lorsque
    l'utilisateur courant est un admin, ce UC lui permet de modifier le mot de passe
    de n'importe quel utilisateur.
*/
class ControllerProfile extends Controller
{

    static int $first_time = 0;

    public function index()
    {
        $this->profile();
    }

    //profil de l'utilisateur connecté ou donné

    /**
     * @throws Exception
     */
    public function profile()
    {
        $loggedUser = $this->get_user_or_redirect();
        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $user       = array_key_exists('param1', $_GET) && $loggedUser->isAdmin() ?
            User::getUserByUserId($_GET['param1']) : $loggedUser;

        if (is_null($user)) {
            $user = $loggedUser;
        }
        (new View("profile"))->show(array("loggedUser" => $loggedUser, "user" => $user)); //show may throw Exception
    }

    public function change_password()
    {
        /** @var User $loggedUser */
        $loggedUser            = $this->get_user_or_redirect();
        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $userId                = array_key_exists('param1', $_GET) && $loggedUser->isAdmin() ?
            $_GET['param1'] : $loggedUser->getUserId();
        $errors                = [];
        $success = array_key_exists('param2', $_GET) && $_GET['param2'] === 'ok' ?
            "Your password has been successfully changed." : '';
        $askForCurrentPassword = false;


        // Determine the user for whom we want to change the password
        if (!$userToChangePasswordFor = User::getUserByUserId($userId)) {
            $this->redirect("manageusers");
        }

        // Make sure that a non-admin doesn't modify the password of other users
        if (!$loggedUser->isAdmin() && $loggedUser->getUserId() !== $userToChangePasswordFor->getUserId()) {
            $this->redirect("manageusers");
        }

        // Ask for the current password if the password of the connected is to be changed
        if ($userToChangePasswordFor->getUserId() === $loggedUser->getUserId()) {
            $askForCurrentPassword = true;
        }

        // If the form has been submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate the entered passwords
            if (
                array_key_exists('newPassword', $_POST)
                && array_key_exists('confirmPassword', $_POST)
            ) {

                $errors = User::validate_passwords($_POST["newPassword"], $_POST["confirmPassword"]);

                // If the connected user is updated, also verify the current password
                if ($askForCurrentPassword && array_key_exists('currentPassword', $_POST)) {
                    if (!User::check_password($_POST["currentPassword"], $userToChangePasswordFor->getPassword())) {
                        $errors[] = "The current password is not correct.";
                    }
                }
                // If passwords are valid, update user
                if (empty($errors)) {
                    $userToChangePasswordFor->setPassword($_POST["newPassword"]);
                    $userToChangePasswordFor->update();
                    $this->redirect("profile", "change_password", $userToChangePasswordFor->getUserId(), "ok");
                }
            } else {
                $this->redirect('main', "error");
            }
        }

        (new View("change_password"))->show([
            "user"           => $userToChangePasswordFor,
            "errors"         => $errors,
            "success"        => $success,
            "askForPassword" => $askForCurrentPassword,
            "loggedUser"     => $loggedUser
        ]);
    }


    /**
     * @return void
     * @throws Exception
     * keys for this function {mail, fullName, title, birthdate, role}.
     */
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
            ) {
                $mail = $_POST["mail"];
                $fullName = $_POST["fullName"];
                $title = $_POST["title"];
                $birthdate = $_POST["birthdate"];
            } else {
                $this->redirect('main', "error");
            }

            $user->setMail($mail);
            $user->setFullName($fullName);
            $user->setTitle($title);
            $user->setBirthdate(new DateTime($birthdate . " 00:00:00"));


            $errors = $user->validate();

            if (empty($errors)) {
                $user->update();
                $this->redirect("profile", "edit_profile", $user->getUserId(), "ok");
            }
        }


        (new View("edit_profile"))->show([
            "user"       => $user,
            "errors"     => $errors,
            "success"    => $success,
            "loggedUser" => $loggedUser
        ]);
    }
}
