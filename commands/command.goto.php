<?
class COMMAND_GOTO
{
	var $NAME = "COMMAND_GOTO";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_GOTO";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_GOTO(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"GOTO", "GOTO", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          $this->INTERPRETER->ADD_ALIAS("GO", "GOTO");
          return True;
     }
     function GOTO($target)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $target = strtoupper($target);
          $cmd = explode(" ",$target);

		if($cmd[0] == "ROOM")
		{
	          if($cmd[1] == "" OR $cmd[2] == "") //No zone or room number? where does he want to go?
	          {
	               $this->COMMUNICATION->SEND_TO_CHAR("Where do you want to go? format: GOTO  ROOM <ZONE> <ROOM>\n");
	          }else{ //Ok, now we have a room and zone.. lets make sure the room exists (get the room title) 
	               $room_name = $this->ROOMS->GET_ROOM_TITLE($cmd[1], $cmd[2]);
	               if($room_name == False)
	               {
	                    $this->COMMUNICATION->SEND_TO_CHAR("That room does not exist.\n");
	               }else{
	                    $this->PLAYER->SET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket), $cmd[1]);
	                    $this->PLAYER->SET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket), $cmd[2]);
	                    $this->INFORMATIVE->LOOK();               
	               }
	          }
		}elseif($cmd[0] == "PLAYER"){
               $user_id = $this->PLAYER->GET_UID_BY_FIRSTNAME_LASTNAME($cmd[1], $cmd[2]);
               if($user_id <>"")
               {
				if($this->PLAYER->IS_ONLINE($cmd[1]." ".$cmd[2]) == True)
				{
					$room = $this->PLAYER->GET_USER_ROOM($user_id);
					$zone = $this->PLAYER->GET_USER_ZONE($user_id);
					$this->PLAYER->SET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket), $zone);
					$this->PLAYER->SET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket), $room);
					$this->INFORMATIVE->LOOK();
				}else{
					$this->COMMUNICATION->SEND_TO_CHAR("They are not online right now.\n");
				}
               }else{
                    $this->COMMUNICATION->SEND_TO_CHAR("No player was found in the system with this name.\n");
               }
		}else{
			$this->COMMUNICATION->SEND_TO_CHAR("Take you to a room or player.\n\tUsage: goto room <zone number> <room number> OR goto player <player name>\n\tIE: goto player john doe\n\tIE: goto room 0 0\n");
		}
     }
}
?>