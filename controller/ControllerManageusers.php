<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerManageusers extends Controller
{

    public function index()
    {
        $loggedUser = $this->get_user_or_redirect();
        $user       = $this->get_user_or_false();
        $errors = [];

        if (isset($_GET['param1']) && !is_numeric($_GET['param1'])) {
            $this->redirect('main', "error");
        }
        $skillFilteredBy = array_key_exists('param1', $_GET) ? Skill::getSkillById($_GET['param1']) : false;

        /** @var User $user */
        $skills = $user->getAllSkills();
        //user logged and admin
        if ($this->user_logged() && $user->isAdmin()) {
            if ($skillFilteredBy) {
                $userList = $user->get_users_by_skillId($skillFilteredBy->getId());
            }else if(empty($userList)){
                $userList = $user->get_Users();
            }


            (new View("manage_users"))->show([
                "userList"      => $userList,
                "errors"        => $errors,
                "skills"        => $skills,
                "loggedUser"    => $loggedUser,
                "skillFilteredBy"       => $skillFilteredBy
            ]);

            //user logged not admin
        } else if ($this->user_logged() && !$user->isAdmin()) {
            $this->redirect("profile");
            //user not admin nor logged : redirect login/signup page
        } else {
            (new View("index"))->show();
        }
    }


    public function get_visible_users_service(){
        /** @var  User $user */
        $user = $this->get_user_or_redirect();
        if (isset($_POST["param1"])){
            $this->redirect('main', "error");
        }
        if (array_key_exists('filter',$_POST)){
            $users_json = $user->getUsersAsJSON($_POST['filter']);
        } else{
            $users_json = $user->getUsersAsJSON(array());
        }
        echo $users_json;
    }


    public function filter(){
        if (isset($_POST["filter"]) && !is_numeric($_POST["filter"])){
            $this->redirect('main', "error");
        }
        if($_POST['filter'] == -1){
            $this->redirect("manageusers");
        }
        if(!empty($_POST['filter'])){
            $this->redirect("manageusers","index",$_POST['filter']);
        }else{
            $this->redirect("manageusers");
        }
    }

}

