<?
class COMMAND_OPEN
{
	var $NAME = "COMMAND_OPEN";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_OPEN(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"OPEN", "OPEN", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function OPEN($target)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $target = strtoupper($target);
          $cmd = explode(" ",$target);
		if($target != "")
		{
			if(trim($cmd[0]) == "DOOR")
			{
				$direction = $this->MOVEMENT->GET_DIRECTION_CODE($cmd[1]);
				if($direction == "0")
				{
			          $this->COMMUNICATION->SEND_TO_CHAR("Please specify the direction the door is in.\n");
				}else{
			          $firstname = $this->PLAYER->GET_USER_FIRSTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
	          		$lastname = $this->PLAYER->GET_USER_LASTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
				     $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
				     $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
					if($this->EXITS->EXIT_EXISTS($zone, $room, $direction))
					{
						if($this->EXITS->EXIT_HAS_DOOR($zone, $room, $direction))
						{
					          if($this->EXITS->IS_DOOR_CLOSED($zone, $room, $direction))
					          {
								if($this->EXITS->IS_DOOR_LOCKED($zone, $room, $direction))
								{
						          	$this->COMMUNICATION->SEND_TO_CHAR("That door is locked.\n");
						          }else{
						          	$this->EXITS->OPEN_DOOR($zone, $room, $direction);
						          	$this->EXITS->OPEN_DOOR($this->EXITS->GET_EXIT_DEST_ZONE($zone, $room, $direction), $this->EXITS->GET_EXIT_DEST_ROOM($zone, $room, $direction), $this->MOVEMENT->GET_REVERSE_DIRECTION($direction));
						          	$this->COMMUNICATION->SEND_TO_CHAR("The door is now open.\n");
						          	$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$firstname." ".$lastname." opens the door.\n");
							          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "> ");
						          	$this->COMMUNICATION->SEND_TO_ROOM($this->EXITS->GET_EXIT_DEST_ZONE($zone, $room, $direction), $this->EXITS->GET_EXIT_DEST_ROOM($zone, $room, $direction), "\nThe door opens from the other side.\n");
							          $this->COMMUNICATION->SEND_TO_ROOM($this->EXITS->GET_EXIT_DEST_ZONE($zone, $room, $direction), $this->EXITS->GET_EXIT_DEST_ROOM($zone, $room, $direction), "> ");
						     	}
					          }else{
						          $this->COMMUNICATION->SEND_TO_CHAR("The door is already open.\n");
					          }
						}else{
					          $this->COMMUNICATION->SEND_TO_CHAR("There is no door in that direction.\n");
						}
					}else{
				          $this->COMMUNICATION->SEND_TO_CHAR("There is no exit in that direction.\n");
					}
				}
			}else{
	          	$this->COMMUNICATION->SEND_TO_CHAR("You do not have a '$cmd[0]'\n");
			}
		}else{
          	$this->COMMUNICATION->SEND_TO_CHAR("What do you want to open?\n");
		}
     }  
}
?>