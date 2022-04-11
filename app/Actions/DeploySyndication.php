<?php

namespace App\Actions;

use App\Models\Content;
use App\Models\Syndication;
use App\Concerns\EstablishesConnections;
use App\Models\SyndicationConnection;
use League\Flysystem\Filesystem;

class DeploySyndication
{
    use EstablishesConnections;

    public function deploy(Syndication $syndication): void
    {
        $syndication->syndicatedConnections->each(function (SyndicationConnection $sc) {
            try {
                $slug = $sc->syndication->slug;
                $filesystem = new Filesystem($this->createAdapter($sc->connection));

                $filesystem->createDir($slug);

                $filesystem->put($slug . '/index.html', $sc->syndication->content->content);

                $sc->forceFill(['was_deployed' => true])->save();
            } catch (\Throwable $e) {
                $sc->forceFill(['was_deployed' => false])->save();
                throw $e;
            }
        });
    }

    public function destroy(Syndication $syndication): void
    {
        $syndication->syndicatedConnections->each(function (SyndicationConnection $sc) {
            $filesystem = new Filesystem($this->createAdapter($sc->connection));

            $filesystem->deleteDir($sc->syndication->slug);
        });
    }
}
