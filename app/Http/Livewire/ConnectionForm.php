<?php

namespace App\Http\Livewire;

use App\Tools;
use App\Models\Connection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ConnectionForm extends Component
{
    public Connection $connection;

    public function rules(): array
    {
        return [
            'connection.tool' => ['required', Rule::in(Tools::all())],
            'connection.name' => ['required', 'string','unique:connections,name'],
            'connection.type' => ['required', Rule::in($this->types())],
            'connection.host' => [Rule::requiredIf(fn () => $this->connection->requiresHost()), 'string'],
            'connection.port' => [Rule::requiredIf(fn () => $this->connection->requiresHost()), 'integer'],
            'connection.username' => [Rule::requiredIf(fn () => $this->connection->requiresUsername()), 'string'],
            'connection.password' => [Rule::requiredIf(fn () => $this->connection->requiresPassword()), 'string'],
            'connection.root_path' => [Rule::requiredIf(fn () => $this->connection->requiresRootPath()), 'string'],
            'connection.base_url' => ['required', 'url'],
            'connection.webhook_url' => ['nullable', 'url'],
            'connection.custom_code' => ['nullable', 'string'],
            'connection.link_custom_code' => ['nullable', 'string'],
        ];
    }

    public function mount(Connection $connection): void
    {
        $this->connection = $connection;

        $this->connection->tool = Tools::current();

        if (! $this->connection->exists) {
            switch ($this->connection->tool) {
                case Tools::REFERER:
                case Tools::SYNDICATION:
                    $this->connection->type = Connection::TYPE_FTP;
                    $this->connection->port = 21;
                    break;
                case Tools::DRIP_FEED:
                    $this->connection->type = Connection::TYPE_WEBHOOK;
                    break;
            }
        }
    }

    public function updatedConnectionType(): void
    {
        $this->clearValidation();
    }

    public function types(): array
    {
        return Connection::typesByTool(Tools::current());
    }

    public function submit(): void
    {
        if($this->connection->id == ''){
            $this->validate();
        }

        $this->connection->save();

        $this->redirectRoute('connections.index');
    }
}
