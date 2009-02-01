<?
class COMMAND_SHOUT
{
	var $NAME = "COMMAND_SHOUT";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_SHOUT(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"SHOUT", "SHOUT", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function SHOUT($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		if(trim($message)=="")
		{
	          $this->COMMUNICATION->SEND_TO_CHAR("You stand there with your mouth hanging open, no words coming out.\n");
		}else{
	          $firstname = $this->DATABASE->GET_USER_FIRSTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
	          $lastname = $this->PLAYER->GET_USER_LASTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
	          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
	          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
	          $send_message = "\n$firstname $lastname shouts, `$message\SN`\n";
	          $send_self_message = "You shout, `$message\SN`\n";
	          $this->COMMUNICATION->SEND_TO_ZONE_EXCEPT($zone, $send_message);
	          $this->COMMUNICATION->SEND_TO_ZONE_EXCEPT($zone, "> ");
	          $this->COMMUNICATION->SEND_TO_CHAR($send_self_message);
		}
	}  
}
?>