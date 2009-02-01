<?
class COMMAND_ALIAS
{
	var $NAME = "COMMAND_ALIAS";
	var $ENABLED;
	var $REQUIRED_FLAG = "";
		/*	ENABELED = bool
			Denotes the class is active and available
		*/
	
	function COMMAND_ALIAS(&$sys_message)
	{
		$this->MESSAGE =& $sys_message;
		$this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "LOAD COMMAND");
		$this->ENABLED = True;
		return True;
	}

	function INITIALIZE($OBJECT_ARRAY)
	{
		foreach($OBJECT_ARRAY as $key => &$value)
		{
			if(!$this->{$key} =& $value){return False;}
		}
		$this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "INIT COMMAND");
		return True;
	}

	function LOAD_COMMANDS()
	{
		$this->INTERPRETER->ADD_ACTION($this->NAME,"ALIAS", "ALIAS", "", $this->REQUIRED_FLAG);
		$this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
		return True;
	}

	function ALIAS($message)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$this->COMMUNICATION->SEND_TO_CHAR("Loaded Aliases:\n");
		foreach($this->INTERPRETER->ALIAS_ARRAY as $item)
		{
				$this->COMMUNICATION->SEND_TO_CHAR("\t".$item['ALIAS']."\t=>\t".$item['COMMAND']."\n");
		}
	}
}
?>