<?php
session_start();

/**
 *   Main API Access Layer
 *
 *   David Lighty - A00843511
 *   BCIT | Comp 2920
 *
 *   Goal:  Create an API based access layer for multiple front ends using Slim
 *   framework, MongoDB.
 *   Main API Paths:
 *     - User authentication
 *     - Notes CRUD
 */

require 'Slim/Slim.php';
require 'mongo/mongoLayer.php';

use noteAppApi\MongoLayer;
use Slim\Slim;

/**
 * Create API App
 */
Slim::registerAutoloader();
$app = new Slim();

// Login Route
$app->post('/login', 'login');
$app->post('/forgot', 'forgotpw');

// Bing photo
$app->get('/bingphoto', 'bingphoto');

/**
 * Routing
 *
 * Dynamic routing that will map the :collection (notes/users, etc)
 * Those routes that require authentication add the 'authenticate' as a middleware option
 *   ==>  (route,middleware,function)
 */

// Notes Routes
$app->get('/:collection', authorize('user'), '_list');
$app->post('/:collection', authorize('user'), '_create');
$app->get('/:collection/:id', authorize('user'), '_read');
$app->put('/:collection/:id', authorize('user'), '_update');
$app->delete('/:collection/:id', authorize('user'), '_delete');

/**
 * _list
 *
 * Get a list of documents from the mongo db
 */

function _list($collection) {
	$uid    = $_SESSION['user']['_id'];
	$select = array(
		'userid' => $uid,
		'limit'  => (isset($_GET['limit']))?$_GET['limit']:false,
		'page'   => (isset($_GET['page']))?$_GET['page']:false,
		'filter' => (isset($_GET['filter']))?$_GET['filter']:false,
		'regex'  => (isset($_GET['regex']))?$_GET['regex']:false,
		'sort'   => (isset($_GET['sort']))?$_GET['sort']:false
	);

	$data = MongoLayer::getList(
		$collection,
		$select
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _create
 *
 * Insert a new record into the db
 */
function _create($collection) {
	$document           = json_decode(Slim::getInstance()->request()->getBody(), true);
	$document['userid'] = $_SESSION['user']['_id'];
	$data               = MongoLayer::create(
		$collection,
		$document
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _read
 *
 * Find a record in the db
 */
function _read($collection, $id) {
	$data = MongoLayer::read(
		$collection,
		$uid,
		$id
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _update
 *
 * Update a record in the db
 */
function _update($collection, $id) {
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	$data     = MongoLayer::update(
		$collection,
		$id,
		$document
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * _delete
 *
 * Remove a record in the db
 */
function _delete($collection, $id) {
	$data = MongoLayer::delete(
		$collection,
		$id
	);
	header("Content-Type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * Authentication Requirement
 *
 * Routes that require user auth token
 */

/**
 *	Validate email and send email
 */
function forgotpw() {
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	if (!empty($document['email']) && !is_null(MongoLayer::findOne('users', array('email' => $document['email'])))) {
		// Valid user...
		$user = MongoLayer::findOne('users', array('email' => $user['email']));
		// The message
		// message
		$message = '
					<html>
					<head>
					  <title>NoteApp Password Recovery</title>
					</head>
					<body>
					  <p>Here is your password: '.$user['password'].'</p>
					</body>
					</html>
					';

		// Send
		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		$headers .= 'To:'.$user['email']."\r\n";
		$headers .= 'From: NoteApp <pwrecovery@noteapp.com>'."\r\n";

		$to .= $user['email'];

		// subject
		$subject = 'NoteApp Password Recovery Email';

		// Mail it
		mail($to, $subject, $message, $headers);
		header("Content-Type: application/json");
		echo '{"success":{"text":"Email sent."}}';
	} else {
		header("Content-Type: application/json");
		echo '{"error":{"text":"Incorrect email or user does not exist."}}';
	}
}

/**
 * Quick and dirty login function
 */
function login() {
	$document = json_decode(Slim::getInstance()->request()->getBody(), true);
	if (!empty($document['email']) && !empty($document['password'])) {
		if ($document['register']) {
			register($document);
			exit;
		} else {
			$user = MongoLayer::validateUser('users', $document);
			if (!is_null($user)) {
				$_SESSION['user'] = $user;
				unset($user['password']);
				header("Content-Type: application/json");
				echo json_encode($user);
			} else {
				header("Content-Type: application/json");
				echo '{"error":{"text":"Password incorrect or User does not exist."}}';
			}
		}
	} else {
		header("Content-Type: application/json");
		echo '{"error":{"text":"Username and Password are required."}}';
	}
}

function register($user) {
	$isUser = MongoLayer::findOne('users', array('email' => $user['email']));
	if (is_null($isUser)) {
		$newUser = array(
			'email'    => $user['email'],
			'password' => $user['password'],
			'role'     => 'user'
		);
		$data = MongoLayer::create(
			'users',
			$newUser
		);
		$_SESSION['user'] = $data;
		unset($data['password']);
		header("Content-Type: application/json");
		echo json_encode($data);
		exit;
	} else {
		header("Content-Type: application/json");
		echo '{"error":{"text":"Email address already in use."}}';
	}

}

/**
 * authorize
 *
 * Detect our auth token or fail with 401
 */
function authorize($role = "user") {
	return function () use ($role) {
		// Get the Slim framework object
		$app = Slim::getInstance();
		// First, check to see if the user is logged in at all
		if (!empty($_SESSION['user'])) {
			// Next, validate the role to make sure they can access the route
			// We will assume admin role can access everything
			if ($_SESSION['user']['role'] == $role ||
				$_SESSION['user']['role'] == 'admin') {
				//User is logged in and has the correct permissions... Nice!
				return true;
			} else {
				// If a user is logged in, but doesn't have permissions, return 403
				$app->halt(403, 'You shall not pass!');
			}
		} else {
			// If a user is not logged in at all, return a 401
			$app->halt(401, 'You shall not pass!');
		}
	};
}

/**
 * Get the Bing photo of the day
 */
function bingphoto() {
	$bingjson = file_get_contents('http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1');
	header("Content-Type: application/json");
	echo $bingjson;
}

/**
 * Start Api app
 */
$app->run();