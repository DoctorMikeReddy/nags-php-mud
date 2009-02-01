<?
class COMMAND_LOGOUT
{
	var $NAME = "COMMAND_LOGOUT";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_LOGOUT(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"LOGOUT", "LOGOUT", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function LOGOUT($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $firstname = $this->PLAYER->GET_USER_FIRSTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $lastname = $this->PLAYER->GET_USER_LASTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));

          $this->MESSAGE->SYSMESSAGE("***User Logout: ".$firstname . " " . $lastname, "SYSTEM", "DISCONNECT");
          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$firstname." ".$lastname." has logged out.\n> ");
		$this->COMMUNICATION->SEND_TO_CHAR("Goodbye.\n");
          $this->SOCKET->set_socketinfo($socket, "MODE", "LOGOUT");
     }
}
?>