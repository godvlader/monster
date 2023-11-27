<?php
require_once 'framework/Model.php';
require_once 'model/Place.php';
require_once 'model/Skill.php';
class Experience extends Model
{

    private ?int $id;
    private string $start;
    private ?string $stop;
    private string $title;
    private $description;
    private int $user;
    private int $placeID;

    /**
     * @param string $start
     * @param string $title
     * @param string|null $description
     * @param string $user
     * @param int $placeID
     * @param string|null $stop
     * @param int|null $id
     * @throws Exception
     */

    public function __construct(string $start, string $title, ?string $description, string $user, int $placeID, ?string $stop = NULL, int $id = NULL)
    {
        $this->id = $id;
        $this->start = $start;
        $this->stop = $stop;
        $this->title = $title;
        $this->description = $description;
        $this->user = $user;
        $this->placeID = $placeID;
    }


    public function update() : Experience
    {

        if (!is_null($this->id)){
            $query = self::execute(
                "UPDATE experience SET 
            `Start`=:Start, 
            `Stop`=:Stop,
            Title=:Title,
            `Description`=:Description,
            Place=:Place WHERE ID = :ID",
                array(
                    "ID"          => $this->id,
                    "Start"       => $this->start,
                    "Stop"        => $this->stop,
                    "Title"       => $this->title,
                    "Description" => $this->description,
                    "Place"       => $this->placeID
                )
            );
        }else{
            self::execute(
                "INSERT INTO experience(
                 `Start`,
                 `Stop`,
                 Title,
                 `Description`,
                 `User`,
                 `Place`) 
            VALUES(:Start,
                   :Stop,
                   :Title,
                   :Description,
                   :User,
                   :Place)",
                [
                    "Start" => $this->start,
                    "Stop" => $this->stop === "" ? NULL : $this->stop,
                    "Title" => $this->title,
                    "Description" => $this->description,
                    "User" => $this->user,
                    "Place" => $this->placeID
                ]
            );
            $this->id = self::lastInsertId();
        }

