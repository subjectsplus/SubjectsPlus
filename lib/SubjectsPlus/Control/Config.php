<?php
namespace SubjectsPlus\Control;

use PDO;
/**
 * sp_Config - this class handles all aspects of the configuration of SubjectsPlus
 *
 * @package SubjectsPlus
 * @author dgonzalez
 * @copyright Copyright (c) 2013
 * @version $Id$
 * @access public
 */
class Config
{
	//class constants
	const LARGE_INPUT_SIZE = 50;
	const MEDIUM_INPUT_SIZE = 30;
	const SMALL_INPUT_SIZE = 10;

	//class private properties
	private $lobjConfigOptions;
	private $lobjConfigValues;
	private $lobjNewConfigValues;
	private $lstrConfigPath;
	private $lobjConfigFile;
	private $lboolChangeSalt;
	private $lboolChangeAPIKey;
	private $lobjSetupDBKeys;
	private $lobjSetupSiteKeys;

	/**
	 * sp_Config::__construct() - initialize all properties
	 *
	 */
	function __construct()
	{
		$this->lobjConfigOptions = array();
		$this->lobjConfigValues = array();
		$this->lobjNewConfigValues = array();
		$this->lstrConfigPath = '';
		$this->lboolChangeSalt = FALSE;
		$this->lboolChangeAPIKey = FALSE;

		$this->lobjSetupDBKeys = array( 'hname', 'uname', 'pword', 'dbName_SPlus', 'db_port' );
		$this->lobjSetupSiteKeys = array( 'resource_name', 'institution_name', 'administrator', 'administrator_email', 'email_key', 'tel_prefix' );
	}

	/**
	 * sp_Config::setConfigOptions() - set the config options property and executes the
	 * setConfigValues method
	 *
	 * @param array $lobjConfigOptions
	 * @return void
	 */
	public function setConfigOptions( Array $lobjConfigOptions )
	{
		$this->lobjConfigOptions = $lobjConfigOptions;

		$this->setConfigValues();
	}

	/**
	 * sp_Config::setConfigPath() - this method sets the config file path and
	 * based on the second argument, it sets the config file object
	 *
	 * @param string $lstrConfigPath
	 * @param boolean $lboolSetConfigFile
	 * @return boolean
	 */
	public function setConfigPath( $lstrConfigPath , $lboolSetConfigFile = TRUE )
	{
		$this->lstrConfigPath = $lstrConfigPath;

		if( $lboolSetConfigFile )
		{
			//only set if the file exits
			if( !file_exists($this->lstrConfigPath) )
			{
				return FALSE;
			}

			$this->setConfigFile();
		}

		return TRUE;
	}

	/**
	 * sp_Config::setChangeSalt() - this method sets the change salt property
	 *
	 * @param boolean $lboolChange
	 * @return void
	 */
	public function setChangeSalt( $lboolChange = FALSE )
	{
		$this->lboolChangeSalt = $lboolChange;
	}

	/**
	 * sp_Config::setChangeAPIKey() - this method sets the change API key property
	 *
	 * @param boolean $lboolChange
	 * @return void
	 */
	public function setChangeAPIKey( $lboolChange = FALSE )
	{
		$this->lboolChangeAPIKey = $lboolChange;
	}

	/**
	 * setNewConfigValues()- this method gathers all POST configurations based on
	 * options property which specify type of configuration and srubs them and then
	 * sets the new values property
	 *
	 * @param array $lobjOptions
	 * @return boolean
	 */
	public function setNewConfigValues( )
	{
		//makes sure that config options is set
		if( empty( $this->lobjConfigOptions ) ) return FALSE;

		$lobjNewValues = array();

		foreach( $this->lobjConfigOptions as $lstrKey => $lobjOption )
		{
			switch( $lobjOption[2] )
			{
				case 'array':
					$lobjNewValues[ $lstrKey ] = $this->commaListToArray( addcslashes( trim( $_POST[ $lstrKey ] ), "\\'\"" ) );
					break;
				case 'aarray':
					$lobjNewValues[ $lstrKey ] = $this->commaListToAArray( addcslashes( trim( $_POST[ $lstrKey ] ), "\\'\"" ) );
					break;
				case 'boolean':
					$lstrValue = addcslashes( trim( $_POST[ $lstrKey ] ), "\\'\"" );
					$lstrValue = strtolower( $lstrValue );
					if( $lstrValue == 'true' ) $lobjNewValues[ $lstrKey ] = TRUE;
					else $lobjNewValues[ $lstrKey ] = FALSE;
					break;
				default:
					$lobjNewValues[ $lstrKey ] = addcslashes( trim( $_POST[ $lstrKey ] ), "\\'\"" );
			}
		}

		$this->lobjNewConfigValues = $lobjNewValues;

		return TRUE;
	}

	/**
	 * sp_Config::getChangeSalt() - this method is a getter for the private change
	 * salt property
	 *
	 * @return boolean
	 */
	public function getChangeSalt()
	{
		return $this->lboolChangeSalt;
	}

	/**
	 * sp_Config::getChangeAPIKey() - this method is a getter for the private change
	 * API key property
	 *
	 * @return boolean
	 */
	public function getChangeAPIKey()
	{
		return $this->lboolChangeAPIKey;
	}

