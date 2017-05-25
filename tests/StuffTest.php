<?php

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

 require_once "src/Place.php";
 require_once "src/Person.php";
 require_once "src/Stuff.php";

  $server = 'mysql:host=localhost:8889;dbname=travel_test';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server,$username,$password);

  //Same name as file
  class StuffTest extends PHPUnit_Framework_TestCase
  {

    function test_getAll()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_stuff1 = new Stuff($name1);
      $test_stuff1->save();

      $name2 = "Photos";
      $test_stuff2 = new Stuff($name2);
      $test_stuff2->save();
      //Act
      $result = Stuff::getAll();
      //Assert
      $this->assertEquals([$test_stuff1, $test_stuff2], $result);
    }

    function test_deleteAll()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_stuff1 = new Stuff($name1);
      $test_stuff1->save();

      $name2 = "Photos";
      $test_stuff2 = new Stuff($name2);
      $test_stuff2->save();
      Stuff::deleteAll();
      //Act
      $result = Stuff::getAll();
      //Assert
      $this->assertEquals([], $result);
    }

    function test_getId()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_stuff1 = new Stuff($name1);
      $test_stuff1->save();

      //Act
      $all_people = Stuff::getAll();
      $expected = $test_stuff1->getId();
      $result = $all_people[0]->getId();

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_update()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_stuff1 = new Stuff($name1);
      $test_stuff1->save();
      $new_name = "Photo";

      //Act
      $test_stuff1->update($new_name);
      $expected = $test_stuff1->getName();
      $result = $new_name;

      //Assert
      $this->assertEquals($expected, $result);
    }

    function test_delete()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_stuff1 = new Stuff($name1);
      $test_stuff1->save();

      //Act
      $test_stuff1->delete();
      $this->assertEquals([], Person::getAll());
    }

    function test_find()
    {
      //Arrange
      $name1 = "Sight-seeing";
      $test_stuff1 = new Stuff($name1);
      $test_stuff1->save();

      $name2 = "Photos";
      $test_stuff2 = new Stuff($name2);
      $test_stuff2->save();

      //Act
      $id = $test_stuff1->getId();
      $result = Stuff::find($id);

      //Assert
      $this->assertEquals($test_stuff1, $result);
    }

    function test_addPlace()
    {
      //Arrange
      $name1 = "Paris";
      $test_place1 = new Place($name1);
      $test_place1->save();

      $stuffName1 = "Sight-seeing";
      $test_stuff1 = new Stuff($stuffName1);
      $test_stuff1->save();

      $test_stuff1->addPlace($test_place1);
      //Act
      $result = $test_stuff1->getPlaces();
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

      $stuffName1 = "Sight-seeing";
      $test_stuff1 = new Stuff($stuffName1);
      $test_stuff1->save();

      $test_stuff1->addPlace($test_place1);
      $test_stuff1->addPlace($test_place2);
      //Act
      $result = $test_stuff1->getPlaces();
      $expected = array($test_place1, $test_place2);

      //Assert
      $this->assertEquals($result, $expected);
    }

    protected function tearDown()
    {
      Stuff::deleteAll();
      Person::deleteAll();
      Place::deleteAll();
    }
  }
 ?>
