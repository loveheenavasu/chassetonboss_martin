<?php

namespace App\Http\Livewire;

use App\Actions\SendWebhookDummyData;
use App\Actions\TestConnection;
use App\Models\Connection;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;

class ConnectionList extends Component

{
    


    use WithPagination;
    public bool $confirmingConnectionDeletion = false;
    public ?Connection $connectionBeingDeleted = null;

    public array $sendDummyData = [];
    public array $testedConnections = [];
    public array $testedMauticConnections = [];

    public function getConnectionsProperty()
    {
        return Connection::byTool(Tools::current())->paginate(100);
    }

    public function testConnection(Connection $connection, TestConnection $tester)
    {
        
           $this->testedConnections[$connection->id] = $tester->test($connection);
    }

    public function testMauticConnection(Connection $connection, TestConnection $tester)
    {
        // $result = $tester->testmauitc($connection);
        
        $this->testedMauticConnections[$connection->id] = $tester->testmauitc($connection);
    }

    public function sendWebhookDummyData(Connection $connection, SendWebhookDummyData $sender): void
    {
        $sender->send($connection->base_url);
        $this->sendDummyData[$connection->id] = true;
    }

    public function confirmConnectionDeletion(Connection $connection): void
    {
        $this->confirmingConnectionDeletion = true;
        $this->connectionBeingDeleted = $connection;
    }

    public function deleteConnection(): void
    {
        $this->connectionBeingDeleted->delete();

        $this->confirmingConnectionDeletion = false;
    }
}
