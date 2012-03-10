<?php
/**
 * Torrent
 *
 * PHP version 5 only
 *
 * LICENSE: This source file is subject to version 3 of the GNU GPL
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/licenses/gpl.html.  If you did not receive a copy of
 * the GNU GPL License and are unable to obtain it through the web, please
 * send a note to adrien.gibrat@gmail.com so I can mail you a copy.
 *
 * 1) Features:
 * - Decode torrent file or data
 * - Build torrent from source folder/file(s)
 * - Silent Exception error system
 *
 * 2) Usage example
 * <code>
    require_once 'Torrent.php';
    
    // get torrent infos
    $torrent = new Torrent( './test.torrent' );
    echo '<br>private: ', $torrent->is_private() ? 'yes' : 'no', 
         '<br>annonce: ', $torrent->announce(), 
         '<br>name: ', $torrent->name(), 
         '<br>comment: ', $torrent->comment(), 
         '<br>piece_length: ', $torrent->piece_length(), 
         '<br>size: ', $torrent->size( 2 ),
         '<br>hash info: ', $torrent->hash_info(),
         '<br>stats: ';
    var_dump( $torrent->scrape() );
    echo '<br>content: ';
    var_dump( $torrent->content() );
    echo '<br>source: ',
         $torrent;

    // create torrent
    $torrent = new Torrent( array( 'test.mp3', 'test.jpg' ), 'http://torrent.tracker/annonce' );
    $torrent->send();

    // print errors
    if ( $errors = $torrent->errors() )
        var_dump( $errors );
 * </code>
 *
 * @author     Adrien Gibrat <adrien.gibrat@gmail.com>
 * @copyleft   2008 - Just use it!
 * @license    http://www.gnu.org/licenses/gpl.html GNU General Public License version 3
 * @version    Release: 0.1
 */
class Torrent {

    static public $errors = array();

    /** Read and decode torrent file/data OR build a torrent from source folder/file(s)
     * Supported signatures:
     *  - Torrent( string $torrent );
     *  - Torrent( string $torrent, string $announce );
     *  - Torrent( string $torrent, array  $meta );
     *  - Torrent( string $file_or_folder );
     *  - Torrent( string $file_or_folder, string $announce_url, [int $piece_length] );
     *  - Torrent( string $file_or_folder, array $meta, [int $piece_length] );
     *  - Torrent( array $files_list );
     *  - Torrent( array $files_list, string $announce_url, [int $piece_length] );
     *  - Torrent( array $files_list, array $meta, [int $piece_length] );
     * @param string|array torrent to read or source folder/file(s)
     * @param string|array announce url or meta informations (optional)
     * @param int piece length (optional)
     */
    public function __construct ( $data, $meta = array(), $piece_length = 256 ) 
    {
        if ( $piece_length < 32 || $piece_length > 4096 ) {
            throw new Exception( __( 'Invalid piece lenth, must be between 32 and 4096' ) );
        }
        if ( is_string( $meta ) ) {
            $meta =  array( 'announce' => $meta );
        }
        //if ( $this->build( $data, $piece_length * 1024 ) ) {
            //$this->touch();
        //} else {
            $meta = array_merge( $meta, $this->decode( $data ) );
        //}
        foreach( $meta as $key => $value ) {
            $this->{$key} = $value;
        }
    }

    /** Convert the current Torrent instance in torrent format
     * @return string encoded torrent data
     */
    public function __toString() 
    {
        return $this->encode( $this );
    }

    /** Return Errors
     * @return array|boolean error list or null if none
     */
    public function errors() 
    {
        return empty( self::$errors ) ? 
            false :
            self::$errors;
    }

    /**** Encode BitTorrent ****/

    /** Encode torrent data
     * @param mixed data to encode
     * @return string torrent encoded data
     */
    static protected function encode ( $mixed ) 
    {
        switch ( gettype( $mixed ) ) {
            case 'integer':
            case 'double':
                return self::encode_integer( $mixed );
            case 'object':
            	$mixed = (array) $mixed; //Bugfix by W-Shadow. Objects can't be ksort'ed anyway (see encode_array()).
            case 'array':
                return self::encode_array( $mixed );
            default:
                return self::encode_string( (string) $mixed );
        }
    }

    /** Encode torrent string
     * @param string string to encode
     * @return string encoded string
     */
    static private function encode_string ( $string ) 
    {
        return strlen( $string ) . ':' . $string;
    }

    /** Encode torrent integer
     * @param integer integer to encode
     * @return string encoded integer
     */
    static private function encode_integer ( $integer ) 
    {
        return 'i' . $integer . 'e';
    }

