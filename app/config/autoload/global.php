<?php
use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
     'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'db',                    
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'contest',
                ]
            ],            
        ]    
    ],  
];