	/**
	 * sp_Config::isNewBaseURL() - this method determines whether the BaseURL changed
	 * from the original values to the new values
	 *
	 * @return boolean
	 */
	public function isNewBaseURL()
	{
		return ( $this->lobjConfigValues[ 'BaseURL' ] != $this->lobjNewConfigValues[ 'BaseURL' ] );
	}

	/**
	 * sp_Config::isNewModRewrite() - this method determines whether the mod_rewrite
	 * changed from the original values to the new values.
	 *
	 * @return boolean
	 */
	public function isNewModRewrite()
	{
		return ( $this->lobjConfigValues[ 'mod_rewrite' ] != $this->lobjNewConfigValues[ 'mod_rewrite' ] );
	}

	/**
	 * sp_Config::getNewBaseURL() - this method returns the BaseURL from the new
	 * values
	 *
	 * @return boolean
	 */
	public function getNewBaseURL()
	{
		return $this->lobjNewConfigValues[ 'BaseURL' ];
	}

	/**
	 * sp_Config::checkDBConnection - this method checks to see if a database connection is possible
	 * with the new values
	 *
	 * @return string
	 */
	public function checkDBConnection( )
	{
		$lstrError = '';

		try {
			$dsn = 'mysql:dbname=' . $this->lobjNewConfigValues['dbName_SPlus'] . ';host=' . $this->lobjNewConfigValues['hname'] . ';port=' . $this->lobjNewConfigValues['db_port'] . ';charset=utf8';
			$lobjConnection = new PDO($dsn, $this->lobjNewConfigValues['uname'], $this->lobjNewConfigValues['pword'], array(PDO::ATTR_PERSISTENT => true));
		} catch (\PDOException $e) {
			$lstrError .= "<h1>There was a problem connecting to the database.</h1>";
			$lstrError .= "<p>This is the detailed error:</p>";
			$lstrError .= 'Connection failed: ' . $e->getMessage();
		}

		return $lstrError;
	}

	/**
	 * sp_Config::displayMessage() - this method displays, at time of call, a div with a message
	 * and a specific class
	 *
	 * @param string $lstrMessage
	 * @return void
	 */
	public function displayMessage( $lstrMessage )
	{
		if( $lstrMessage == '' )
		{
			?>
		<div class="master-feedback" >
		</div><br /><br />
		<?php
		}else
		{
			?>
		<div class="master-feedback" style="display: block;" >
			<?php echo $lstrMessage ?>
		</div><br /><br />
		<?php
		}
	}

	/**
	 * sp_Config::writeConfigFile() - this method will execute the setup config file function first
	 * and then it will try and open the config file path for writing. If successful,
	 * it will then write the new config file and then close the file and change the
	 * permissions to a writable file.
	 *
	 * @return boolean
	 */
	public function writeConfigFile( )
	{
		if( $this->setupConfigFile() === TRUE)
		{
			//open the file for writing which will truncate all data on the file.
			$lhndFile = fopen( $this->lstrConfigPath, 'w' );

			if( $lhndFile === FALSE ) return FALSE;

			//go through each line in file array and write it to the file
			foreach( $this->lobjConfigFile as $lstrLine )
			{
				$lboolSuccess = fwrite( $lhndFile, $lstrLine );

				//if, at any point, the file cannot be written to, return false.
				if( $lboolSuccess === FALSE ) return FALSE;
			}

			//close and change permissions of file
			fclose( $lhndFile );
			return chmod( $this->lstrConfigPath, 0666 );
		}

		return FALSE;
	}

	/**
	 * sp_Config::wrtieModRewriteFile() - this method, depending on the new values
	 * 'mod_rewrite', write the .htaccess file to make prettier URL in the subject
	 * folder
	 *
	 * @param string $lstrModRewritePath
	 * @return boolean
	 */
	public function wrtieModRewriteFile( $lstrModRewritePath = '' )
	{
		if( $lstrModRewritePath == '' ) return FALSE;

		//create file array
		$lobjFile = file( $lstrModRewritePath );

		//go through each line of file
		foreach( $lobjFile as $lintLineNumber => $lstrLine )
		{
			//do nothing to first line. Comment
			if( $lintLineNumber == 0 ) continue;

			if( $this->lobjNewConfigValues[ 'mod_rewrite' ] )
			{
				//remove comments if true
				$lstrLine = str_replace( '#' , '', $lstrLine);
			}else
			{
				//add comments if false
				$lstrLine = '#' . $lstrLine;
			}

			$lobjFile[ $lintLineNumber ] = $lstrLine;
		}

		//open the file for writing which will truncate all data on the file.
		$lhndFile = fopen( $lstrModRewritePath, 'w' );

		//if opening of the file givers error, return false
		if( $lhndFile === FALSE ) return FALSE;

		//go through and write file array to file
		foreach( $lobjFile as $lstrLine )
		{
			$lboolSuccess = fwrite( $lhndFile, $lstrLine );

			//if the file cannot be written to, return false.
			if( $lboolSuccess === FALSE ) return FALSE;
		}

		//close file and change permissions
		fclose( $lhndFile );
		return chmod( $lstrModRewritePath, 0666 );
	}

