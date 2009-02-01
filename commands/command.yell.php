<?
class COMMAND_YELL
{
	var $NAME = "COMMAND_YELL";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_YELL(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"YELL", "YELL", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          $this->INTERPRETER->ADD_ALIAS("!", "YELL");
          return True;
     }
     function YELL($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $firstname = $this->PLAYER->GET_USER_FIRSTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $lastname = $this->PLAYER->GET_USER_LASTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $send_message = "\nYou hear ".$firstname." ".$lastname." yell out, `$message\SN`\n";
          $send_self_message = "You yell out, `$message\SN`\n";
          $this->COMMUNICATION->SEND_TO_ALL_EXCEPT($send_message);
          $this->COMMUNICATION->SEND_TO_ALL_EXCEPT("> ");
          $this->COMMUNICATION->SEND_TO_CHAR($send_self_message);
	}  
}
?>