<?php

require_once "framework/Model.php";

class Skill extends Model {

    public ?int $id;
    public string $name;

    /**
     * @param string $name
     * @param int|null $id
     */
    public function __construct(string $name, int $id = NULL) {
        $this->name = $name;
        $this->id= $id;
    }

    public function validate() {
        $errors = [];
        if((!isset($this->name) || $this->name == '' || strlen($this->name) > 128)){
            $errors[] = "Skill name must be between 1 and 128 character";
        }
        if (self::getskillByName($this->name) != null){
            $errors[] = "Skill already exist.";
        }
        return $errors;
    }

    public function addToUsing($skillList)
    {
        $query1 = self::execute("DELETE from `using` where skill = :name", array("name" => $this->name));
        $data = $query1->fetchAll();
        foreach ($skillList as $skill) {
            $query2 = self::execute("INSERT INTO `using` (experience, skill) values (:experience, (SELECT ID FROM skill where ID = :id))", array("id" => $this->id, "experience" => $this->exp->getId()));
            $data = $query2->fetchAll();
        }
        return $data;
    }

    public function delete() : bool{
        self::execute("DELETE FROM `using` WHERE skill = :ID",["ID"=>$this->id]);
        self::execute("DELETE FROM `mastering` WHERE  skill = :skill",["skill"=> $this->id]);
        self::execute("DELETE FROM `skill` WHERE  ID = :ID",["ID"=> $this->id]);
        return true;
    }

    public function update(): Skill {
        if ($this->id != null){
            self::execute("UPDATE skill SET 
                 Name=:Name WHERE ID = :ID",
                [
                    'ID'=>$this->id,
                    'Name'=>$this->name]
            );
        }else {
            self::execute("INSERT INTO skill (Name) 
                                         VALUE (:Name)",
                ['Name'=> $this->name]);
            $skill = self::getSkillById(self::lastInsertId());
            $this->id = $skill->id;
        }
        return $this;
    }

    public function countUsers() : int {
        $query = self::execute("SELECT COUNT(*) FROM user u
        JOIN mastering m on m.User = u.ID
        JOIN skill s on m.Skill = s.ID
        WHERE s.ID =:ID", ["ID" =>  $this->id]);
        return $query->fetch()[0];
    }

    public function countExperience() : int {
        $query = self::execute("SELECT COUNT(*) FROM `using` WHERE skill = :skill", ["skill" =>  $this->id]);
        return $query->fetch()[0];
    }

/*  ALl function needed to Manage skills  */
    public static function getSkills() : array {
        $skills = [];
        $query = self::execute("SELECT * FROM skill",[]);
        $data = $query->fetchAll();
        foreach ($data as $row){
            $skills[] = new Skill($row['Name'],
                $row['ID']
            );
        }

        return $skills;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    public static function getskillByName(string $name) : ?Skill{
        $query = self::execute("SELECT * FROM skill where Name = :Name", ["Name" => $name]);
        $data = $query->fetch();
        if ($query->rowCount() == 0){
            return null;
        }else{
            return new Skill($data["Name"],
                $data["ID"]
            );
        }

    }

    public static function getSkillById(int $id) : ?Skill{
        $query = self::execute("SELECT * FROM skill where ID = :ID", ["ID" => $id]);
        $data = $query->fetch();
        if ($query->rowCount() == 0){
            return null;
        }else{
            return new Skill($data["Name"],
                             $data["ID"]
                            );
        }

    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public static function getSkillsAsJSON() : string{
        $str = "";
        $skills = self::getSkills();

        foreach ($skills as $skill){

            $id   = $skill->id;
            $name = $skill->name;

            $id   = json_encode($id);
            $name = json_encode($name);

            $str .= "{\"id\":$id,
                      \"name\":$name},";
        }

        if ($str !== ""){
            $str = substr($str,0,strlen($str)-1);
        }
        return "[$str]";
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
