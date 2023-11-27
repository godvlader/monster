<?php

// manage places : permet de visualiser la liste des localisations disponibles au sein de l'application,
// en indiquant pour chaque localisation le nombre d'expériences qui la référencent.

require_once 'model/Skill.php';
require_once 'model/User.php';
require_once 'model/Place.php';
require_once 'model/Mastering.php';

class ControllerPlace extends  Controller
{

    public function index()
    {
        $user = $this->get_user_or_redirect();
        if ($user->isAdmin()) {
            self::places();
        } else {
            $this->redirect("profile");
        }
    }

    public function places()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;
        $places = Place::getPlaces();

        (new View("manage_places"))->show(["loggedUser" => $loggedUser, "user" => $user, "places" => $places]);
    }


    public function add(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;
        $errors     = [];
        $success    = array_key_exists('param1', $_GET) && $_GET['param1'] === 'ok' ?
            "Place has been successfully inserted." : "";
        $place = null;
        $places = Place::getPlaces();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedUser->isAdmin()) {

            if (array_key_exists('name', $_POST) && array_key_exists('city', $_POST)) {
                $name = $_POST['name'];
                $city = $_POST['city'];
                $place = new Place($name, $city);
                $errors = $place->validate();
            } else {
                $this->redirect('main', "error");
            }


            if (empty($errors)) {
                $place->update();
                $this->redirect("place", "add", "ok");
            }
        }

        (new View('manage_places'))->show([
            "user"    => $user,
            "places"  => $places,
            "errors"  => $errors,
            "success" => $success,
            "loggedUser" => $loggedUser
        ]);
    }

    public function edit()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;
        $errors     = [];
        $success    = array_key_exists('param1', $_GET) && $_GET['param1'] === 'ok' ?
            "Place has been successfully changed." : "";
        $place = array_key_exists('id', $_POST) ? Place::getPlaceById($_POST['id']) : null;
        $places = Place::getPlaces();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedUser->isAdmin()) {

            if (array_key_exists('name', $_POST) && array_key_exists('city', $_POST)) {
                $name = $_POST['name'];
                $city = $_POST['city'];
                if ($place->getName() === $name && $place->getCity() === $city) {
                    $this->redirect("place");
                }
                $place->setName($name);
                $place->setCity($city);
                $errors = $place->validate();
            } else {
                $this->redirect('main', "error");
            }


            if (empty($errors)) {
                $place->update();
                $this->redirect("place", "edit", "ok");
            }
        }

        (new View('manage_places'))->show([
            "user"    => $user,
            "places"  => $places,
            "errors"  => $errors,
            "success" => $success,
            "loggedUser" => $loggedUser
        ]);
    }

    public function delete(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedUser->isAdmin()) {
            $place = null;

            if (array_key_exists('id', $_POST)) {
                $place = Place::getPlaceById($_POST['id']);
            } else {
                $this->redirect('main', "error");
            }

            if ($place != null) {
                $place->delete();
                $this->redirect("place");
            }
        } else {
            $this->redirect('main', "error");
        }
    }

    public function delete_confirm(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $loggedUser->isAdmin()) {
            $place = null;

            if (array_key_exists('param1', $_GET) && is_numeric($_GET['param1'])) {
                $place = Place::getPlaceById($_GET['param1']);
            } else {
                $this->redirect('main', "error");
            }

            if ($place != null) {
                (new View("delete_place"))->show(["loggedUser" => $loggedUser, "place" => $place, "user" => $user]);
            }
        } else {
            $this->redirect('main', "error");
        }
    }
}