	/**
	 * sp_Config::displayEditConfigForm() - this method goes through the values, based
	 * on passed type, and creates and outputs the editing configurations form. Uses
	 * the options stored in class property
	 *
	 * @param string $lstrType
	 * @return void
	 */
	public function displayEditConfigForm( $lstrType = 'original' )
	{
		//use original or new values
		if( $lstrType == 'original' )
		{
			$lobjValues = $this->lobjConfigValues;
		}else
		{
			$lobjValues = $this->lobjNewConfigValues;
		}

		//initialize variables
		$lstrLeftHTML = '';
		$lstrRightHTML = '';
		$lstrLeftBottomHTML = '';
		$lstrRightBottomHTML = '';

		//go through all options
		foreach( $this->lobjConfigOptions as $lstrKey => $lobjOption )
		{
			//span containing input label
			$lstrHTML = "<label for=\"{$lobjOption[0]}\">{$lobjOption[0]} ";

			//if there is a tooltip in the options
			if( isset( $lobjOption[6] ) && $lobjOption[6] != '' )
			{
				$lstrTitle = htmlentities( $lobjOption[6] );

				$lstrHTML .= "&nbsp;<span class=\"tooltipcontainer\"><img class=\"tooltip\" src=\"../assets/images/icons/help.png\" data-notes=\"{$lstrTitle}\" /></span>\n";
			}

			$lstrHTML .= "</label>\n";
			$lstrHTML .= "<span style=\"font-size: smaller; padding: 0px 0px 5px 0px;\">{$lobjOption[1]}</span><br />\n";

			//based on type, create HTML form inputs
			switch( $lobjOption[2] )
			{
				case 'array':
					//based on type of array
					switch( strtolower( $lobjOption[4] ) )
					{
						case 'ticks':

							if( isset( $lobjOption[5] ) && is_array( $lobjOption[5] ))
							{
								$lstrHTML .= $this->arrayToTicks( $lobjOption[5], $lobjValues[$lstrKey], $lstrKey );
								$lstrHTML .= "\n";
								break;
							}

						case "textarea":
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<textarea id=\"{$lstrKey}\" name=\"{$lstrKey}\" cols=\"45\" rows=\"3\" >{$lstrValue}</textarea>";
							break;

						case 'medium':
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" />\n";
							break;
						case 'small':
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" />\n";
							break;
						default:
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" />\n";
							break;
					}

					$lstrHTML .= "\n";
					break;
					//case of an assosiative array
				case 'aarray':
					//based on type of aarray
					switch( strtolower( $lobjOption[4] ) )
					{
						case 'medium':
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" ";
							break;
						case 'small':
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" ";
							break;
						default:
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" ";
							break;
					}

					//append to auto generated options in order to discourage changing important
					//configurations
					if( in_array( $lstrKey, array( 'all_tbtags' ) ) )
					{
						$lstrHTML .= " disabled />\n<br /><span style=\"font-size: smaller\">**" . _( "This is automatically generated on installation" ) . ".
										<a onclick=\"javascript: enableTextBox(this);\" style=\"cursor: pointer; color: #C03957; text-decoration: underline;\" >" . _( "Edit?" ) . "</a></span>\n";
						break;
					}

					$lstrHTML .= "/>\n";
					break;
				case 'boolean':
					//if a boolean type
					$lstrHTML .= "<select id=\"{$lstrKey}\" name=\"{$lstrKey}\">\n";

					if( $lobjValues[$lstrKey] )
					{
						$lstrHTML .= "<option value=\"TRUE\" selected>" . _( "TRUE" ) . "</option>\n";
						$lstrHTML .= "<option value=\"FALSE\" >" . _( "FALSE" ) . "</option>\n";
					}
					else
					{
						$lstrHTML .= "<option value=\"TRUE\" >" . _( "TRUE" ) . "</option>\n";
						$lstrHTML .= "<option value=\"FALSE\" selected>" . _( "FALSE" ) . "</option>\n";
					}

					$lstrHTML .= "</select>\n";

					break;
				case 'pword':
					$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"password\" value=\"{$lobjValues[$lstrKey]}\" ";

					switch( strtolower( $lobjOption[4] ) )
					{
						case 'medium':
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" ";
							break;
						case 'small':
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" ";
							break;
						default:
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" ";
							break;
					}

					$lstrHTML .= "/>\n";
					break;

				default:
					$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lobjValues[$lstrKey]}\" ";

					switch( strtolower( $lobjOption[4] ) )
					{
						case 'medium':
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" ";
							break;
						case 'small':
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" ";
							break;
						default:
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" ";
							break;
					}

					//append to auto generated options in order to discourage changing important
					//configurations
					if( in_array( $lstrKey, array( 'BaseURL', 'CKBasePath' ) ) )
					{
						$lstrHTML .= " disabled />\n<br /><span style=\"font-size: smaller\">**" . _( "This is automatically generated on installation" ) . ".
										<a onclick=\"javascript: enableTextBox(this);\" style=\"cursor: pointer; color: #C03957; text-decoration: underline;\" >" . _( "Not right?" ) . "</a></span>\n";
						break;
					}

					$lstrHTML .= "/>\n";
					break;
			}

			$lstrHTML .= "\n";

			//based on passed option, place on left, leftbottom or right box
			if( strtolower( $lobjOption[3] ) == 'left' )
			{
				$lstrLeftHTML .= $lstrHTML;
			}elseif( strtolower( $lobjOption[3] ) == 'left-bottom' )
			{
				$lstrLeftBottomHTML .= $lstrHTML;
			}elseif( strtolower( $lobjOption[3] ) == 'right-bottom' )
			{
				$lstrRightBottomHTML .= $lstrHTML;
			}else
			{
				$lstrRightHTML .= $lstrHTML;
			}
		}

