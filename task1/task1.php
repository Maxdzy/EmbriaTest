<?php
/**
 * php task1.php
 * 
 * add test data for posts
 * add test data for likes
 */

$dbHost = 'localhost';
$dbName = 'tz1_codegist';
$dbUser = 'stm';
$dbPass = 'Good2017';

try {
	$dbh = new PDO("mysql:host={$dbHost};dbname={$dbName}", $dbUser, $dbPass);
	/*
	 * begin add posts
	$categoris = ['news', 'people', 'photo', 'video'];
	for ($i = 0; $i < 150000; $i++) {
		$categori = $categoris[rand(0, 3)];
		$content = 'asfasfa fa dsf sdf'.rand(0, 10000).' sdf sdfs fsd fsd '.rand(0, 10000);
		
		$dbh->query("INSERT INTO `posts` (`id`, `category`, `content`, `created`) VALUES (NULL, '{$categori}', '{$content}', CURRENT_TIMESTAMP);");
	}
	* end add posts
	*/
	
	/*
	 * begin add likes
	 */
	$categoris = ['news', 'people', 'photo', 'video'];
	for ($i = 0; $i < 1000; $i++) {
		$post_id = rand(50000, 51100);
		$user_id = rand(1, 4);
		$dbh->query("INSERT INTO `likes` (`post_id`, `user_id`) VALUES ('{$post_id}', '{$user_id}');");
	}
	
	
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
 
