<?php
  //load modules
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Person.php";
  require_once __DIR__."/../src/Place.php";

  $app = new Silex\Application();

  //Instantiate new PDO connection
  $server = 'mysql:host=localhost:8889;dbname=travel';
  $username = 'root';
  $password = 'root';
  $DB = new PDO($server,$username,$password);

  //Instantiate twig template
  $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
  ));

  //get routes
  $app->get("/", function() use ($app) {
    return $app['twig']->render('index.html.twig');
  });

  //people
  $app->get("/all_people", function() use ($app) {
    return $app['twig']->render('all_people.html.twig', array('people' => Person::getAll()));
  });

  $app->post("/create_person", function() use ($app) {
    $new_person = new Person($_POST['person_name']);
    $new_person->save();
    return $app['twig']->render('create_person.html.twig', array('newPerson' => $new_person));
  });

  $app->post("/delete_people", function() use($app){
    Person::deleteAll();
    return $app['twig']->render('delete_people.html.twig');
  });

  //places
  $app->get("/all_places", function() use ($app) {
    return $app['twig']->render('all_places.html.twig', array('places' => Place::getAll()));
  });

  $app->post("/create_place", function() use ($app) {
    $new_place = new Place($_POST['place_name']);
    $new_place->save();
    return $app['twig']->render('create_place.html.twig', array('newPlace' => $new_place));
  });

  $app->post("/delete_places", function() use($app){
    Place::deleteAll();
    return $app['twig']->render('delete_places.html.twig');
  });


  return $app;
 ?>
