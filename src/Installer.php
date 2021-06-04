<?php 

namespace Oneofftech\KlinkStreaming;

use Composer\Factory;
use Composer\Script\Event;
use Composer\IO\IOInterface;
use Composer\Downloader\TransportException;

class Installer
{

    /**
     * Download the tus client binary as part of the post-install-cmd script
     */
    public static function downloadTusClient(Event $event)
    {
        $io = $event->getIO();
        
        $io->write('Downloading tus-client binary...');

        $map = ['winnt' => 'windows'];

        $binary_urls = $event->getComposer()->getConfig()->get('tus-client-binary');

        $os = strtolower(PHP_OS);

        if(isset($map[$os])){
            $os = $map[$os];
        }

        if(isset($binary_urls[$os])){

            $fileName = __DIR__ . '/../bin/tus-client' . ($os==='windows' ? '.exe' : '');
            $processedUrl = $binary_urls[$os];

            $rfs = Factory::createHttpDownloader($io, $event->getComposer()->getConfig());

            try {

                $rfs->copy($processedUrl, $fileName);

            } catch (TransportException $e) {
                $io->writeError('');

                $io->writeError('    Download failed', true);
            }

            if($os!=='windows'){
                // making sure the binary is executable
                chmod($fileName, 0755);
            }
        }
        else {
            $io->writeError("Cannot identify the OS. Found $os, expected windows, linux or darwin.", true);
        }

    }

}