		?>
		<form id="config_form" class="pure-form pure-form-stacked" action="edit-config.php" method="POST">

			<div class="pure-g">
				<div class="pure-u-1-3">
				    <div class="pluslet">
				        <div class="titlebar">
				            <div class="titlebar_text"><?php print _("Core Configurations"); ?></div>
				        </div>
				        <div class="pluslet_body">
				            <?php print $lstrLeftHTML; ?>
				        </div>
				    </div>
				    <div class="pluslet">
				        <div class="titlebar">
				            <div class="titlebar_text"><?php print _("Institutional Configurations"); ?></div>
				        </div>
				        <div class="pluslet_body">
				            <?php print $lstrLeftBottomHTML; ?>
				        </div>
				    </div>
				</div>

				<div class="pure-u-1-3">




				    <div class="pluslet">
				        <div class="titlebar">
				            <div class="titlebar_text"><?php print _("Catalog Connections"); ?></div>
				        </div>
				        <div class="pluslet_body">
				            <?php echo $lstrRightBottomHTML; ?>
				        </div>
				    </div>				    				    
				</div>

				<div class="pure-u-1-3">
				    <div class="pluslet">
				        <div class="titlebar">
				            <div class="titlebar_text"><?php print _("Save"); ?></div>
				        </div>
				        <div class="pluslet_body">
				            <input type="submit" class="button" name="submit_edit_config" value="<?php echo _("Save Config"); ?>" />
				        </div>
				    </div>
				    <div class="pluslet">
				        <div class="titlebar">
				            <div class="titlebar_text"><?php print _("Other Configurations"); ?></div>
				        </div>
				        <div class="pluslet_body">
				            <?php echo $lstrRightHTML; ?>
				        </div>
				    </div>
				</div>
				</div> <!-- end pure g-r -->

