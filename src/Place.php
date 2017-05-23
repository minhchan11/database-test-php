<?php
  class Place
  {
      //property
      private $placeName;
      private $id;

      //constructor
      function __construct($placeName, $id = null)
      {
        $this->placeName = $placeName;
        $this->id = $id;
      }

      //getters and setters
      function getName()
      {
        return $this->placeName;
      }

      function setName($new_placeName)
      {
        $this->placeName = (string) $new_placeName;
      }

      function getId()
      {
        return $this->id;
      }

      //Save in database using SQL command
      function save()
      {
        //plural $GLOBALS
        $executed = $GLOBALS['DB'] -> exec("INSERT INTO places (placeName) VALUES ('{$this->getName()}');");
        if ($executed) {
          $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          } else {
            return false;
          }
      }

      static function getAll()
      {
        $dtb_places = $GLOBALS['DB']->query("SELECT * FROM places;");
        $local_places = array();
        foreach ($dtb_places as $place) {
          $new_placeName = $place['placeName'];
          $new_id = $place['id'];
          $new_place = new Place($new_placeName,$new_id);
          array_push($local_places, $new_place);
        }

        return $local_places;
      }

      static function deleteAll()
      {
        $executed = $GLOBALS['DB']->query("DELETE FROM places;");
        if ($executed) {
            return true;
          } else {
            return false;
          }
      }

      static function find($search_id)
      {
        $found_place = $GLOBALS['DB']->prepare("SELECT * FROM places WHERE id = :id");
        $found_place->bindParam(':id', $search_id, PDO::PARAM_STR);
        $found_place->execute();
        foreach ($found_place as $place) {
          $new_placeName = $place['placeName'];
          $new_id = $place['id'];
          if ($new_id == $search_id)
          {
            $found_place = new Place($new_placeName,$new_id);
          }
          return $found_place;
        }
      }

  }
 ?>
