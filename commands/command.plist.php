<?
class COMMAND_PLIST
{
	var $NAME = "COMMAND_PLIST";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_PLIST";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_PLIST(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"PLIST", "PLAYER_LIST", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function PLAYER_LIST($target)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$player_list = $this->PLAYER->GET_ALL_USER_IDS();
		
          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
		foreach($player_list as $player)
		{
			$this->COMMUNICATION->SEND_TO_Q("\FW".$player[0]."\t\FR(\FY".$this->PLAYER->GET_USER_USERNAME($player[0])."\FR) \FW".$this->PLAYER->GET_USER_FULLNAME($player[0])."\SN\n");
		}
          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
		$this->COMMUNICATION->WRITE_Q();
	}
}
?>