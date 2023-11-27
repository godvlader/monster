<?php

require_once "framework/Model.php";

class Using extends Model{

    public int $experience;
    public int $skill;

    /**
     * @param int $experience
     * @param int $skill
     */
    public function __construct(int $experience, int $skill)
    {
        $this->experience = $experience;
        $this->skill = $skill;
    }
  
    public function validate() {}

    public function update() {}

    public function delete() {}

    /**
     * @return int
     */
    public function getExperience(): int
    {
        return $this->experience;
    }
   
    /**
     * @return int
     */
    public function getSkill(): int
    {
        return $this->skill;
    }

    /**
     * @param int $skill
     */
    public function setSkill(int $skill): void
    {
        $this->skill = $skill;
    }

    /**
     * @param int $experience
     */
    public function setExperience(int $experience): void
    {
        $this->experience = $experience;
    }
}

?>

