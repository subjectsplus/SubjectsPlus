<?php

namespace App\Service;

class EmbedService
{
    public function getEmbedLink(string $mediaSource, string $mediaType): string
    {
        // Extract the video id from source url and parse new embed link
        if ($mediaType === 'vimeo') {
            $vimeoRegex = "/.+\/(\d+)/";
            $embedLink = '//player.vimeo.com/video/';

            \preg_match($vimeoRegex, $mediaSource, $matches);
            
            if ($matches[1]) {
                return  $embedLink . $matches[1];
            }
        } else if ($mediaType === 'youtube') {
            $youtubeRegex = "/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/|shorts\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/";
            $embedLink = '//www.youtube.com/embed/';

            \preg_match($youtubeRegex, $mediaSource, $matches);

            if ($matches[1]) {
                return $embedLink . $matches[1];
            }
        } else if ($mediaType == 'kaltura') {
            $embedLink = '//cdnapisec.kaltura.com/p/1332041/sp/133204100/embedIframeJs/uiconf_id/25208101/partner_id/1332041?iframeembed=true&playerId=kplayer&entry_id={kaltura_ref_id}&flashvars[streamerType]=auto';

            $mediaSourceSplit = explode('/', $mediaSource);
        	
        	if (isset($mediaSourceSplit[5])) {
        		$kalturaRefId= $mediaSourceSplit[5];
                return \str_replace('{kaltura_ref_id}', $kalturaRefId, $embedLink);
            }
        }

        return $mediaSource;
    }
}