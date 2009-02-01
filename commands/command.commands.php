<?
class COMMAND_COMMANDS
{
	var $NAME = "COMMAND_COMMANDS";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_COMMANDS(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"COMMANDS", "COMMANDS", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function COMMANDS($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$this->COMMUNICATION->SEND_TO_CHAR("Loaded Commands:\n");
		$x=0;
		foreach($this->INTERPRETER->ACTION_ARRAY as $item)
		{
	          if($this->PFLAGS->GET_PLAYER_FLAG($item['TYPE']))
	          {
				if($x == 10)
				{
					$this->COMMUNICATION->SEND_TO_CHAR($item['COMMAND']."\n");
					$x=0;
				}else{
					$this->COMMUNICATION->SEND_TO_CHAR($item['COMMAND'].", ");
					$x++;
				}
	          }
		}
		if($x != 0)
		{
			$this->COMMUNICATION->SEND_TO_CHAR("\n");
		}
     }  
}
?>