		</form>
		<?php
	}

	/**
	 * sp_Config::displaySetupDBConfigForm() - this method executes the display
	 * setup config form for the type 'db'
	 *
	 * @param string $lstrType
	 * @return void
	 */
	public function displaySetupDBConfigForm( $lstrType = 'original' )
	{
		$this->displaySetupConfigForm( 'db', $lstrType );
	}

	/**
	 * sp_Config::displaySetupSiteConfigForm() - this method executes the display
	 * setup config form for the type 'site'
	 *
	 * @param string $lstrType
	 * @return void
	 */
	public function displaySetupSiteConfigForm( )
	{
		$this->displaySetupConfigForm( 'site' );
	}

	/**
	 * sp_Config::displayErrorPage() - this method displays the config error page
	 * with the passed title and reason
	 *
	 * @param string $lstrTitle
	 * @param string $lstrReason
	 * @return void
	 */
	public function displayErrorPage( $lstrTitle, $lstrReason, $lboolError = TRUE )
	{
		displayLogoOnlyHeader();

		?>
		<br /><br />
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
    	<div class="box" name="error_page" align="center">
			<h2 class="bw_head"><?php echo _( $lstrTitle ); ?></h2>

				<?php if( $lboolError )
				{
					?> <p><?php echo _( "Something went wrong:" ); ?></p><?php
				}
				?>
				<p><?php echo $lstrReason; ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Config::displaySteps() - this method displays the setup config steps page
	 *
	 * @return void
	 */
	public function displaySteps()
	{
		displayLogoOnlyHeader();

		?>
		<br /><br />
		<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
            <div class="box" name="error_page">
            <h2 class="bw_head"><?php echo _( "Welcome!" ); ?></h2>

				<p><?php echo _( "Before getting started, we need some information about the database, You will need to know the following: " ); ?></p><br />
				<ul>
					<li><?php echo _( "Database host" ); ?></li>
					<li><?php echo _( "Database username" ); ?></li>
					<li><?php echo _( "Database password" ); ?></li>
					<li><?php echo _( "Database name" ); ?></li>
				</ul><br />
				<p><?php echo _( "If for any reason this automatic file creation does not work, you can simply open <code>config-default.php</code> in a text editor and fill in your information and then save it as <code>config.php</code>." ); ?></p>
				<br />
				<a href="setup-config.php">Continue</a>
			</div>
		</div>
		<?php
	}

	/**
	 * sp_Config::setConfigFile() - this method converts a file into and array and
	 * sets tje config file property
	 *
	 * @return void
	 */
	private function setConfigFile()
	{
		//create file array
		$lobjConfigFile = file( $this->lstrConfigPath );

		$this->lobjConfigFile = $lobjConfigFile;
	}

	/**
	 * sp_Config::displaySetupConfigForm() - the method will display the setup config form
	 * based on the first argument defining which form it is and the second defiing which
	 * values to use
	 *
	 * @param string $lstrForm
	 * @param string $lstrType
	 * @return void
	 */
	private function displaySetupConfigForm( $lstrForm = '', $lstrType = 'original' )
	{
		$lstrForm = strtolower( trim( $lstrForm ) );

		if( $lstrForm == '' ) return;

		//if the db form
		if( $lstrForm == 'db' )
		{
			if( $lstrType == 'original' )
			{
				$this->autoGenerateConfigs();
				$lobjValues = $this->lobjConfigValues;
			}else
			{
				$lobjValues = $this->lobjNewConfigValues;
			}
		}

		//if the site form
		if( $lstrForm == 'site' )
		{
			$lobjValues = $this->lobjConfigValues;
		}

		$lstrHTML = '';

		//go through all options and create the form
		foreach( $this->lobjConfigOptions as $lstrKey => $lobjOption )
		{
			//if db, hide all the non db options
			if( $lstrForm == 'db' )
			{
				if( !in_array( $lstrKey , $this->lobjSetupDBKeys ) )
				{
					$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"hidden\" ";

					switch( $lobjOption[2] )
					{
						case 'array':
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							break;
						case 'aarray':
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							break;
						case 'boolean':
							if( $lobjValues[$lstrKey] )
							{
								$lstrValue = 'TRUE';
							}else
							{
								$lstrValue = 'FALSE';
							}
							break;
						default:
							$lstrValue = $lobjValues[$lstrKey];
							break;
					}

					$lstrHTML .= "value=\"{$lstrValue}\" />\n";
					continue;
				}
			}

			//if site, hide all non site options
			if( $lstrForm == 'site' )
			{
				if( !in_array( $lstrKey , $this->lobjSetupSiteKeys ) )
				{
					$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"hidden\" ";

					switch( $lobjOption[2] )
					{
						case 'array':
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							break;
						case 'aarray':
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							break;
						case 'boolean':
							if( $lobjValues[$lstrKey] )
							{
								$lstrValue = 'TRUE';
							}else
							{
								$lstrValue = 'FALSE';
							}
							break;
						default:
							$lstrValue = $lobjValues[$lstrKey];
							break;
					}

					$lstrHTML .= "value=\"{$lstrValue}\" />\n";
					continue;
				}
			}

			$lstrHTML .= "<span class=\"record_label\">{$lobjOption[0]}</span>\n";

			//if there is a tooltip in the options
			if( isset( $lobjOption[6] ) && $lobjOption[6] != '' )
			{
				$lstrTitle = htmlentities( $lobjOption[6] );

				$lstrHTML .= "&nbsp;<span class=\"tooltipcontainer\"><img class=\"tooltip\" src=\"../assets/images/icons/help.png\" data-notes=\"{$lstrTitle}\" /></span>\n";
			}

			//$lstrHTML .= "<br />\n";
			$lstrHTML .= "<p style=\"font-size: smaller; padding: 0px 0px 5px 0px;\">{$lobjOption[1]}</p>\n";

			//based on type, create HTML form inputs
			switch( $lobjOption[2] )
			{
				case 'array':
					//based on type of array
					switch( strtolower( $lobjOption[4] ) )
					{
						case 'ticks':

							if( isset( $lobjOption[5] ) && is_array( $lobjOption[5] ))
							{
								$lstrHTML .= $this->arrayToTicks( $lobjOption[5], $lobjValues[$lstrKey], $lstrKey );
								$lstrHTML .= "\n";
								break;
							}

						case "textarea":
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<textarea id=\"{$lstrKey}\" name=\"{$lstrKey}\" cols=\"45\" rows=\"3\" >{$lstrValue}</textarea>";
							break;

						case 'medium':
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" />\n";
							break;
						case 'small':
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" />\n";
							break;
						default:
							$lstrValue = $this->arrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" />\n";
							break;
					}

					$lstrHTML .= "\n";
					break;
					//case of an assosiative array
				case 'aarray':
					//based on type of aarray
					switch( strtolower( $lobjOption[4] ) )
					{
						case 'medium':
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" ";
							break;
						case 'small':
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" ";
							break;
						default:
							$lstrValue = $this->aarrayToCommaList( $lobjValues[$lstrKey] );
							$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lstrValue}\" ";
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" ";
							break;
					}

					//append to auto generated options in order to discourage changing important
					//configurations
					if( in_array( $lstrKey, array( 'all_tbtags' ) ) )
					{
						$lstrHTML .= " disabled />\n<br /><span style=\"font-size: smaller\">**" . _( "This is automatically generated on installation" ) . ".
										<a onclick=\"javascript: enableTextBox(this);\" style=\"cursor: pointer; color: #C03957; text-decoration: underline;\" >" . _( "Edit?" ) . "</a></span>\n";
						break;
					}

					$lstrHTML .= "/>\n";
					break;
				case 'boolean':

					$lstrHTML .= "<select id=\"{$lstrKey}\" name=\"{$lstrKey}\">\n";

					if( $lobjValues[$lstrKey] )
					{
						$lstrHTML .= "<option value=\"TRUE\" selected>" . _( "TRUE" ) . "</option>\n";
						$lstrHTML .= "<option value=\"FALSE\" >" . _( "FALSE" ) . "</option>\n";
					}
					else
					{
						$lstrHTML .= "<option value=\"TRUE\" >" . _( "TRUE" ) . "</option>\n";
						$lstrHTML .= "<option value=\"FALSE\" selected>" . _( "FALSE" ) . "</option>\n";
					}

					$lstrHTML .= "</select>\n";

					break;
				default:
					$lstrHTML .= "<input id=\"{$lstrKey}\" name=\"{$lstrKey}\" type=\"text\" value=\"{$lobjValues[$lstrKey]}\" ";

					switch( strtolower( $lobjOption[4] ) )
					{
						case 'medium':
							$lstrHTML .= "size=\"" . self::MEDIUM_INPUT_SIZE . "\" ";
							break;
						case 'small':
							$lstrHTML .= "size=\"" . self::SMALL_INPUT_SIZE . "\" ";
							break;
						default:
							$lstrHTML .= "size=\"" . self::LARGE_INPUT_SIZE . "\" ";
							break;
					}

					//append to auto generated options in order to discourage changing important
					//configurations
					if( in_array( $lstrKey, array( 'BaseURL', 'CKBasePath' ) ) )
					{
						$lstrHTML .= " disabled />\n<br /><span style=\"font-size: smaller\">**" . _( "This is automatically generated on installation" ) . ".
										<a onclick=\"javascript: enableTextBox(this);\" style=\"cursor: pointer; color: #C03957; text-decoration: underline;\" >" . _( "Not right?" ) . "</a></span>\n";
						break;
					}

					$lstrHTML .= "/>\n";
					break;
			}

			$lstrHTML .= "<br /><br />\n";

		}

		if( $lstrForm == 'db' )
		{
			?>
			<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
				<form id="config_form" action="setup-config.php" method="POST">
                    <div class="box required_field">
					<h2 class="bw_head"><?php echo _( "Database Configurations" ); ?></h2>

						<?php echo $lstrHTML; ?>
					</div>
                    <div class="box" align="center">
					<h2 class="bw_head"><?php echo _( "Save" ); ?></h2>

						<input type="submit" class="button" name="submit_setup_db_config" value="<?php echo _("Save Config"); ?>" />
					</div>
				</form>
			</div>
			<?php
		}

		if( $lstrForm == 'site' )
		{
			?>
			<div id="maincontent" style="max-width: 800px; margin-right: auto; margin-left: auto;">
				<form id="config_form" action="install.php?step=1" method="POST">
                        <div class="box required_field">
                        <h2 class="bw_head"><?php echo _( "SubjectsPlus Installation" ); ?></h2>

						<?php echo _( '<p>Welcome to the SubjetsPlus Installation. Please complete the following information so that we can continue with installation. These can be changed later.</p>' ) ?>
					</div>
                        <div class="box required_field">
					<h2 class="bw_head"><?php echo _( "Site Configurations" ); ?></h2>

						<?php echo $lstrHTML; ?>
					</div>

                    <div class="box" align="center">
					<h2 class="bw_head"><?php echo _( "Install" ); ?></h2>

						<input type="submit" class="button" name="submit_setup_site_config" value="<?php echo _("Install SubjectsPlus"); ?>" />
					</div>
				</form>
			</div>
			<?php
		}
	}

	/**
	 * sp_Config::setupConfigFile() - this method changes the lines in the file array
	 * property that contain the variable declaration for SubjectsPlus's config file.
	 *
	 * @return boolean
	 */
	private function setupConfigFile( )
	{
		//change globals for databse connection
		global $hname;
		global $uname;
		global $pword;
		global $dbName_SPlus;
	    global $db_port;

		$hname = $this->lobjNewConfigValues[ 'hname' ];
		$uname = $this->lobjNewConfigValues[ 'uname' ];
		$pword = $this->lobjNewConfigValues[ 'pword' ];
		$dbName_SPlus = $this->lobjNewConfigValues[ 'dbName_SPlus' ];
        $db_port =  $this->lobjNewConfigValues[ 'db_port' ];

		//if installing, change the salt of the SubjectsPlus config file
		if( $this->lboolChangeSalt )
		{
			$this->lobjConfigOptions[ 'salt' ] = array( "", "", "string", "", "", "", "" );
			$this->lobjNewConfigValues[ 'salt' ] = $this->generateRandomString( 11 );
		}

		//if installing, change the API key of the SubjectsPlus config file
		if( $this->lboolChangeAPIKey )
		{
			$this->lobjConfigOptions[ 'api_key' ] = array( "", "", "string", "", "", "", "" );
			$this->lobjNewConfigValues[ 'api_key' ] = $this->generateRandomString( 20, FALSE );
		}

		//go through file array to change the variables configurations
		foreach( $this->lobjConfigFile as $lintLineNumber => $lstrLine )
		{
			//determine whether current line contains a variable declaration at the beginning
			//of the line
			if ( ! preg_match( '/^\$([^ ]+)[ ]*=[ ]*(.+)[ ]*;/', $lstrLine, $lobjMatch ) )
				continue;

			//the matched string from the above preg_match should be a key of the options array
			//and continue only if the key in the options array exists
			if( isset( $this->lobjConfigOptions[ $lobjMatch[1] ] ) )
			{
				$lobjOption = $this->lobjConfigOptions[ $lobjMatch[1] ];

				//based on the type defined in the option array, write new declaration
				//of configuration variable
				switch( $lobjOption[2] )
				{
					case 'array':
						$this->lobjConfigFile[ $lintLineNumber ] = "\${$lobjMatch[1]} = array( ";

						$lobjList = $this->lobjNewConfigValues[ $lobjMatch[1] ];

						if( count( $lobjList ) == 0 )
						{
							$this->lobjConfigFile[ $lintLineNumber ] .= ");\r\n";
						}else
						{
							foreach( $lobjList as $lstrItem )
							{
								$this->lobjConfigFile[ $lintLineNumber ] .= '"' . $lstrItem . '", ';
							}

							//remove last charcter, which is an extra comma
							$this->lobjConfigFile[ $lintLineNumber ] = substr( $this->lobjConfigFile[ $lintLineNumber ], 0, ( strlen( $this->lobjConfigFile[ $lintLineNumber ] ) - 2 ) );
							$this->lobjConfigFile[ $lintLineNumber ] .= ");\r\n";
						}
						break;
					case 'aarray':
						$this->lobjConfigFile[ $lintLineNumber ] = "\${$lobjMatch[1]} = array( ";

						$lobjList = $this->lobjNewConfigValues[ $lobjMatch[1] ];

						if( count( $lobjList ) == 0 )
						{
							$this->lobjConfigFile[ $lintLineNumber ] .= ");\r\n";
						}else
						{
							foreach( $lobjList as $lstrKey => $lstrItem )
							{
								$this->lobjConfigFile[ $lintLineNumber ] .= '"' . $lstrKey . '"' . ' => "' . $lstrItem . '", ';
							}

							//remove last charcter, which is an extra comma
							$this->lobjConfigFile[ $lintLineNumber ] = substr( $this->lobjConfigFile[ $lintLineNumber ], 0, ( strlen( $this->lobjConfigFile[ $lintLineNumber ] ) - 2 ) );
							$this->lobjConfigFile[ $lintLineNumber ] .= ");\r\n";
						}
						break;
					case 'boolean':
						if( $this->lobjNewConfigValues[ $lobjMatch[1] ] )
						{
							$this->lobjConfigFile[ $lintLineNumber ] = "\${$lobjMatch[1]} = TRUE;\r\n";
						}else
						{
							$this->lobjConfigFile[ $lintLineNumber ] = "\${$lobjMatch[1]} = FALSE;\r\n";
						}
						break;
					default:
						$this->lobjConfigFile[ $lintLineNumber ] = "\${$lobjMatch[1]} = \"{$this->lobjNewConfigValues[ $lobjMatch[1] ]}\";\r\n";
						break;
				}
			}
		}

		return TRUE;
	}

	/**
	 * sp_Config::setConfigValues() - this method goes through the class property
	 * file array and looks for variable declarations and then matches them to the
	 * options property to find type and based on that type, if exists, create
	 * and set and element in the values property.
	 *
	 * @return void
	 */
	private function setConfigValues( )
	{
		$lobjValues = array();

		foreach( $this->lobjConfigFile as $lintLineNumber => $lstrLine )
		{
			//if not a variable declaration, continue to next line
			if ( ! preg_match( '/^\$([^ ]+)[ ]*=[ ]*([^;]*)[ ]*;/', $lstrLine, $lobjMatch ) )
				continue;

			if( isset( $this->lobjConfigOptions[ $lobjMatch[1] ] ) )
			{
				$lobjOption = $this->lobjConfigOptions[ $lobjMatch[1] ];

				//get value of variable depending on given type
				switch( $lobjOption[2] )
				{
					case 'array':
						preg_match( '/array\((.*)\)/' , $lobjMatch[2], $lobjElements );

						if( trim( $lobjElements[1] ) == '' )
						{
							$lobjValues[ $lobjMatch[1] ] = array();
						}else
						{
							$lobjElements = explode( ',', $lobjElements[1] );

							//remove first and last character (quotes)
							foreach( $lobjElements as $key => $lstrElement )
							{
								$lstrElement = trim( $lstrElement );
								$lstrElement = substr( $lstrElement, 1, ( strlen( $lstrElement ) - 2 ) );

								$lobjElements[ $key ] = $lstrElement;
							}

							$lobjValues[ $lobjMatch[1] ] = $lobjElements;
						}

						break;
					case 'aarray':
						preg_match( '/array\((.*)\)/' , $lobjMatch[2], $lobjElements );

						$lobjAArray = array();

						if( trim( $lobjElements[1] ) == '' )
						{
							$lobjValues[ $lobjMatch[1] ] = array();
						}else
						{
							$lobjElements = explode( ',', $lobjElements[1] );

							//remove first and last character (quotes)
							foreach( $lobjElements as $key => $lstrElement )
							{
								$lobjKeyValue = explode( '=>', $lstrElement );

								$lstrKey = trim( $lobjKeyValue[0] );
								$lstrValue = trim( $lobjKeyValue[1] );

								$lstrKey = substr( $lstrKey, 1, ( strlen( $lstrKey ) - 2 ) );
								$lstrValue = substr( $lstrValue, 1, ( strlen( $lstrValue ) - 2 ) );

								$lobjAArray[ $lstrKey ] = $lstrValue;
							}

							$lobjValues[ $lobjMatch[1] ] = $lobjAArray;
						}
						break;
					case 'boolean':
						$lstrValue = $lobjMatch[2];
						$lstrValue = trim($lstrValue);
						$lstrValue = strtolower($lstrValue);

						if($lstrValue == 'true')
						{
							$lobjValues[ $lobjMatch[1] ] = TRUE;
						}elseif( $lstrValue == 'false' )
						{
							$lobjValues[ $lobjMatch[1] ] = FALSE;
						}
						break;
					default:
						$lstrValue = $lobjMatch[2];
						$lstrValue = trim( $lstrValue );
						$lstrValue = substr( $lstrValue, 1, ( strlen( $lstrValue ) - 2 ) );

						$lobjValues[ $lobjMatch[1] ] = $lstrValue;

						break;
				}
			}
		}

		$this->lobjConfigValues = $lobjValues;
	}

	/**
	 * sp_Config::generateRandomString() - this method is used to generate a string of random characters
	 *
	 * @param integer $lintLength
	 * @return string
	 */
	private function generateRandomString( $lintLength = 10, $lboolSpecialCharacters = TRUE )
	{
		//list of vaild random characters; includes special characters depending on flag
		if( $lboolSpecialCharacters )
			$lstrCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#%^&*';
		else
			$lstrCharacters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$lstrRandomString = '';
		for ($i = 0; $i < $lintLength; $i++)
		{
			$lstrRandomString .= $lstrCharacters[rand(0, strlen($lstrCharacters) - 1)];
		}
		return $lstrRandomString;
	}

	/**
	 * sp_Config::arrayToCommaList() - this method converts an array to a comma separated list string
	 *
	 * @param array $lobjList
	 * @return string
	 */
	private function arrayToCommaList( $lobjList )
	{
		$lstrList = '';

		if( count( $lobjList ) == 0 ) return $lstrList;

		foreach( $lobjList as $lstrElement )
		{
			$lstrList .= $lstrElement . ',';
		}

		//remove last comma
		$lstrList = substr( $lstrList, 0, ( strlen( $lstrList ) - 1 ) );

		return $lstrList;
	}

	/**
	 * sp_Config::aarrayToCommaList() - this method converts an associative array to a comma separated list string
	 *
	 * @param array $lobjList
	 * @return string
	 */
	private function aarrayToCommaList( $lobjList )
	{
		$lstrList = '';

		if( count( $lobjList ) == 0 ) return $lstrList;

		foreach( $lobjList as $lstrKey => $lstrElement )
		{
			$lstrList .= $lstrKey . "=" . $lstrElement . ',';
		}

		//remove last comma
		$lstrList = substr( $lstrList, 0, ( strlen( $lstrList ) - 1 ) );

		return $lstrList;
	}

	/**
	 * sp_Config::commaListToArray() - this method converts a comma separated list string to an array
	 *
	 * @param string $lstrList
	 * @return array
	 */
	private function commaListToArray( $lstrList )
	{
		$lobjList = array();

		$lobjSplit = explode( ',', $lstrList );

		foreach( $lobjSplit as $lstrElement )
		{
			$lstrElement = trim( $lstrElement );

			if( $lstrElement != '' ) $lobjList[] = $lstrElement;
		}

		return $lobjList;
	}

	/**
	 * sp_Config::commaListToAArray() - this method converts a comma separated list string to an associative array
	 *
	 * @param string $lstrList
	 * @return array
	 */
	private function commaListToAArray( $lstrList )
	{
		$lobjList = array();

		$lobjSplit = explode( ',', $lstrList );

		foreach( $lobjSplit as $lstrElement )
		{
			$lstrElement = trim( $lstrElement );

			$lobjSplit2 = explode( "=", $lstrElement );

			if( $lstrElement != '' ) $lobjList[ $lobjSplit2[0] ] = $lobjSplit2[1];
		}

		return $lobjList;
	}

	/**
	 * sp_Config::arrayToTicks() - this method converts an array to a HTML string that displays the list
	 * as "ticks" which are clicked to turn options on and off for a passed input (aka lstrKey)
	 *
	 * @param array $lobjList
	 * @param array $lobjSelected
	 * @param string $lstrKey
	 * @return string
	 */
	private function arrayToTicks( $lobjList, $lobjSelected = array(), $lstrKey = "ticks" )
	{
		$lstrHTML = '';

		foreach( $lobjList as $lstrElement )
		{
			$lstrHTML .= "<span name=\"" . $lstrKey . "\" class=\"";

			//show the preselected configurations as on
			if( in_array( $lstrElement, $lobjSelected ) )
			{
				$lstrHTML .= 'ctag-on';
			}else
			{
				$lstrHTML .= 'ctag-off';
			}

			$lstrHTML .= "\">" . $lstrElement . "</span> ";
		}

		$lstrHTML .= "<input name=\"" . $lstrKey . "\" type=\"hidden\" value=\"" . $this->arrayToCommaList( $lobjSelected ) . "\" />" ;

		return $lstrHTML;
	}

	/**
	 * sp_Config::autoGenerateConfigs() - this method sets options of the values
	 * to auto-generated values rather than what is in the config file
	 *
	 * @return void
	 */
	private function autoGenerateConfigs()
	{
		$lstrURL = $_SERVER[ 'REQUEST_URI' ];
		$lobjSplit = explode( '/', $lstrURL );

		for( $i=(count($lobjSplit) - 1); $i>=0; $i-- )
		{
			$lstrItem = strtolower( trim( $lobjSplit[$i] ) );
			unset($lobjSplit[$i]);

			if( $lstrItem == 'control' )
			{
				$this->lobjConfigValues[ 'BaseURL' ] = urldecode( 'http://' . $_SERVER[ 'HTTP_HOST' ] . implode( '/', $lobjSplit ) . '/' );
				$this->lobjConfigValues[ 'CKBasePath' ] = urldecode( implode( '/', $lobjSplit ) .'/' . 'ckeditor' . '/' );
				$this->lobjConfigValues[ 'all_tbtags' ] = array( 'main' => '' );
				break;
			}
		}
	}
}

?>
