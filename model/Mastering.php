<?php

require_once "framework/Model.php";

class Mastering extends Model
{

    public int $user;
    public int $skill;
    public int $level;


    /**
     * @param int $user
     * @param int $skill
     * @param int $level
     */
    public function __construct(int $user, int $skill, int $level)
    {
        $this->user = $user;
        $this->skill = $skill;
        $this->level = $level;
    }

    //PHP USE 
    public function increase()
    {
        if ($this->level < 5) {
            self::execute(
                "UPDATE `mastering` SET 
            `Level`=:Level  WHERE `User`=:User AND `Skill`=:Skill",
                [
                    'Level' => $this->level + 1,
                    'User' => $this->user,
                    'Skill' => $this->skill,
                ]

            );
        }
    }

    //PHP USE 
    public function decrease()
    {
        if ($this->level > 1) {
            self::execute(
                "UPDATE `mastering` SET 
            `Level`=:Level  WHERE `User`=:User AND `Skill`=:Skill",
                [
                    'Level' => $this->level - 1,
                    'User' => $this->user,
                    'Skill' => $this->skill,
                ]

            );
        }
    }


    public function updateLevel($level)
    {
        if ($level > 0 && $level < 6) {
            $query = self::execute(
                "UPDATE `mastering`SET `Level`=:level WHERE `User`=:user AND `Skill`= :skill",
                array(
                    'level' => $level,
                    'user' => $this->user,
                    'skill' => $this->skill
                )
            );
            $data = $query->fetchAll();
            return $data;
        }
    }

    public function validate(): array
    {
        $errors = [];
        if ($this->level < 0 || $this->level > 5) {
            $errors[] = "Level must be included between 0 and 5.";
        }
        if (self::getMasteringById($this->user, $this->skill) != null) {
            $errors[] = "This Skill is already used.";
        }

        return $errors;
    }

    public function update(): Mastering
    {
        self::execute(
            "UPDATE mastering SET 
             Level=:Level WHERE User=:User 
                            AND Skill=:Skill",
            [
                'User' => $this->user,
                'Skill' => $this->skill
            ]
        );

        return $this;
    }

    public function delete(): bool
    {
        self::execute("DELETE FROM `mastering` WHERE  skill = :skill", ["skill" => $this->skill]);

        return true;
    }

    public function insert(): Mastering
    {
        self::execute(
            "INSERT INTO mastering (User, Skill, Level) 
                                         VALUES (:User, :Skill, :Level)",
            [
                'User' => $this->user,
                'Skill' => $this->skill,
                'Level' => $this->level
            ]
        );
        return $this;
    }

    /**
     * @return int|User
     */
    public function getUser()
    {
        return $this->user;
    }
   
    /**
     * @return int|Skill
     */
    public function getSkill()
    {
        return $this->skill;
    } 

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    public function getSkillName(): string
    {
        $skill = Skill::getSkillById($this->skill);
        return $skill->getName();
    }

    public function getMasterSkills()
    {

        $query = self::execute("SELECT s.Name, mastering.level from mastering 
                                join user u on u.ID=mastering.User 
                                JOIN skill s on s.ID=mastering.Skill 
                                where u.ID =:ID", array("ID" => $this->user));
    }

    public static function getMasteringById(int $user, int $skill): ?Mastering
    {
        $query = self::execute("SELECT * FROM mastering where User=:User AND Skill=:Skill", ["User" => $user, "Skill" => $skill]);
        $data = $query->fetch();
        if ($query->rowCount() == 0) {
            return null;
        } else {
            return new Mastering(
                $data["User"],
                $data["Skill"],
                $data["Level"]
            );
        }
    }

    /**
     * @param int|User $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @param int|Skill $skill
     */
    public function setSkill($skill): void
    {
        $this->skill = $skill;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
    }
}
