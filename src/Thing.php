<?php
    class Thing
    {
      //property
      private $thingName;
      private $id;

      //constructor
      function __construct($thingName, $id = null)
      {
        $this->thingName = $thingName;
        $this->id = $id;
      }

      function getName()
      {
        return $this->thingName;
      }

      function setName($new_thingName)
      {
        $this->thingName = (string) $new_thingName;
      }

      function getId()
      {
        return $this->id;
      }

      function save()
      {
        $executed = $GLOBALS['DB'] -> exec("INSERT INTO things (thingName) VALUES ('{$this->getName()}');");
        if ($executed) {
          $this->id = $GLOBALS['DB']->lastInsertId();
            return true;
          } else {
            return false;
          }
      }

      function update($new_name)
      {
        $executed = $GLOBALS['DB'] -> exec("UPDATE things SET thingName='{$new_name}' WHERE id = {$this->getId()};");
        if ($executed) {
          $this->setName($new_name);
          return true;
        } else {
          return false;
        }
      }

        function delete()
        {
          $executed = $GLOBALS['DB'] -> exec("DELETE FROM things WHERE id = {$this->getId()}");
          if ($executed)
          {
            return true;
          } else {
            return false;
          }
        }

        function addPlace($place)
        {
          $executed = $GLOBALS['DB']->query("INSERT INTO places_things (place_id, thing_id) VALUES ({$place->getId()},{$this->getId()});");
          if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        function getPlaces()
        {
          $query = $GLOBALS['DB']->query("SELECT place_id FROM places_things WHERE thing_id ={$this->getId()};");
          $place_ids = $query->fetchAll(PDO::FETCH_ASSOC);

          $places = array();
          foreach($place_ids as $id){
            $place_id = $id['place_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM places WHERE id={$place_id};");
            $return_place = $result->fetchAll(PDO::FETCH_ASSOC);

            $name = $return_place[0]['placeName'];
            $id = $return_place[0]['id'];
            $new_place = new Place($name, $id);
            array_push($places, $new_place);
          }
          return $places;
        }

        static function getAll()
        {
          $dtb_things = $GLOBALS['DB']->query("SELECT * FROM things;");
          $local_things = array();
          foreach ($dtb_things as $thing) {
            $new_thingName = $thing['thingName'];
            $new_id = $thing['id'];
            $new_thing = new Thing($new_thingName,$new_id);
            array_push($local_things, $new_thing);
          }

          return $local_things;
        }

        static function deleteAll()
        {
          $executed = $GLOBALS['DB']->query("DELETE FROM things;");
          if ($executed) {
              return true;
            } else {
              return false;
            }
        }

        static function find($search_id)
        {
          $found_thing = $GLOBALS['DB']->prepare("SELECT * FROM things WHERE id = :id");
          $found_thing->bindParam(':id', $search_id, PDO::PARAM_STR);
          $found_thing->execute();
          foreach ($found_thing as $thing) {
            $new_thingName = $thing['thingName'];
            $new_id = $thing['id'];
            if ($new_id == $search_id)
            {
              $found_thing = new Thing($new_thingName,$new_id);
            }
            return $found_thing;
          }
        }

      }
?>