    /** Encode torrent dictionary or list
     * @param array array to encode
     * @return string encoded dictionary or list
     */
    static private function encode_array ( $array ) 
    {
        if ( self::is_list( (array) $array ) ) {
            $return = 'l';
            foreach ( $array as $value ) {
                $return .= self::encode( $value );
            }
        } else {
            ksort( $array, SORT_STRING );
            $return = 'd';
            foreach ( $array as $key => $value ) {
                $return .= self::encode( strval( $key ) ) . self::encode( $value );
            }
        }
        return $return . 'e';
    }

    /** Helper to test if an array is a list
     * @param array array to test
     * @return boolean is the array a list
     */
    static protected function is_list ( $array ) 
    {
        foreach ( array_keys( $array ) as $key ) {
            if ( ! is_int( $key ) ) {
                return false;
            }
        }
        return true;
    }

    /**** Decode BitTorrent ****/

    /** Decode torrent data or file
     * @param string data or file path to decode
     * @return array decoded torrent data
     */
    static protected function decode ( $string ) 
    {
        $data = is_file( $string ) ? 
            file_get_contents( $string ) :
            $string;
        return self::decode_data( $data );
    }

    /** Decode torrent data
     * @param string data to decode
     * @return array decoded torrent data
     */
    static protected function decode_data ( & $data ) 
    {
        switch( self::char( $data ) ) {
        case 'i':
            $data = substr( $data, 1 );
            return self::decode_integer( $data );
        case 'l':
            $data = substr( $data, 1 );
            return self::decode_list( $data );
        case 'd':
            $data = substr( $data, 1 );
            return self::decode_dictionary( $data );
        default:
            return self::decode_string( $data );
        }
    }

    /** Decode torrent dictionary
     * @param string data to decode
     * @return array decoded dictionary
     */
    static private function decode_dictionary ( & $data ) 
    {
        $dictionary = array();
        $previous = null;
        while ( ( $char = self::char( $data ) ) != 'e' ) {
            if ( $char === false ) {
                throw new Exception( __( 'Unterminated dictionary' ) );
            }
            if ( ! ctype_digit( $char ) ) {
                throw new Exception( __( 'Invalid dictionary key' ) );
            }
            $key = self::decode_string( $data );
            if ( isset( $dictionary[$key] ) ) {
                throw new Exception( __( 'Duplicate dictionary key' ) );
            }
            if ( $key < $previous ) {
                throw new Exception( __( 'Missorted dictionary key' ) );
            }
            $dictionary[$key] = self::decode_data( $data );
            $previous = $key;
        }
        $data = substr( $data, 1 );
        return $dictionary;
    }

    /** Decode torrent list
     * @param string data to decode
     * @return array decoded list
     */
    static private function decode_list ( & $data ) 
    {
        $list = array();
        while ( ( $char = self::char( $data ) ) != 'e' ) {
            if ( $char === false )  {
                throw new Exception( 'Unterminated list' );
            }
            $list[] = self::decode_data( $data );
        }
        $data = substr( $data, 1 );
        return $list;
    }

    /** Decode torrent string
     * @param string data to decode
     * @return string decoded string
     */
    static private function decode_string ( & $data ) 
    {
        if ( self::char( $data ) === '0' && substr( $data, 1, 1 ) != ':' ) {
            self::$errors[] = new Exception( 'Invalid string length, leading zero' );
        }
        if ( ! $colon = @strpos( $data, ':' ) ) {
            throw new Exception( 'Invalid string length, colon not found' );
        }
        $length = intval( substr( $data, 0, $colon ) );
        if ( $length + $colon + 1 > strlen( $data ) ) {
            throw new Exception( 'Invalid string, input too short for string length' );
        }
        $string = substr( $data, $colon + 1, $length );
        $data = substr( $data, $colon + $length + 1 );
        return $string;
    }

    /** Decode torrent integer
     * @param string data to decode
     * @return integer decoded integer
     */
    static private function decode_integer ( & $data ) 
    {
        $start  = 0;
        $end    = strpos( $data, 'e');
        if ( $end === 0 )
            self::$errors[] = new Exception( 'Empty integer' );
        if ( self::char( $data ) == '-' )
            $start++;
        if ( substr( $data, $start, 1 ) == '0' && ( $start != 0 || $end > $start + 1 ) )
            self::$errors[] = new Exception( 'Leading zero in integer' );
        if ( ! ctype_digit( substr( $data, $start, $end ) ) )
            self::$errors[] = new Exception( 'Non-digit characters in integer' );
        $integer = substr( $data, 0, $end );
        $data = substr( $data, $end + 1 );
        return $integer + 0;
    }

