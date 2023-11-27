<?php

require_once "framework/Model.php";
require_once "model/Experience.php";

class Place extends Model{

    private ?int $id;
    private string $name;
    private string $city;

    /**
     * @param int|null $id
     * @param string $name
     * @param string $city
     */
    public function __construct(string $name, string $city, int $id = NULL)
    {
        $this->name = $name;
        $this->city = $city;
        $this->id = $id;
    }

    
    public function validate(): array {
        $errors = [];
        if (!self::validateString($this->name)){
            $errors[]= "Place name must be between 1 and 128 character";
        }
        if (!self::validateString($this->city)){
            $errors[]= "City must be between 1 and 128 character";
        }
        if (self::validate_unicity($this->name, $this->city) != null){
            $errors[] = "Place already exist.";
        }
        return $errors;
    }

    public static function validate_unicity(string $name, string $city): ?Place {
        $query = self::execute("SELECT * FROM place where Name=:Name AND City =:City", ["Name" => $name, "City"=> $city]);
        $data = $query->fetch();
        if ($query->rowCount() == 0){
            return null;
        }else{
            return new Place($data["Name"],
                $data["City"],
                $data["ID"]
            );
        }
    }


    public static function validateString(string $string) : bool {
        if ((!isset($string) || $string == '' || strlen($string) > 128)){
            return false;
        }
        return true;
    }

    public function update(): Place {
        if (self::getPlaceById($this->id)){
            self::execute("UPDATE place SET 
                 Name=:Name,
                 City=:City WHERE ID = :id",
                   [
                       'id'=>$this->id,
                       'Name'=>$this->name,
                       'City'=>$this->city]
            );
        }else {
            self::execute("INSERT INTO place (Name, City) 
                                         VALUES (:Name, :City)",
                                                ['Name'=> $this->name,
                                                 'City'=> $this->city]);
            $place = self::getPlaceById(self::lastInsertId());
            $this->id = $place->id;
        }
        return $this;
    }

    public function delete() : bool{
        $experiences =  Experience::getExperiencesByPlaceID($this->getId());
        if (self::countExperience() != 0){
            foreach ($experiences as $experience){
                $experience->delete();
            }
        }
        self::execute("DELETE FROM place WHERE ID = :ID",["ID" => $this->id]);

        return true;
    }

    public function countExperience() : int {
        $query = self::execute("SELECT COUNT(*) FROM experience WHERE place = :placeId", ["placeId" =>  $this->id]);
        return $query->fetch()[0];
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }




    public static function getPlaces() : array {
        $places = [];
        $query = self::execute("SELECT * FROM place",[]);
        $data = $query->fetchAll();
        foreach ($data as $row){
            $places[] = new Place($row['Name'],
                                  $row['City'],
                                  $row['ID']
                                 );
        }
        return $places;
    }

   


    public static function getPlaceById($id): ?Place {
        $query = self::execute("SELECT * FROM place where ID = :ID", ["ID" => $id]);
        $data = $query->fetch();
        if ($query->rowCount() == 0){
            return null;
        }else{
            return new Place($data["Name"],
                             $data["City"],
                             $data["ID"]
                            );
        }
    }
    
    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

}


?>
