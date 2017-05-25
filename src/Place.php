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

      function getPeople()
      {
        //query will return an object
        $people = array();
        $found_people = $GLOBALS['DB'] -> query("SELECT * FROM people WHERE place_id = {$this->getId()};");
        foreach ($found_people as $person) {
          $new_name = $person['name'];
          $new_place_id = $person['place_id'];
          $new_id = $person['id'];
          $new_person = new Person($new_name,$new_place_id,$new_id);
          array_push($people, $new_person);
        }
        return $people;
      }

      function update($new_name)
      {
        //Make sure to keep track of the column name
        $executed = $GLOBALS['DB'] -> exec("UPDATE places SET placeName ='{$new_name}' WHERE id = {$this->getId()};");
        if ($executed) {
          $this->setName($new_name);
          return true;
        } else {
          return false;
        }
      }

      function delete()
      {
        $executed = $GLOBALS['DB'] -> exec("DELETE FROM places WHERE id = {$this->getId()};DELETE FROM places_things WHERE place_id = {$this->getId()};DELETE FROM places_stuffs WHERE place_id = {$this->getId()};DELETE FROM people WHERE place_id = {$this->getId()};");
        if(!$executed){
          return false;
        } else {
          return true;
        }
      }

      function addThing($thing)
      {
        $executed = $GLOBALS['DB']->query("INSERT INTO places_things (place_id, thing_id) VALUES ({$this->getId()}, {$thing->getId()});");
        if ($executed) {
            return true;
          } else {
            return false;
          }
      }

      function getThings()
      {
        $query = $GLOBALS['DB']->query("SELECT thing_id FROM places_things WHERE place_id ={$this->getId()};");
        $thing_ids = $query->fetchAll(PDO::FETCH_ASSOC);

        $things = array();
        foreach($thing_ids as $id){
          $thing_id = $id['thing_id'];
          $result = $GLOBALS['DB']->query("SELECT * FROM things WHERE id={$thing_id};");
          $return_thing = $result->fetchAll(PDO::FETCH_ASSOC);

          $name = $return_thing[0]['thingName'];
          $id = $return_thing[0]['id'];
          $new_thing = new Thing($name, $id);
          array_push($things, $new_thing);
        }

        return $things;
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