    /** Helper to return the first char of encoded data
     * @param string encoded data
     * @return string|boolean first char of encoded data or false if empty data
     */
    static private function char ( $data ) 
    {
        return empty( $data ) ?
            false : 
            substr( $data, 0, 1 );
    }

    /**** Make BitTorrent ****/

    /** Getter and setter of torrent annouce url
     * @param null|string annouce url (optional, if omitted it's a getter)
     * @return string|null annouce url or null if not set
     */
    public function announce ( $announce = null ) 
    {
        return is_null( $announce ) ?
            isset( $this->announce ) ? $this->announce : null :
            $this->touch( $this->announce = (string) $announce );
    }

    /** Getter and setter of torrent annouce list
     * @param null|array annouce list (optional, if omitted it's a getter)
     * @return array|null annouce list or null if not set
     */
    public function announce_list ( $announce_list = null ) 
    {
        return is_null( $announce_list ) ?
            isset( $this->{'announce-list'} ) ? $this->{'announce-list'} : null :
            $this->touch( $this->{'announce-list'} = (array) $announce_list );
    }

    /** Getter and setter of torrent comment
     * @param null|string comment (optional, if omitted it's a getter)
     * @return string|null comment or null if not set
     */
    public function comment ( $comment = null ) 
    {
        return is_null( $comment ) ?
            isset( $this->comment ) ? $this->comment : null :
            $this->touch( $this->comment = (string) $comment );
    }

    /** Getter and setter of torrent name
     * @param null|string name (optional, if omitted it's a getter)
     * @return string|null name or null if not set
     */
    public function name ( $name = null ) 
    {
        return is_null( $name ) ?
            isset( $this->info['name'] ) ? $this->info['name'] : null :
            $this->touch( $this->info['name'] = (string) $name );
    }

    /** Getter and setter of private flag
     * @param null|boolean is private or not (optional, if omitted it's a getter)
     * @return boolean private flag
     */
    public function is_private ( $private = null ) 
    {
        return is_null( $private ) ?
            ! empty( $this->info['private'] ) :
            $this->touch( $this->info['private'] = $private ? 1 : 0 );
    }

    /** Getter and setter of webseed(s) url list ( GetRight implementation )
     * @param null|string|array webseed or webseeds mirror list (optional, if omitted it's a getter)
     * @return string|array|null webseed(s) or null if not set
     */
    public function url_list ( $urls = null ) 
    {
        return is_null( $urls ) ?
            isset( $this->{'url-list'} ) ? $this->{'url-list'} : null :
            $this->touch( $this->{'url-list'} = $urls );
    }

    /** Getter and setter of httpseed(s) url list ( Bittornado implementation )
     * @param null|string|array httpseed or httpseeds mirror list (optional, if omitted it's a getter)
     * @return array|null httpseed(s) or null if not set
     */
    public function httpseeds ( $urls = null ) 
    {
        return is_null( $urls ) ?
            isset( $this->httpseeds ) ? $this->httpseeds : null :
            $this->touch( $this->httpseeds = (array)  $urls );
    }

    /** Save torrent file to disk
     * @param null|string name of the file (optional)
     * @return boolean file has been saved or not
     */
    public function save ( $filename = null ) 
    {
        return file_put_contents( is_null( $filename ) ? $this->info['name'] . '.torrent' : $filename, $this );
    }

