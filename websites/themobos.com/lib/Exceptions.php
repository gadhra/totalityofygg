<?php
/**
 * Exception interface
 * 
 * Internal custom exceptions for blogworks.  
 * Taken and modified from http://php.net/manual/en/language.exceptions.php#91159
 * @author Stefan Antonowicz <iam@stefans.computer>
 * @version 3.0
 * @package phpLib
 * @subpackage Library
 */
interface IException
{
    /* Protected methods inherited from Exception class */
    public function getMessage();                 // Exception message 
    public function getCode();                    // User-defined Exception code
    public function getFile();                    // Source filename
    public function getLine();                    // Source line
    public function getTrace();                   // An array of the backtrace()
    public function getTraceAsString();           // Formated string of trace
    
    /* Overrideable methods inherited from Exception class */
    public function __toString();                 // formated string for display
    public function __construct($message = null, $code = 0);
}
abstract class CustomException extends Exception implements IException
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown
    public function __construct( $message = null, $code = 0 )
    {
        if(! $message ) {
            throw new $this( 'Unknown '. get_class( $this ) );
        }
        parent::__construct( $message, $code );
    }
    /**
     * Homegrown method to handle errors while honoring environment
     * @return array  echo the error and die if dev, otherwise return an empty array
     */
    public function handleError( $response_type = 'multi' ) {
        if( ENVIRONMENT == 'DEV' ) {
            echo $this->getMessage(); exit;
        }
        switch( $response_type ) {
            case 'multi':
                return [];
                break;
            case 'single':
                return null;
                break;
            default:
                return false;
                break;
        }
    }
}