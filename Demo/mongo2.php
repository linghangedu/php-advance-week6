<?php

/*
 * Copyright (c) 2015 ObjectLabs Corporation
 * Distributed under the MIT license - http://opensource.org/licenses/MIT
 *
 * Written with extension mongo 1.5.2
 * Documentation: http://php.net/mongo
 * A PHP script connecting to a MongoDB database given a MongoDB Connection URI.
 */

// Create seed data
$seedData = array(
    array(
        'decade' => '1970s',
        'artist' => 'Debby Boone',
        'song' => 'You Light Up My Life',
        'weeksAtOne' => 10
    ),
    array(
        'decade' => '1980s',
        'artist' => 'Olivia Newton-John',
        'song' => 'Physical',
        'weeksAtOne' => 10
    ),
    array(
        'decade' => '1990s',
        'artist' => 'Mariah Carey',
        'song' => 'One Sweet Day',
        'weeksAtOne' => 16
    ),
);



/*
 * You have to start you local mongoDB server, by enter ">mongod" in terminal, first to connect it.
 */
$client = new MongoClient();
$db = $client->selectDB("mydb");

/*
 * First we'll add a few songs. Nothing is required to create the songs
 * collection; it is created automatically when we insert.
 */
$songs = $db->songs;

// To insert a dict, use the insert method.
$songs->batchInsert($seedData);

/*
 * Then we need to give Boyz II Men credit for their contribution to
 * the hit "One Sweet Day".
*/
$songs->update(
    array('artist' => 'Mariah Carey'),
    array('$set' => array('artist' => 'Mariah Carey ft. Boyz II Men'))
);

/*
 * Finally we run a query which returns all the hits that spent 10
 * or more weeks at number 1.
*/
$query = array('weeksAtOne' => array('$gte' => 10));
$cursor = $songs->find($query)->sort(array('decade' => 1));

foreach($cursor as $doc) {
    echo 'In the ' .$doc['decade'];
    echo ', ' .$doc['song'];
    echo ' by ' .$doc['artist'];
    echo ' topped the charts for ' .$doc['weeksAtOne'];
    echo ' straight weeks.', "\n";
}

// Since this is an example, we'll clean up after ourselves.
$songs->drop();

// Only close the connection when your app is terminating
$client->close();

?>
