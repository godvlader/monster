<?php
require_once 'model/Experience.php';
require_once 'model/User.php';

class ControllerExperience extends Controller
{
    public function index()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user       = array_key_exists('param1', $_GET) && $loggedUser->isAdmin() ?
            User::getUserByUserId($_GET['param1']) : $loggedUser;

        if (is_null($user)) {
            $this->redirect('main', "error");
        }

        $experiences = $user->get_Experience();



        (new View("experience"))->show(
            [
                "user" => $user,
                "experiences" => $experiences,
                "loggedUser" => $loggedUser
            ]
        );
    }


    public function skillClick()
    {
        if (isset($_POST['skill'], $_POST['experience'], $_POST['user'])) {
            /** @var User $loggedUser */
            $loggedUser = $this->get_user_or_redirect();
            if ($loggedUser->isAdmin()) {
                $user = array_key_exists('user', $_POST) ? User::getUserByUserId($_POST['user']) : $loggedUser;
            } else {
                $user = $loggedUser;
            }
            if (isset($_POST["experience"], $_POST["skill"])) {
                $thirdLevelexp = $user->thirdLevelValidation("Experience", $_POST["experience"]);
                $thirdLevelUsing = $user->thirdLevelForUsing($_POST["experience"], $_POST["skill"]);
            }


            if (is_null($thirdLevelexp) || is_null($thirdLevelUsing)) {
                $this->redirect('main', "error");
            } else {
                $skill = Skill::getSkillById($_POST['skill']);
                $exp = Experience::get_experience_by_id($_POST['experience']);
                $exp->addToMastering($skill);
                return "success";
            }
        } else {
            $this->redirect('main', "error");
        }
    }
    /**
     * list experiences : permet de visualiser la liste des expériences professionnelles de l'utilisateur.
     * add experience : permet d'ajouter une expérience professionnelle.
     * edit experience : permet de modifier une expérience professionnelle.
     * delete experience : permet de supprimer une expérience professionnelle.
     */


    public function getSliderParameter()
    {
        echo Configuration::get('double_slider');
    }

    public function getSwitchJustValidate()
    {
        echo Configuration::get('just_validate_switch');
    }

    //TODO : Add delete confirmation
    public function delete()
    {
        $loggedUser = $this->get_user_or_redirect();
        if ($loggedUser->isAdmin()) {
            $user = array_key_exists('userId', $_POST) ? User::getUserByUserId($_POST['userId']) : $loggedUser;
        } else {
            $user = $loggedUser;
        }
        //var_dump($user);
        if (isset($_POST["experienceId"])) {
            $thirdLevel = $user->thirdLevelValidation("Experience", $_POST["experienceId"]);
        }

        if (is_null($thirdLevel)) {
            $this->redirect('main', "error");
        } else {
            $exp = $user->getExperienceById($_POST['experienceId']);
            $exp->delete();
            $this->redirect("experience", "index", $user->getUserId());
        }
    }

    public function delete_confirm()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();

        if (!(isset($_GET['param2'], $_GET['param1'])) || !is_numeric($_GET['param1']) || !is_numeric($_GET['param2'])) {
            $this->redirect('main', "error");
        }

        $user = array_key_exists('param2', $_GET) && $loggedUser->isAdmin() ?
            User::getUserByUserId($_GET['param2']) : $loggedUser;

        if (isset($_GET['param1'])) {
            $exp = $user->getExperienceById($_GET['param1']);
            (new View("delete_experience"))->show(array("user" => $user, "experience" => $exp, "loggedUser" => $loggedUser));
        }
    }

    public function edit()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $user = array_key_exists('param2', $_GET) && $loggedUser->isAdmin() ?
            User::getUserByUserId($_GET['param2']) : $loggedUser;
        $errors     = [];

        $experience = array_key_exists('param1', $_GET) && $loggedUser->isAdmin() ? Experience::get_experience_by_id($_GET['param1'])
            : Experience::get_experience_by_id($_POST['experienceId']);
        $user       = $experience != null ? User::getUserByUserId($experience->getUser()) : $loggedUser;
        $places     = Place::getPlaces();
        $skills     = Skill::getSkills();
        $checkList  = [];

        $usedSkills = $experience->getUsedSkills();
        foreach ($usedSkills as $usedSkill) {
            $checkList[] = $usedSkill->getId();
        }

        $matseredSkills = $experience->getMasteringSkills();
        foreach ($matseredSkills as $matseredSkill) {
            $checkList[] = $matseredSkill->getId();
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (
                array_key_exists("experienceId", $_POST) &&
                array_key_exists("start", $_POST) &&
                array_key_exists("stop", $_POST) &&
                array_key_exists("title", $_POST) &&
                array_key_exists("description", $_POST) &&
                array_key_exists("userId", $_POST) &&
                array_key_exists("place", $_POST)
            ) {

                $experience = Experience::get_experience_by_id($_POST["experienceId"]);

                if ($experience !== null) {

                    $start = $_POST["start"];
                    $stop = $_POST["stop"];
                    $title = $_POST["title"];
                    $description = $_POST["description"];
                    $userId = $_POST["userId"];
                    $place = $_POST["place"];

                    if ($start) {
                        $experience->setStart($start);
                    }
                    if ($stop) {
                        $experience->setStop($stop);
                    }
                    if ($title) {
                        $experience->setTitle($title);
                    }
                    if ($description) {
                        $experience->setDescription($description);
                    }
                    if ($place) {
                        $experience->setPlaceID($place);
                    }
                    if ($userId) {
                        $user = User::getUserByUserId($userId);
                    }


                    $errors = $experience->validate();

                    if (empty($errors)) {

                        $experience->update();
                        foreach ($skills as $skill) {
                            if (array_key_exists($skill->getId(), $_POST)) {
                                $skillList[] = $skill;
                            }
                        }
                        if (!empty($skillList)) {
                            $experience->addToUsing($skillList);
                        }
                        $this->redirect("experience", "index", $experience->getUser());
                    }
                }
            }
        }
        (new View("edit_experience"))->show([
            "loggedUser" => $loggedUser,
            "user"       => $user,
            "experience" => $experience,
            "usedSkills" => $checkList,
            "skills"     => $skills,
            "places"     => $places,
            "errors"     => $errors
        ]);
    }

    public function edit_experience_service()
    {
        $loggedUser = $this->get_user_or_redirect();
        if (isset($_GET['param1'])) {
            $this->redirect('main', "error");
        }

        $user = array_key_exists('userId', $_POST) && $loggedUser->isAdmin() ?
            User::getUserByUserId($_POST['userId']) : $loggedUser;


        if (isset($_POST['id']) && isset($_POST['start']) && isset($_POST['stop'])) {
            $exp = Experience::get_experience_by_id($_POST['id']);
            $thirdLevel = $user->thirdLevelValidation("Experience", $_POST["id"]);
            if (is_null($thirdLevel)) {
                $this->redirect('main', "error");
            }

            $startDate = $_POST['start'];
            if (empty($_POST['stop'])) {
                $stopDate = null;
            } else {
                $stopDate = $_POST['stop'];
            }
            $startDate = new DateTime(substr($startDate, 4, 11));
            $startDate = $startDate->format('Y-m-d');
            $stopDate = new DateTime(substr($stopDate, 4, 11));
            $stopDate = $stopDate->format('Y-m-d');

            $exp->setStart($startDate);
            $exp->setStop($stopDate);

            if (!is_null($exp)) {
                $exp->update();
            }
        }
    }

    public function add()
    {
        /** @var User $loggedUser */
        $loggedUser = $this->get_user_or_redirect();
        $errors = [];
        $success = array_key_exists('param2', $_GET) && $_GET['param2'] === 'ok' ?
            "Experience has been successfully added." : "";
        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $user = array_key_exists('param1', $_GET) ? User::getUserByUserId($_GET['param1']) : $loggedUser;

        if ($user == null) {
            $this->redirect('main', "error");
        }
        $places = Place::getPlaces();
        $skills = Skill::getSkills();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (
                array_key_exists("start", $_POST) &&
                array_key_exists("stop", $_POST) &&
                array_key_exists("title", $_POST) &&
                array_key_exists("description", $_POST) &&
                array_key_exists("userId", $_POST) &&
                array_key_exists("place", $_POST)
            ) {

                $start = $_POST["start"];
                $stop = $_POST["stop"];
                $title = $_POST["title"];
                $description = $_POST["description"];
                $userId = $_POST["userId"];
                $place = $_POST["place"];


                $user = User::getUserByUserId($userId);


                if ($user) {
                    $experience = new Experience($start, $title, $description, $user->getUserId(), $place, $stop);
                }

                $errors = $experience->validate();


                if (empty($errors)) {
                    $experience->update();
                    foreach ($skills as $skill) {
                        if (array_key_exists($skill->getId(), $_POST)) {
                            $skillList[] = $skill;
                        }
                    }
                    if (!empty($skillList)) {
                        $experience->addToUsing($skillList);
                    }
                    $this->redirect("experience", "index", $experience->getUser());
                }
            }
        }

        (new View("add_experience"))->show(
            [
                "loggedUser" => $loggedUser,
                "user"       => $user,
                "skills"     => $skills,
                "places"     => $places,
                "errors"     => $errors,
                "success"    => $success
            ]
        );
    }


    public function toIndex()
    {
        $loggedUser = $this->get_user_or_redirect();
        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $user = array_key_exists('param1', $_GET) ? User::getUserByUserId($_GET['param1']) : $loggedUser;

        if ($user == null) {
            $this->redirect('main', "error");
        }
        $user = $user->getUserId();
        $this->redirect("experience", "index", $user);
    }

    public function getFirstDate_service()
    {
        $loggedUser = $this->get_user_or_false();
        if (
            !$loggedUser || isset($_GET['param1']) || !isset($_POST['userforstartdate']) ||
            (isset($_POST['userforstartdate']) && !is_numeric($_POST['userforstartdate']))
        ) {
            $this->redirect('main', "error");
        }

        if (array_key_exists('userforstartdate', $_POST)) {
            $userId = $_POST['userforstartdate'];
        }
        $firstExperienceDate = Experience::getFirstExperienceDate($userId);
        if (!$firstExperienceDate) {
            $this->redirect('main', "error");
        }
        echo $firstExperienceDate;
    }


    public function getFilteredExperienceService()
    {
        $loggedUser = $this->get_user_or_false();
        if (
            !$loggedUser || isset($_GET['param1'])
            || !isset($_POST['user'])
            || (isset($_POST['user']) && !is_numeric($_POST['user']))
        ) {
            $this->redirect('main', "error");
        }

        if (isset($_POST['start'], $_POST['stop'], $_POST['user'])) {

            $user = $_POST['user'];
            $start = $_POST['start'];
            $stop = $_POST['stop'];

            if (empty($start) && empty($stop)) {
                $experiences = Experience::getExperiencesByUserID($user);
            } else {
                $experiences = Experience::getExperienceBetweenDates($user, $start, $stop);
            }

            if (!empty($experiences)) {
                $jsonExps = Experience::getConvertedExperiences($experiences);
                echo $jsonExps;
            }
        }
    }

    public function getExperiencesForCalendar()
    {
        $loggedUser = $this->get_user_or_false();
        if (
            !$loggedUser || isset($_GET['param1'])
            || !isset($_POST['userforcalendar'])
            || (isset($_POST['userforcalendar']) && !is_numeric($_POST['userforcalendar']))
        ) {
            $this->redirect('main', "error");
        }

        if (array_key_exists('userforcalendar', $_POST)) {
            $loggedUser = $this->get_user_or_redirect();
            if ($loggedUser->isAdmin()) {
                $user = array_key_exists('userforcalendar', $_POST) ? User::getUserByUserId($_POST['userforcalendar']) : $loggedUser;
            } else {
                $user = $loggedUser;
            }
        }
        $user = $user->getUserId();
        $experiences = Experience::getExperiencesForCalendar($user, $_POST['start'], $_POST['end']);
        if (!empty($experiences)) {
            echo $experiences;
        }
    }
}
