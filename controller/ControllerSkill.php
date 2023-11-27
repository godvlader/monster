<?php
require_once 'model/Skill.php';
require_once 'model/User.php';
require_once 'model/Skill.php';
require_once 'model/Mastering.php';
/*
 * manage skills : permet de visualiser la liste des compétences disponibles au sein de l'application,
 * en indiquant pour chaque compétence, sous la forme d'un lien, le nombre d'utilisateurs qui la maîtrisent.
 * Quand on clique sur ce lien en regard d'une compétence, on doit visualiser la liste des utilisateurs
 * qui maîtrisent cette compétence (manage users filtrée sur cette compétence).
 *
 * add skill : permet d'ajouter une nouvelle compétence.
 * edit skill : permet de modifier une compétence.
 * delete skill : permet de supprimer une compétence et les données dépendantes, moyennant une demande de
 * confirmation explicite.
 */

class ControllerSkill extends Controller
{

    public function index()
    {
        /** @var User $user */
        $user = $this->get_user_or_redirect();
        if ($user->isAdmin()) {
            self::skills();
        } else {
            $this->redirect("mastering");
        }
    }

    public function get_skills_service()
    {
        if (isset($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $skills_json = Skill::getSkillsAsJSON();
        echo $skills_json;
    }

    /*============================*/
    /* All Manage Skill functions */
    /*============================*/

    public function skills()
    {
        $loggedUser = $this->get_user_or_redirect();

        $user       = $loggedUser;
        $skills     = Skill::getSkills();
        (new View("manage_skills"))->show([
            "loggedUser" => $loggedUser,
            "user" => $user,
            "skills" => $skills
        ]);
    }


    public function edit()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;
        $errors     = [];
        $success    = array_key_exists('param1', $_GET) && $_GET['param1'] === 'ok' ?
            "Skill name has been successfully updated." : "";
        $skill = array_key_exists('id', $_POST) ? Skill::getSkillById($_POST['id']) : null;
        $skills = Skill::getSkills();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedUser->isAdmin() && !is_null($skill)) {

            if (array_key_exists('name', $_POST)) {
                $name = $_POST['name'];
                if ($skill->getName() === $name) {
                    $this->redirect("skill");
                }
                $skill->setName($name);
                $errors = $skill->validate();
            } else {
                $this->redirect('main', "error");
            }

            if (empty($errors)) {
                $skill->update();
                $this->redirect("skill", "edit", "ok");
            } else {
                $this->redirect('main', "error");
            }
        } else {
            $this->redirect('main', "error");
        }

        (new View('manage_skills'))->show([
            "user"    => $user,
            "skills"  => $skills,
            "errors"  => $errors,
            "success" => $success,
            "loggedUser" => $loggedUser
        ]);
    }


    public function add()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;
        $errors     = [];
        $success    = array_key_exists('param1', $_GET) && $_GET['param1'] === 'ok' ?
            "Skill has been successfully inserted." : "";
        $skill = null;
        $skills = Skill::getSkills();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $loggedUser->isAdmin()) {

            if (array_key_exists('name', $_POST)) {
                $name = $_POST['name'];
                $skill = new Skill($name);
                $errors = $skill->validate();
            }

            if (empty($errors)) {
                $skill->update();
                $this->redirect("skill", "add", "ok");
            }
        }

        (new View('manage_skills'))->show([
            "user"    => $user,
            "skills"  => $skills,
            "errors"  => $errors,
            "success" => $success,
            "loggedUser" => $loggedUser
        ]);
    }

    public function delete_confirm(): void
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = $loggedUser;

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && $loggedUser->isAdmin()) {
            $skill = null;

            if (array_key_exists('param1', $_GET) && is_numeric($_GET['param1'])) {
                $skill = Skill::getSkillById($_GET['param1']);
            } else if (is_null($skill)) {
                $this->redirect('main', "error");
            }

            if ($skill != null) {
                (new View("delete_skill"))->show([
                    "loggedUser" => $loggedUser,
                    "skill" => $skill,
                    "user" => $user
                ]);
            } else {
                $this->redirect("skill");
            }
        } else {
            $this->redirect('main', "error");
        }
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $skill = null;
            var_dump($_POST['id']);

            if (array_key_exists('id', $_POST)) {
                $skill = Skill::getSkillById($_POST['id']);
            }
            var_dump($skill);
            if ($skill != null) {
                $skill->delete();
                $this->redirect("skill", "skills");
            } else {
                $this->redirect('main', "error");
            }
        } else {
            $this->redirect('main', "error");
        }
    }
}
