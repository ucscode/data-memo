<?php

/* 
	version 2.0.0
	----------------------
	changed name from data_storage to storage
	referenced the __get() as &__get()
	added __debuginfo() for debug visuality
	added new exception handler
	temporarily stored data in $memo property
	added __destruct() to finalized storage action
	converted delete_file() method to remove()
	added clear() method to erase storage memory
	converted json_pretty_print() method to pretty_print();
	removed readonly() and writable() method;

	v 2.0.1
	-------------------------
	added data() method to access $memo property [ in case of foreach ]
	
	v 2.0.2
	-------------------------
	removed auto-saver from __destruct() function
	added save() function to store $memo data to file
	changed name form storage to datamemo
*/

class datamemo {

	private $filename;
	
	private $filepath;
	
	private $readonly = false;
	
	private $memo;
	
	private $__NULL = null;
	
	private $configured;
	
	private $pretty;
	
	private $remove;
	
	//-----------------------------------------------------------------
	
	public function __construct( $filename, $filepath = __DIR__ ) {
	
		if( !DEFINED("DISPLAY_STORAGE_ERROR") ) 
			DEFINE("DISPLAY_STORAGE_ERROR", ini_get("display_errors") );
		
		try {
			
			if( empty($filename) ):
				if( DISPLAY_STORAGE_ERROR ) throw new Exception( "Missing file name in argument 1"  );
				return;	
			endif;
			
			$this->filename = $this->stripe($filename);
			$this->filepath = $this->stripe($filepath);
			if( substr($this->filepath, -1) == '/' ) 
				$this->filepath = substr($this->filepath, 0, -1);
	
			$valid_absolute_path = preg_match( "~^" . $this->stripe($_SERVER['DOCUMENT_ROOT']) . "~", $this->filepath );
			
			if( !$this->isFileType( $filename ) ):
				if( DISPLAY_STORAGE_ERROR ) throw new Exception( "Missing file extension in argument 1" );
				return;
			elseif( !$valid_absolute_path ):
				if( DISPLAY_STORAGE_ERROR ) throw new Exception( "Path must be absolute in argument 2" );
				return;
			endif;
			
			$file = $this->filepath . "/" . $this->filename;
			$MEMO = $this->memo( $file );
			$this->memo = ( empty($MEMO) || !is_array($MEMO) ) ? Array() : (array)$MEMO;
	
			$this->configured = true;

		} catch( Exception $e ) {
			
			$this->debug( $e );
			
		}
		
	}
	
	//-----------------------------------------------------------------
	
	private function isFileType( $file ) {
		$filename = explode(".", basename( $file ));
		return ( count($filename) > 1 && !empty($filename[1]) );
	}
	
	private function stripe( $pathfile ) {
		return str_replace( "\\", "/", $pathfile );
	}
	
	//-----------------------------------------------------------------
	
	private function debug( $exception, $array = array( "print_method" => true ) ) {
		
		$trace = $exception->getTrace()[0];
		
		$eMessage = '';
			
		$trace_func = array_key_exists( "custom_method_name", $array ) ? $array["custom_method_name"] : $trace['function'];
		
		$method = ( method_exists($this, $trace['function']) ) ? new ReflectionMethod($this, $trace['function']) : null;
		
		if( $method && $method->isPublic() ): 
			if( $array["print_method"] ) $eMessage .= " for " . $trace['class'] . '::' . $trace_func . "(), called";
			$eMessage .= " in <b>" . $this->stripe($trace['file']) . "</b> on line " . $trace['line'];
		endif;
		
		$Message = array_key_exists( "custom_message", $array ) ? $array["custom_message"] : $exception->getMessage();
		
		$eMessage = "<br />\n <b>DataMemo Notice:</b> " . $Message . $eMessage . "<br \>\n\n";
		
		print_r( $eMessage );
		
		$this->configured = false;
		
	}
	
	//------------------------------------------------------------------
	
	private function memo( $file ) {
		
		if( file_exists($file) ) {
			$contents = file_get_contents($file);
			if( empty($contents) ) return;
			$memo = json_decode( $contents, true );
			if( json_last_error() ) throw new Exception( "Json " . ucwords(json_last_error_msg()) . " in <b>" . $file . "</b>" );
			return $memo;
		}
	
	}
	
	//--------------------------------------------------------------------
		// FEEL THE MAGIC
	//---------------------------------------------------------------------
	
		public function __debuginfo() {
			return (array)$this->memo;
		}
		
	// --------------------------------------------------------------------
	
		public function &__get($key) {
			try {
				if( !array_key_exists( $key, $this->memo ) ) {
					if( DISPLAY_STORAGE_ERROR ) throw new exception( "Undefined index $key" );
					return $this->__NULL;
				};
			} catch( Exception $e ) {
				$this->debug( $e, array( "print_method" => false ) );
			}
			return $this->memo[$key];
		}
		
	// --------------------------------------------------------------------
	
		public function __set($key, $value) {
			$this->memo[$key] =& $value;
		}
		
	// --------------------------------------------------------------------

	
	public function __isset($key) {
		
		return array_key_exists( $key, $this->memo );
		
	}
	
	public function __unset($key) {
		
		if( array_key_exists( $key, $this->memo ) ) unset( $this->memo[$key] );
		
	}
	
	public function remove( $default = true ) {
		$this->remove = $default;
	}
	
	public function pretty_print( $default = true ) {
		$this->pretty = ( $default ) ? JSON_PRETTY_PRINT : NULL;
	}
	
	public function clear() {
		$this->memo = new ArrayObject();
	}
	
	private function getFile() {
		if( !file_exists( $this->filepath ) ) mkdir( $this->filepath );
		$file = $this->filepath . "/" . $this->filename;
		return $file;
	}
	
	public function save() {
		$data = json_encode( $this->memo, $this->pretty );
		return ( file_put_contents( $this->getFile(), $data ) ) ? true : false;
	}
	
	public function __destruct() {
		if( $this->remove && file_exists( $this->getFile() ) ) unlink( $file );
	}
	
	public function data() {
		return $this->memo;
	}
	
	
}

