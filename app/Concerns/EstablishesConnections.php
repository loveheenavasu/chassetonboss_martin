<?php

namespace App\Concerns;

use App\Models\Connection;
use League\Flysystem\Adapter\AbstractFtpAdapter;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Sftp\SftpAdapter;

/**
 * @link https://flysystem.thephpleague.com/v1/docs/adapter/ftp/
 * @link https://flysystem.thephpleague.com/v1/docs/adapter/sftp/
 */
trait EstablishesConnections
{
    public function createAdapter(Connection $connection): AbstractFtpAdapter
    {
        switch ($connection->type) {
            case 'ftp':
                $adapter = $this->createFtpAdapter($connection);
                break;
            case 'sftp':
                $adapter = $this->createSftpAdapter($connection);
                break;
            default:
                throw new \Exception('Connection ' . $connection->type . ' not supported.');
        }

        return $adapter;
    }

    protected function createFtpAdapter(Connection $connection): AbstractFtpAdapter
    {
        return new Ftp([
            'host' => $connection->host,
            'username' => $connection->username,
            'password' => $connection->password,
            'port' => $connection->port,
            'root' => $connection->root_path,
            'passive' => true,
            'ssl' => true,
            'timeout' => 5,
            'ignorePassiveAddress' => false,
        ]);
    }

    protected function createSftpAdapter(Connection $connection): AbstractFtpAdapter
    {
        return new SftpAdapter([
            'host' => $connection->host,
            'username' => $connection->username,
            'password' => $connection->password,
            'root' => $connection->root_path,
            'port' => $connection->port,
            'timeout' => 5,
        ]);
    }
}
