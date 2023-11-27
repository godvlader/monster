<?php
class ControllerIni extends Controller{
    public function index(){
        
    }

    public function getMax(){
        $ini_array = parse_ini_file("../prwb_2122_a04/config/dev.ini", true);
        echo json_encode($ini_array['FRONTEND_SETTINGS']);
    }

    public function check_max_service($maxDesc){
        $ini_array = parse_ini_file("../prwb_2122_a04/config/dev.ini", true);
        $max = $ini_array['FRONTEND_SETTINGS']->$maxDesc;
        if(null !== isset($_GET["param1"])){
            $maxDesc = $_GET["param1"];
            if($maxDesc > $max){
                echo "false";
            }
            else{
                echo "true";
            }
        }

    }

}