<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

  require_once "src/Place.php";
  require_once "src/Person.php";
  require_once "src/Thing.php";

  $server = 'mysql:host=localhost:8889;dbname=travel_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server,$username,$password);

  //Same name as file
  class PlaceTest extends PHPUnit_Framework_TestCase
  {

    function test_getAll()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "London";
      $test_place2 = new Place($name2);
      $test_place2->save();
      //Act
      $result = Place::getAll();
      //Assert
      $this->assertEquals([$test_place1, $test_place2], $result);
    }

    function test_deleteAll()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "London";
      $test_place2 = new Place($name2);
      $test_place2->save();
      Place::deleteAll();
      //Act
      $result = Place::getAll();
      //Assert
      $this->assertEquals([], $result);
    }

    function test_getId()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      //Act
      $all_people = Place::getAll();
      $expected = $test_place1->getId();
      $result = $all_people[0]->getId();

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_update()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();
      $new_name = "London";

      //Act
      $test_place1->update($new_name);
      $expected = $test_place1->getName();
      $result = $new_name;

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_delete()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();
      $place_id = $test_place1->getId();

      $name1 = "Paris";
      $test_person1 = new Person($name1, $place_id);
      $test_person1->save();

      //Act
      $test_place1->delete();
      $this->assertEquals([], Person::getAll());
    }

    function test_find()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "London";
      $test_place2 = new Place($name2);
      $test_place2->save();

      //Act
      $id = $test_place1->getId();
      $result = Place::find($id);

      //Assert
      $this->assertEquals($test_place1, $result);
    }

    function test_getPeople()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();
      $place_id = $test_place1->getId();

      $name1 = "Paris";
      $test_person1 = new Person($name1, $place_id);
      $test_person1->save();

      $name2 = "London";
      $test_person2 = new Person($name2, $place_id);
      $test_person2->save();

      //Act
      $result = $test_place1->getPeople();
      $expected = array($test_person1, $test_person2);

      //Assert
      $this->assertEquals($result,$expected);
    }

    function test_addThing()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $thingName1 = "Sight-seeing";
      $test_thing1 = new Thing($thingName1);
      $test_thing1->save();

      $test_place1->addThing($test_thing1);
      //Act
      $result = $test_place1->getThings();
      $expected = array($test_thing1);

      //Assert
      $this->assertEquals($result, $expected);
    }

    function test_getThings()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $thingName1 = "Sight-seeing";
      $test_thing1 = new Thing($thingName1);
      $test_thing1->save();

      $thingName2 = "Photos";
      $test_thing2 = new Thing($thingName2);
      $test_thing2->save();

      $test_place1->addThing($test_thing1);
      $test_place1->addThing($test_thing2);

      //Act
      $result = $test_place1->getThings();
      $expected = array($test_thing1, $test_thing2);

      //Assert
      $this->assertEquals($result, $expected);
    }

    protected function tearDown()
    {
      Place::deleteAll();
      Person::deleteAll();
      Thing::deleteAll();
    }
  }
 ?>
