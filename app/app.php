<?php
  //load modules
  require_once __DIR__."/../vendor/autoload.php";
  require_once __DIR__."/../src/Person.php";
  require_once __DIR__."/../src/Place.php";
  use Symfony\Component\HttpFoundation\Request;
  Request::enableHttpMethodParameterOverride();

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
    $new_person = new Person($_POST['person_name'],$_POST['place_id']);
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

  $app->get("/place_detail/{id}", function($id) use ($app) {
    $this_place = Place::find($id);
    return $app['twig']->render('place_detail.html.twig', array('place' => $this_place, 'people'=>$this_place->getPeople()));
  });

  $app->get("/place_edit/{id}", function($id) use ($app) {
    $this_place = Place::find($id);
    return $app['twig']->render('place_edit.html.twig', array('place' => $this_place));
  });

  $app->patch("/place_edit/{id}", function($id) use ($app) {
    $this_place = Place::find($id);
    $new_name = $_POST['name'];
    $this_place->update($new_name);
    return $app['twig']->render('place_detail.html.twig', array('place' => $this_place, 'people'=>$this_place->getPeople()));
  });

  $app->delete("/place_edit/{id}", function($id) use ($app) {
    $this_place = Place::find($id);
    $this_place->delete();
  return $app['twig']->render('all_places.html.twig', array('places' => Place::getAll()));
  });

//   $app->post("/add_stuffs", function() use ($app) {
//     $place = Category::find($_POST['place_id']);
//     $stuff = Task::find($_POST['stuff_id']);
//     $place->addTask($stuff);
//     return $app['twig']->render('place.html.twig', array('place' => $place, 'categories' => Category::getAll(), 'stuffs' => $place->getTasks(), 'all_stuffs' => Task::getAll()));
// });

  return $app;
 ?>
