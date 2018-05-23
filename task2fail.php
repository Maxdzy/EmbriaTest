<?php

/**
 * run php task2.php
 */

$dbHost = 'localhost';
$dbPort = 33060;
$dbName = 'homestead';
$dbUser = 'homestead';
$dbPass = 'secret';
$tmpTableName = 'email_domains_' . rand(1, 100);
$step = 10000;

try {
    $dbh = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName}", $dbUser, $dbPass);
    $createEmailDomainsTable = "CREATE TABLE {$tmpTableName} ENGINE=MyISAM as (SELECT TRIM(REPLACE(REPLACE(email, ',', ';'), ' ', '')) as emails FROM users WHERE email <> '');";
    $dbh->query($createEmailDomainsTable);
    echo 'create ' . $tmpTableName . ' table' . PHP_EOL;

    $result = [];
    $start = 0;
    do {
        $limit = $start . ',' . $step;
        $selectEmails = $dbh->query("SELECT * FROM {$tmpTableName} LIMIT {$limit}");

        $emails = [];
        foreach ($selectEmails as $key => $row) {
            $emails[] = array_map(function ($val) {
                $domain = substr(strrchr($val, '@'), 1);
                return $domain;
            }, (explode(';', $row['emails'])));
        }

        foreach ($emails as $email) {
            $email = array_diff($email, ['']);
            $countDomain = array_count_values(array_keys(array_flip($email)));

            foreach ($countDomain as $domain => $count) {
                if (isset($result[$domain])) {
                    $result[$domain] += $count;
                } else {
                    $result[$domain] = $count;
                }
            }
        }

        $start += $step;
    } while (count($emails));

    echo 'result: ' . PHP_EOL;
    print_r($result);

    echo 'drop ' . $tmpTableName . ' table' . PHP_EOL;
    $dbh->query("DROP TABLE {$tmpTableName}");

    //seeder
    /*$dmns = ['gmail.com', 'mail.ru', 'rambler.ru', 'yahoo.com'];
    for ($i = 0; $i < 100000000; $i++) {
        $name = 'name' . time() . rand(0, 100);
        $gender = rand(0, 1);
        $email = '';
        for ($j = 0; $j < rand(0, 10); $j++) {
            $email .= $name . '@' . $dmns[rand(0, 3)] . ', ';
        }
        $dbh->query('INSERT INTO users (`name`, `gender`, `email`) VALUES ("' . $name . '", "' . $gender . '", "' . $email . '");');
    }*/

    $dbh = null;
} catch (PDOException $e) {
    echo '=( error connect';
}

