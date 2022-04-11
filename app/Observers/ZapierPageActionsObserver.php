<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Http;

class ZapierPageActionsObserver
{
    public function created(Page $page)
    {
        $this->sendRequest('create', $page);
    }

    public function updated(Page $page)
    {
        $this->sendRequest('update', $page);
    }

    public function deleted(Page $page)
    {
        $this->sendRequest('delete', $page);
    }

    protected function sendRequest(string $action, $page)
    {
        if (!$page->connection->webhook_url) {
            return;
        }

        $response = Http::post($page->connection->webhook_url, [
            'site_url' => $page->connection->base_url,
            'page_url' => $page->full_url,
            'action' => $action,
            'product' => $page->product,
            'affiliate_link' => $page->affiliate_link,
        ]);
    }
}
