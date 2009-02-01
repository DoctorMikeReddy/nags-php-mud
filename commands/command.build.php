<?
class COMMAND_BUILD
{
	var $NAME = "COMMAND_BUILD";
	var $ENABLED;
	var $REQUIRED_FLAG = "USE_BUILD";
		/*	ENABELED = bool
			Denotes the class is active and available
		*/

	function COMMAND_BUILD(&$sys_message)
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
		$this->INTERPRETER->ADD_ACTION($this->NAME,"BUILD", "BUILD", "", $this->REQUIRED_FLAG);
		$this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
		$this->PFLAGS->ADD_FLAG("BUILDER");
		$this->PFLAGS->ADD_FLAG("SYSADMIN");
		$this->PFLAGS->ADD_FLAG("HEADBUILDER");
		$this->PFLAGS->ADD_FLAG("BUILDER_ZONE");
		return True;
	}

	function BUILD($target="")
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$cmd = explode(" ",$target);
		if($this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE") <> null OR $this->PFLAGS->GET_PLAYER_FLAG("SYSADMIN") == true OR $this->PFLAGS->GET_PLAYER_FLAG("HEADBUILDER") == true)
		{
			switch (strtoupper($cmd[0])):
				case 'CREATE':
					switch (strtoupper($cmd[1])):
						case 'ROOM':
							if($this->PFLAGS->GET_PLAYER_FLAG("SYSADMIN") == true OR $this->PFLAGS->GET_PLAYER_FLAG("HEADBUILDER") == true)
							{
								if(isset($cmd[2]))
								{
									$zone = $cmd[2];
									$new_room_id = $this->DATABASE->GET_VAR("select room_id from rooms where zone_id='".$zone."' order by room_id desc limit 1")+1;
									$this->ROOMS->CREATE_ROOM($zone, $new_room_id);
									$this->COMMUNICATION->SEND_TO_CHAR("New room created in zone ".$zone." with a room number of ".$new_room_id."\n");
								}else{
									$this->COMMUNICATION->SEND_TO_CHAR("System Admin and Head Builder must include the zone they wish to create the room in.\n");
								}
							}else{
								$zone = $this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE");
								$new_room_id = $this->DATABASE->GET_VAR("select room_id from rooms where zone_id='".$zone."' order by room_id desc limit 1")+1;
								$this->ROOMS->CREATE_ROOM($zone, $new_room_id);
								$this->COMMUNICATION->SEND_TO_CHAR("New room created in zone ".$zone." with a room number of ".$new_room_id."\n");
							}
							break;
						case 'ZONE':
							if($this->PFLAGS->GET_PLAYER_FLAG("SYSADMIN") == true OR $this->PFLAGS->GET_PLAYER_FLAG("HEADBUILDER") == true)
							{
								$new_zone_id = $this->DATABASE->GET_VAR("select zone_id from zones order by zone_id desc limit 1")+1;
								$new_room_id = 0;
								$this->ZONES->CREATE_ZONE($new_zone_id);
								$this->ROOMS->CREATE_ROOM($new_zone_id, $new_room_id);
								$this->COMMUNICATION->SEND_TO_CHAR("New zone created: ".$new_zone_id."\n");
								$this->COMMUNICATION->SEND_TO_CHAR("New room created in zone ".$new_zone_id." with a room number of ".$new_room_id."\n");
							}else{
								$this->COMMUNICATION->SEND_TO_CHAR("\FROnly SYSADMIN or HEADBUILDER are allowed to create zones.\SN\n");
							}
							break;
					endswitch;
					break;
				case 'DELETE':
					switch (strtoupper($cmd[1])):
						case 'ROOM':
								$this->COMMUNICATION->SEND_TO_CHAR("Delete Room Function\n");
							break;
						case 'ZONE':
								$this->COMMUNICATION->SEND_TO_CHAR("Delete Zone Function\n");
							break;
					endswitch;
					break;
				default:
					$this->COMMUNICATION->SEND_TO_CHAR("You are allowed to build in zone: ".$this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE")." `".$this->DATABASE->GET_VAR("select zone_name from zones where zone_id='".$this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE")."'")."`\n");
					$this->COMMUNICATION->SEND_TO_CHAR("Highest room in zone ".$this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE")." is: ".$this->DATABASE->GET_VAR("select room_id from rooms where zone_id='".$this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE")."' order by room_id desc limit 1")."\n");
					break;
			endswitch;
		}else{
			$this->COMMUNICATION->SEND_TO_CHAR("You are not assigned to a zone so you may not build.\n");
		}
	}
}
?>