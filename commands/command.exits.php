<?
class COMMAND_EXITS
{
	var $NAME = "COMMAND_EXITS";
     var $ENABLED;
     var $REQUIRED_FLAG = "";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_EXITS(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"EXITS", "EXITS", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function EXITS($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		if($message != "")
		{
          	$this->COMMUNICATION->SEND_TO_CHAR("No arguments are supported.\n");
          }else{
	          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
	          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
			$count=$this->DATABASE->GET_VAR("SELECT count(direction) from exits where zone=$zone and room=$room;");
			if($count <> 0)
			{
		          $exits_in_room = $this->DATABASE->GET_RESULTS("SELECT exits.direction, rooms.room_name, exits.dest_zone, exits.dest_room FROM exits Inner Join rooms ON exits.dest_zone=rooms.zone_id AND exits.dest_room = rooms.room_id WHERE exits.zone='".$zone."' AND exits.room='".$room."'");
		          foreach($exits_in_room as $exits)
		          {
		          	switch($exits[0]):
		          		case 'NO':
							$returncode = $returncode . 'North\t\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "NO"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'SO':
							$returncode = $returncode . 'South\t\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "SO"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'EA':
							$returncode = $returncode . 'East\t\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "EA"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'WE':
							$returncode = $returncode . 'West\t\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "WE"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'UP':
							$returncode = $returncode . 'Up\t\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "UP"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'DO':
							$returncode = $returncode . 'Down\t\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "DO"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'NE':
							$returncode = $returncode . 'Northeast\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "NE"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'NW':
							$returncode = $returncode . 'Northwest\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "NW"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'SE':
							$returncode = $returncode . 'Southeast\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "SE"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
		          		case 'SW':
							$returncode = $returncode . 'Southwest\t';
							if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "SW"))
							{
								$returncode = $returncode . '\FR(CLOSED)\SN\t';
							}else{
								$returncode = $returncode . '\t\t';
							}
							$returncode = $returncode .$exits[1]. '\n';
							break;
					endswitch;
		          }
		          $this->COMMUNICATION->SEND_TO_CHAR($returncode);
			}
          }
     }  
}
?>