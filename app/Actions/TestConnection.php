<?php

namespace App\Actions;
use Illuminate\Http\Request;
use App\Concerns\EstablishesConnections;
use App\Models\Connection;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;
use Mautic\Auth\BasicAuth;
class TestConnection
{
    use EstablishesConnections;

    

    public function test(Connection $connection)
    {
        try {
            $adapter = $this->createAdapter($connection);

            $adapter->getConnection();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }

        return true;
    }

    public function testmauitc(Connection $connection){
        $apiUrl = $connection->base_url;

        $settings = array(
             'AuthMethod'       => 'BasicAuth', 
             'userName'         => $connection->username,
             'password'         => $connection->password,         
             'apiUrl'           => $connection->base_url,         
        );
        try {
            // Initiate the auth object specifying to use BasicAuth
            $initAuth = new ApiAuth();
            $auth = $initAuth->newAuth($settings, 'BasicAuth');
            if ($auth) {
                $api = new MauticApi();
                $contactApi = $api->newApi('contacts', $auth, $apiUrl);
                
                // Get Contact list
                $results = $contactApi->getList();
                if(isset($results['errors'])){
                    return 'Could not connect to host: '.$connection->base_url;
                }else{
                    return true;
                }
            } else {
                echo "Something went wrong!";
            }
        } catch (Exception $e) {
            echo "Mautic not connected!";   
        }
    }

    public static function isTestableType($type): bool
    {
        return in_array($type, [Connection::TYPE_FTP, Connection::TYPE_SFTP]);
    }

    public static function isTestableMauticType($type): bool
    {

        return in_array($type, [Connection::TYPE_MAUTIC]);
    }

}
