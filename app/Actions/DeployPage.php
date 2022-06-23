<?php

namespace App\Actions;

use App\Concerns\EstablishesConnections;
use App\Models\Page;
use App\Models\PremiumPages;
use App\Models\LandingPage;
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

    public function deploylandingpage(LandingPage $landingpage)
    {
        $filesystem2 = $this->createFilesystem2($landingpage);
        $filesystem2->createDir($landingpage->slug);
        $html = view('landingpage.show', [
            'content' => $landingpage->content,
            'style'=>$landingpage->style,
            'link' => $landingpage->affiliate_link,
            'button_text' => $landingpage->landing_template->button_text,
            'custom_code' => $landingpage->connection->custom_code,
            'link_custom_code' => $landingpage->connection->link_custom_code,
        ])->render();

        $filesystem2->put($landingpage->slug . '/index.php', $html);

    }

    public function deleteLandingpage(LandingPage $landingpage)
    {
        $filesystem2 = $this->createFilesystem2($landingpage);

        $filesystem2->deleteDir($landingpage->slug);
    }


    protected function createFilesystem2(LandingPage $landingpage): Filesystem
    {
        return new Filesystem($this->createAdapter($landingpage->connection));
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