        return $this;
    }

    public function insert()
    {
        $query = self::execute(
            "INSERT INTO `experience` (`Start`, `Stop`, `Title`, `Description`, `User`, `Place`) 
                    VALUES (:star,
                            :sto,
                            :title,
                            :descr,
                            (SELECT u.ID from User u where u.ID = :user),
                            (SELECT p.ID from place p where p.ID = :place))",
            array(
                "star" => $this->start,
                "sto" => $this->stop,
                "title" => $this->title,
                "descr" => $this->description,
                "user" => $this->user,
                "place" => $this->placeID
            )
        );
        $this->setExperienceId();
        return $query->fetch();
    }

    public function delete()
    {
        $query0 = self::execute("DELETE FROM `USING` WHERE Experience = :id", array("id" => $this->id));
        $query1 = self::execute("DELETE FROM `experience` WHERE ID = :id", array("id" => $this->id));
        $data[] = $query0->fetchAll();
        $data[] = $query1->fetchAll();
        return $data;
    }


    public function addToUsing( array $skillList) : void
    {
        $query1 = self::execute("DELETE from `using` where experience = :exp", array("exp" => $this->id));
        $data = $query1->fetchAll();
        foreach ($skillList as $skill) {
            $query2 = self::execute("INSERT INTO `using` (experience, skill) values ((SELECT ID FROM experience where ID = :exp), :skill)", array("exp" => $this->id, "skill" => $skill->getId()));
            $data = $query2->fetchAll();
        }
    }

    public function addToMastering($skill)
    {
        //$query1 = self::execute("DELETE from `using` where experience = :expe", array("expe" => $this->id));
        //$data = $query1->fetchAll();
        $user = User::getUserByUserId($this->user);
        $query2 = self::execute(
            "INSERT INTO mastering (user, skill, `level`) VALUES ((SELECT ID FROM user where ID = :user), (SELECT ID FROM skill WHERE ID = :skill), '1')",
            array("user" => $user->getUserId(), "skill" => $skill->getId())
        );
        $data2 = $query2->fetchAll();
    }

    public function validate(): array
    {
        $errors = [];

        if (isset($this->start) && isset($this->stop)) {
            $errors = self::validateDates($this->start, $this->stop);
        }

        if(isset($this->placeID) && is_null(Place::getPlaceById($this->placeID))){
            $errors[] = "Place doesn't exist.";
        }

        if (!(isset($this->title) && strlen($this->title) > 2 && strlen($this->title) < 129)) {
            $errors[] = "Title must be between 3 and 128 characters.";
        }

        if (!(strlen($this->description) < 129)) {
            $errors[] = "Description must be between 3 and 128 characters.";
        }

        return $errors;
    }

    // @IMRAN have a look if you want to correct commented code
    /** Check with Alex from which date want to start and which is the date to end
     *This pattern check if the year start at 2000 .
     */
    private function validateDate(string $date): bool
    {
        if (preg_match("/^(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            return true;
        }
        return false;
    }

    public function validateDates(string $startDate, ?string $stopDate): array
    {
        $errors = [];
        $today = new DateTime();
        $today = $today->format("Y-m-d");

        if (self::validateDate($startDate) === false) {
            $errors[] = "Start date is invalid.";
        }

        if ($stopDate !== "" && self::validateDate($stopDate) === false) {
            $errors[] = "Stop date is invalid.";
        }
        if ( strlen($stopDate) > 0 && strtotime($stopDate) < strtotime($startDate)){
            $errors[] = "Stop date must be superior or equal to Start date.";
        }
        if (strtotime($stopDate) > strtotime($today) || strtotime($today) < strtotime($startDate)){
            $errors[] = "Start and Stop date can't be in the future";
        }
        return $errors;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function get_experience_by_id(int $id) : ?Experience
    {
        $query = self::execute("SELECT * FROM Experience where ID = :ID", ["ID" => $id]);
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return null;
        } else {
            return new Experience(
                $data["Start"],
                $data["Title"],
                $data["Description"],
                $data["User"],
                $data["Place"],
                $data["Stop"],
                $data["ID"]
            );
        }
    }


    public static function getExperiencesByPlaceID(int $placeId): array
    {
        $result = [];
        $query = self::execute("SELECT * FROM Experience where Place = :place", ["place" => $placeId]);
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return $result;
        } else {
            foreach ($data as $row)
                $result[] = new Experience(
                    $row["Start"],
                    $row["Title"],
                    $row["Description"],
                    $row["User"],
                    $row["Place"],
                    $row["Stop"],
                    $row["ID"]
                );
        }
        return $result;
    }

    public static function getExperiencesByUserID(int $userId): array
    {
        $result = [];
        $query = self::execute("SELECT * FROM Experience where user = :user", ["user" => $userId]);
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return $result;
        } else {
            foreach ($data as $row)
                $result[] = new Experience(
                    $row["Start"],
                    $row["Title"],
                    $row["Description"],
                    $row["User"],
                    $row["Place"],
                    $row["Stop"],
                    $row["ID"]
                );
        }
        return $result;
    }
    //DateTime $start, DateTime $stop, string $title, ?string $description, string $user, int $placeID, int $id = NULL
    public static function getExperienceBetweenDates($user, $start, $stop)
    {
        
        $stop++;
        $start .="-01-01";
        $stop .="-01-01";
        $query = self::execute(
            "SELECT * FROM experience WHERE user =:user GROUP BY ID HAVING (`Stop` BETWEEN :star AND :sto) OR (`Start` BETWEEN :star AND :sto);",
            array("user" => $user, "star" => $start, "sto" => $stop)
        );
        $data = $query->fetchAll();
        $experiences = [];
        foreach ($data as $row) {
            $experiences[] = new Experience(
                $row["Start"],
                $row["Title"],
                $row["Description"],
                $row["User"],
                $row["Place"],
                $row["Stop"],
                $row["ID"]
            );
        }
        return $experiences;
    }

    public static function getExperiencesForCalendar($user, $start, $end)
    {   

        $experiences = self::getExperienceBetweenDates($user,$start, $end );  
        $id = null;
        $converted = [];
        if ($experiences) {
            foreach ($experiences as $exp) {
                $id = $exp->getId();
                $start = $exp->start;
                $start = new DateTime($start);
                $start = $start->format('Y-m-d');
                if (!is_null($exp->stop)) {
                    $stop = $exp->stop;
                    $stop = new DateTime($stop);
                    $stop = $stop->format('Y-m-d');
                } else {
                    $stop = new DateTime();
                    $stop = $stop->format('Y-m-d');
                }
                $title = $exp->title;
                $title = $title." at ".$exp->getPlaceName()." (".$exp->getPlaceCity().")"; 

                $converted[] = ['id'=>$id,'title'=>$title,'start'=>$start,'end'=>$stop];
                
            }
            return json_encode($converted);
        }
    }

    public static function getFirstExperienceDate($userID){
        $query = self::execute("SELECT MIN(`start`) FROM Experience WHERE user = :user",["user"=>$userID]);
        $data = $query->fetch();
        $data = $data[0];
        $converted = [];
        if($data){
            $converted[] = ['date'=>$data];
            $res = json_encode($converted);
            return $res;
        }else
            return null;
    }

    public static function getConvertedExperiences($experiences)
    {
        $converted = "";
        if ($experiences) {
            foreach ($experiences as $exp) {
                $id = json_encode($exp->id);

                $start = new DateTime($exp->start);
                $start = $start->format('M Y');
                $start = json_encode($start);

                if (!is_null($exp->stop)) {
                    $stop = new DateTime($exp->stop);
                    $stop = $stop->format('M Y');
                } else {
                    $stop = $exp->stop;
                }

                $stop = json_encode($stop);

                $title = json_encode($exp->title);
                $description = json_encode($exp->description);
                $user = json_encode($exp->user);
                $placename = json_encode($exp->getPlaceName());
                $city = json_encode($exp->getPlaceCity());
                $mastering = $exp->getMasteringSkills("test");
                $mastering = json_encode($mastering);
                $using = $exp->getUsedSkills();
                $using = json_encode($using);

                $converted .= "{\"id\":$id,\"start\":$start,\"stop\":$stop,\"title\":$title,\"description\":$description,\"user\":$user,\"placename\":$placename,\"city\":$city,\"mastering\":$mastering,\"used\":$using},";
            }
            if (!empty($converted)) {
                $converted = substr($converted, 0, strlen($converted) - 1);
                return "[$converted]";
            }
        }
    }
    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function getStop()
    {
        return $this->stop;
    }

    public function getPlaceName()
    {
        return $this->getPlace()->getName();
    }

    public function getPlaceCity()
    {
        return $this->getPlace()->getCity();
    }


    public function getPlace()
    {
        $query = self::execute("SELECT * FROM Place where ID = :ID", array("ID" => $this->placeID));
        $data = $query->fetchAll();

        foreach ($data as $row) {
            $place = new Place(
                $row['Name'],
                $row['City'],
                $row['ID']
            );
        }
        return $place;
    }


    public function getAllPlaces()
    {
        $query = self::execute("SELECT * FROM Place", array());
        $data = $query->fetchAll();
        foreach ($data as $row) {
            $places[] = new Place(
                $row['Name'],
                $row['City'],
                $row['ID']
            );
        }
        return $places;
    }

    public function getUsedSkills() : ?array
    {
        $skills = [];

        $query = self::execute(
            "SELECT s.name, s.ID from skill s where id
         in(select u.Skill from `using` u where u.experience  = :exp AND (u.skill 
         not in(select m.Skill from mastering m where m.User = :user)))",
            array("user" => $this->user, "exp" => $this->id)
        );

        $data = $query->fetchAll();
        if (!empty($data)){
            foreach ($data as $row) {
                $skills[] = new Skill($row['name'], $row['ID']);
            }
        }

        return $skills;
    }

    public function getMasteringSkills() : array
    {
        $skills = [];
        $query = self::execute(
            "SELECT distinct(s.name), s.ID from skill s where id 
        in(select m.Skill from mastering m where user = :user AND m.Skill 
        in(select skill from `using` where experience = :exp))",
            array("user" => $this->user, "exp" => $this->id)
        );
        $data = $query->fetchAll();
        if (!empty($data)) {
            foreach ($data as $row) {
                $skills[] = new Skill($row['name'], $row['ID']);
            }
        }
        return $skills;
    }

    public function getAllSkills()
    {
        $query = self::execute("SELECT s.name, s.ID from skill s", array());
        $data = $query->fetchAll();
        foreach ($data as $row) {
            $skills[] = new Skill($row['name'], $row['ID']);
        }


        return $skills;
    }


    /**
     * @return int
     */
    public function getUser(): int
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getPlaceID(): int
    {
        return $this->placeID;
    }

    /**
     * @param int $placeID
     */
    public function setPlaceID(int $placeID): void
    {
        $this->placeID = $placeID;
    }


    public function setExperienceId()
    {
        $query = self::execute("SELECT ID FROM EXPERIENCE WHERE ID = :id", array("id" => Model::lastInsertId()));
        $data = $query->fetchAll();
        foreach ($data as $row) {
            $id = $row['ID'];
        }
        $this->setId($id);
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $start
     */
    public function setStart(string $start): void
    {
        $this->start = $start;
    }

    /**
     * @param string|null $stop
     */
    public function setStop(?string $stop): void
    {
        $this->stop = $stop;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}
