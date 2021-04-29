<?php

/**
 * Defines constants for use by OpenGraph metatags.
 */
class OpenGraphTypes
{
    // Define Default Type:
    
    const DEFAULT_TYPE = "website";
    
    // Define Music Types:
    
    const MUSIC_SONG = "music.song";
    
    const MUSIC_ALBUM = "music.album";
    
    const MUSIC_PLAYLIST = "music.playlist";
    
    const MUSIC_RADIO_STATION = "music.radio_station";
    
    // Define Video Types:
    
    const VIDEO_MOVIE = "video.movie";
    
    const VIDEO_EPISODE = "video.episode";
    
    const VIDEO_TV_SHOW = "video.tv_show";
    
    const VIDEO_OTHER = "video.other";
    
    // Define Other Types:
    
    const BOOK = "book";
    
    const ARTICLE = "article";
    
    const PROFILE = "profile";
    
    const WEBSITE = "website";
    
    // Define Namespaces:
    
    private static $namespaces = array(
        'og' => 'http://ogp.me/ns',
        'book' => 'http://ogp.me/ns/book',
        'music' => 'http://ogp.me/ns/music',
        'video' => 'http://ogp.me/ns/video',
        'article' => 'http://ogp.me/ns/article',
        'profile' => 'http://ogp.me/ns/profile',
        'website' => 'http://ogp.me/ns/website'
    );
    
    /**
     * Answers the namespace URI for the specified prefix.
     *
     * @param string $prefix
     * @return string
     */
    public static function get_namespace_uri($prefix)
    {
        return isset(self::$namespaces[$prefix]) ? self::$namespaces[$prefix] : null;
    }
}
