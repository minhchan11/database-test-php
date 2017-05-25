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
  class ThingTest extends PHPUnit_Framework_TestCase
  {

    function test_getAll()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_thing1 = new Thing($name1);
      $test_thing1->save();

      $name2 = "Photos";
      $test_thing2 = new Thing($name2);
      $test_thing2->save();
      //Act
      $result = Thing::getAll();
      //Assert
      $this->assertEquals([$test_thing1, $test_thing2], $result);
    }

    function test_deleteAll()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_thing1 = new Thing($name1);
      $test_thing1->save();

      $name2 = "Photos";
      $test_thing2 = new Thing($name2);
      $test_thing2->save();
      Thing::deleteAll();
      //Act
      $result = Thing::getAll();
      //Assert
      $this->assertEquals([], $result);
    }

    function test_getId()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_thing1 = new Thing($name1);
      $test_thing1->save();

      //Act
      $all_people = Thing::getAll();
      $expected = $test_thing1->getId();
      $result = $all_people[0]->getId();

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_update()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_thing1 = new Thing($name1);
      $test_thing1->save();
      $new_name = "Photo";

      //Act
      $test_thing1->update($new_name);
      $expected = $test_thing1->getName();
      $result = $new_name;

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_delete()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_thing1 = new Thing($name1);
      $test_thing1->save();

      //Act
      $test_thing1->delete();
      $this->assertEquals([], Person::getAll());
    }

    function test_find()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_thing1 = new Thing($name1);
      $test_thing1->save();

      $name2 = "Photos";
      $test_thing2 = new Thing($name2);
      $test_thing2->save();

      //Act
      $id = $test_thing1->getId();
      $result = Thing::find($id);

      //Assert
      $this->assertEquals($test_thing1, $result);
    }

    function test_addPlace()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $thingName1 = "Sight-seeing";
      $test_thing1 = new Thing($thingName1);
      $test_thing1->save();

      $test_thing1->addPlace($test_place1);
      //Act
      $result = $test_thing1->getPlaces();
      $expected = array($test_place1);

      //Assert
      $this->assertEquals($result, $expected);
    }

    function test_getPlaces()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $name2 = "London";
      $test_place2 = new Place($name2);
      $test_place2->save();

      $thingName1 = "Sight-seeing";
      $test_thing1 = new Thing($thingName1);
      $test_thing1->save();

      $test_thing1->addPlace($test_place1);
      $test_thing1->addPlace($test_place2);
      //Act
      $result = $test_thing1->getPlaces();
      $expected = array($test_place1, $test_place2);

      //Assert
      $this->assertEquals($result, $expected);
    }

    protected function tearDown()
    {
      Thing::deleteAll();
      Person::deleteAll();
      Place::deleteAll();
    }
  }
 ?>