    /** Send torrent file to client
     * @param null|string name of the file (optional)
     * @return void script exit
     */
    public function send ( $filename = null ) 
    {
        $data = (string) $this;
        header( 'Content-type: application/x-bittorrent' );
        header( 'Content-Length: ' . strlen( $data ) );
        header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $this->{'creation date'} ) . ' GMT');
        header( 'Content-Disposition: attachment; filename="' . ( is_null( $filename ) ? $this->info['name'] . '.torrent' : $filename ) . '"' );
        exit( $data );
    }

    /** Build torrent info
     * @param string|array source folder/file(s) path
     * @param integer piece length
     * @return array|boolean torrent info or false if data isn't folder/file(s)
     */
    protected function build ( $data, $piece_length ) 
    {
        if ( is_null( $data ) )
            return false;
        elseif ( is_array( $data ) && self::is_list( $data ) )
            return $this->info = $this->files( $data, $piece_length );
        elseif ( is_dir( $data ) )
            return $this->info = $this->folder( $data, $piece_length );
        elseif ( is_file( $data ) && pathinfo( $data, PATHINFO_EXTENSION ) != 'torrent' )
            return $this->info = $this->file( $data, $piece_length );
        else
            return false;
    }

    /** Build torrent info from single file
     * @param string file path
     * @param integer piece length
     * @return array torrent info
     */
    private function file ( $file, $piece_length ) 
    {
        if ( ! $handle = self::fopen( $file, $size = self::filesize( $file ) ) ) {
            return ! self::$errors[] = new Exception( 'Failed to open file: "' . $file . '"' );
        }
        $pieces = '';
        while ( ! feof( $handle ) )
            $temp = fread( $handle, $piece_length );
            $pieces .= self::pack( $temp );
        fclose( $handle );
        return array(
            'length'        => $size,
            'name'          => basename( $file ),
            'piece length'  => $piece_length,
            'pieces'        => $pieces
        );
    }

    /** Build torrent info from files
     * @param array file list
     * @param integer piece length
     * @return array torrent info
     */
    private function files ( $files, $piece_length ) 
    {
        $files  = array_map( 'realpath', $files );
        sort( $files );
        usort( $files, create_function( '$a,$b', 'return strrpos($a,DIRECTORY_SEPARATOR)-strrpos($b,DIRECTORY_SEPARATOR);' ) );
        $path   = explode( DIRECTORY_SEPARATOR, dirname( realpath( current( $files ) ) ) );
        $length = $piece_length;
        $piece  = $pieces = '';
        foreach ( $files as $i => $file ) {
            if ( $path != array_intersect_assoc( $file_path = explode( DIRECTORY_SEPARATOR, $file ), $path ) ) {
                continue self::$errors[] = new Exception( 'Files must be in the same folder: "' . $file . '" discarded' );
            }
            if ( ! $handle = self::fopen( $file, $filesize = self::filesize( $file ) ) ) {
                continue self::$errors[] = new Exception( 'Failed to open file: "' . $file . '" discarded' );
            }
            while ( ! feof( $handle ) ) {
                if ( ( $length = strlen( $piece .= fread( $handle, $length ) ) ) == $piece_length ) {
                    $pieces .= self::pack( $piece );
                } else {
                    $length = $piece_length - $length;
                }
            }
            fclose( $handle );
            $info_files[$i] = array(
                'length'    => $filesize,
                'path'      => array_diff( $file_path, $path )
            );
        }
        switch ( count( $info_files ) ) {
            case 0:
                return false;
            case 1:
                return $this->file( $files[key( $info_files )], $piece_length );
            default:
                return array(
                    'files'         => $info_files,
                    'name'          => end( $path ),
                    'piece length'  => $piece_length,
                    'pieces'        => $pieces . ( $piece ? self::pack( $piece ) : '' )
                );
        }
    }

    /** Build torrent info from folder content
     * @param string folder path
     * @param integer piece length
     * @return array torrent info
     */
    private function folder ( $dir, $piece_length ) {
        return $this->files( self::scandir( $dir ), $piece_length );
    }

    /** Set torrent creator and creation date
     * @return void
     */
    protected function touch () {
        $this->{'created by'}       = 'Torrent PHP Class - Adrien Gibrat';
        $this->{'creation date'}    = time();
    }

    /** Helper to return filesize (even bigger than 2Gb, linux only)
     * @param string file path
     * @return double|boolean filesize or false if error
     */
    static public function filesize ( $file ) {
        return ( $size = @filesize( $file ) ) !== false ?
            (double) sprintf( '%u', $size ) :
            false;
    }

    /** Helper to open file to read (even bigger than 2Gb, linux only)
     * @param string file path
     * @param integer|double file size (optional)
     * @return ressource|boolean file handle or false if error
     */
    static public function fopen ( $file, $size = null ) {
        if ( ( is_null( $size ) ? self::filesize( $file ) : $size )  <= 2 * pow( 1024, 3 ) )
            return fopen( $file, 'r' );
        elseif ( PHP_OS != 'Linux' )
            return ! self::$errors[] = new Exception( 'File size is greater than 2GB. This is only supported under Linux' );
        elseif ( ! is_readable( $file ) )
            return false;
        else
            return popen( 'cat ' . escapeshellarg( realpath( $file ) ), 'r' );
    }

    /** Helper scan directories files and sub directories recursivly
     * @param string directory path
     * @return array directory content list
     */
    static public function scandir ( $dir ) {
        $paths = array();
        foreach ( scandir( $dir ) as $item  )
                if ( $item != '.' && $item != '..' )
                    if ( is_dir( $path = realpath( $dir . DIRECTORY_SEPARATOR . $item ) ) )
                        $paths = array_merge( self::scandir( $path ), $paths );
                    else
                        $paths[] = $path;
        return $paths;
    }

    /** Helper to pack data hash
     * @param string data
     * @return string packed data hash
     */
    static protected function pack ( & $data ) {
        return pack('H*', sha1( $data ) ) . ( $data = '' );
    }

    /**** Analyze BitTorrent ****/

    /** Get piece length
     * @return integer piece length or null if not set
     */
    public function piece_length () {
        return isset( $this->info['piece length'] ) ?
            $this->info['piece length'] :
            null;
    }

    /** Compute hash info
     * @return string hash info or null if info not set
     */
    public function hash_info () {
        return isset( $this->info ) ? 
            sha1( self::encode( $this->info ) ) : 
            null;
    }

    /** List torrent content
     * @param integer|null size precision (optional, if omitted returns sizes in bytes)
     * @return array file(s) and size(s) list, files as keys and sizes as values
     */
    public function content ( $precision = null ) {
        $files = array();
        if ( is_array( $this->info['files'] ) )
            foreach ( $this->info['files'] as $file )
                $files[self::path( $file['path'], $this->info['name'] )] = $precision ? 
                    self::format( $file['length'], $precision ) : 
                    $file['length'];
        elseif ( isset( $this->info['name'] ) )
                $files[$this->info['name']] = $precision ? 
                    self::format( $this->info['length'], $precision ) : 
                    $this->info['length'];
        return $files;
    }

    /** List torrent content pieces and offset(s)
     * @return array file(s) and pieces/offset(s) list, file(s) as keys and pieces/offset(s) as values
     */
    public function offset () {
        $files = array();
        $size = 0;
        if ( is_array( $this->info['files'] ) )
            foreach ( $this->info['files'] as $file )
                $files[self::path( $file['path'], $this->info['name'] )] = array(
                    'startpiece'    => floor( $size / $this->info['piece length'] ),
                    'offset'        => fmod( $size, $this->info['piece length'] ),
                    'size'          => $size += $file['length'],
                    'endpiece'      => floor( $size / $this->info['piece length'] )
                );
        elseif ( isset( $this->info['name'] ) )
                $files[$this->info['name']] = array(
                    'startpiece'    => 0,
                    'offset'        => 0,
                    'size'          => $this->info['length'],
                    'endpiece'      => floor( $this->info['length'] / $this->info['piece length'] )
                );
        return $files;
    }

    /** Sum torrent content size
     * @param integer|null size precision (optional, if omitted returns size in bytes)
     * @return integer|string file(s) size
     */
    public function size ( $precision = null ) {
        $size = 0;
        if ( isset($this->info['files']) && is_array( $this->info['files'] ) )
            foreach ( $this->info['files'] as $file )
                $size += $file['length'];
        elseif ( isset( $this->info['name'] ) )
                $size = $this->info['length'];
        return is_null( $precision ) ? 
            $size :
            self::format( $size, $precision );
    }

    /** Request torrent statistics from scrape page
     * @param string announce or scrape page url (optional, to request an alternative tracker BUT mandatory for static call)
     * @param string torrent hash info (optional: ONLY for static call)
     * @return array tracker torrent statistics
     */
    /* static */ public function scrape ( $announce = null, $hash_info = null ) {
        if ( ! ini_get( 'allow_url_fopen' ) )
            return ! self::$errors[] = new Exception( '"allow_url_fopen" must be enabled' );
        $packed_hash = pack('H*', $hash_info ? $hash_info : sha1( self::encode( $this->info ) ) );
        $scrape_data = file_get_contents( str_ireplace( '/announce', '/scrape', $announce ? $announce : $this->announce ) . '?info_hash=' . urlencode( $packed_hash ) );
        $stats = self::decode_data( $scrape_data );
        return isset( $stats['files'][$packed_hash] ) ?
            $stats['files'][$packed_hash] :
            ! self::$errors[] = new Exception( 'Invalid scrape data' );
    }

    /** Helper to build file path
     * @param array file path
     * @param string base folder
     * @return string real file path
     */
    static private function path ( $path, $folder ) {
        array_unshift( $path, $folder );
        return join( DIRECTORY_SEPARATOR, $path );
    }

    /** Helper to format size in bytes to human readable
     * @param integer size in bytes
     * @param integer precision after coma
     * @return string formated size in appropriate unit
     */
    static public function format ( $size, $precision = 2 ) {
        $units = array ('octets', 'Ko', 'Mo', 'Go', 'To');
        while( ( $next = next( $units ) ) && $size > 1024 )
            $size /= 1024;
        return round( $size, $precision ) . ' ' . ( $next ? prev( $units ) : end( $units ) );
    }

}

?>
