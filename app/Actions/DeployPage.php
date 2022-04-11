<?php

namespace App\Actions;

use App\Concerns\EstablishesConnections;
use App\Models\Page;
use App\Models\PremiumPages;
use League\Flysystem\Filesystem;

class DeployPage
{
    use EstablishesConnections;

    public function deploy(Page $page)
    {
        $filesystem = $this->createFilesystem($page);

        $filesystem->createDir($page->slug);

        $html = view('pages.show', [
            'content' => $page->content,
            'link' => $page->affiliate_link,
            'button_text' => $page->template->button_text,
            'custom_code' => $page->connection->custom_code,
            'link_custom_code' => $page->connection->link_custom_code,
        ])->render();
        $filesystem->put($page->slug . '/index.php', $html);
    }
    public function deployPremium(PremiumPages $premiumpage)
    {

        $filesystem1 = $this->createFilesystem1($premiumpage);
        $filesystem1->createDir($premiumpage->slug);
        $html = view('premiumpages.show', [
            'content' => $premiumpage->content,
            'meta_title'   => $premiumpage->header_text,
            'custom_code' => $premiumpage->connection->custom_code,
            'link_custom_code' => $premiumpage->connection->link_custom_code,
        ])->render();
        $filesystem1->put($premiumpage->slug . '/index.php', $html);
    }

    public function delete(Page $page)
    {
        $filesystem = $this->createFilesystem($page);

        $filesystem->deleteDir($page->slug);
    }

    protected function createFilesystem(Page $page): Filesystem
    {
        return new Filesystem($this->createAdapter($page->connection));
    }

    public function deletePremium(PremiumPages $premiumpage)
    {
        $filesystem1 = $this->createFilesystem1($premiumpage);

        $filesystem1->deleteDir($premiumpage->slug);
    }

    protected function createFilesystem1(PremiumPages $premiumpage): Filesystem
    {
        return new Filesystem($this->createAdapter($premiumpage->connection));
    }
}
