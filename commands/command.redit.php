<?
/*============================================================================*\
|
| CLASS REDIT
|
|  DESCRIPTION:
|		REDIT COMMAND FOR NAGS GAMING SERVER
|
|  REQUIREMENTS:
|		NAGS. This is a core command.
|
|  USAGE:
|		REDIT
|			This command enters the editor system and allows you to
|			edit information regarding the room you are currently in.
|
|  AUTHOR:
|		TERRY JAMES VALLADON
|
|  LICENSE:
|		Copyright (C) 2001-2004 by Terry Valladon (get-nags@terryvalladon.com)
|
|		This program is free software
|		you can redistribute it and/or modify
|		it under the terms of the GNU General Public License as published by
|		the Free Software Foundation; either version 2, or (at your option)
|		any later version.
|
|		This program is distributed in the hope that it will be useful,
|		but WITHOUT ANY WARRANTY; without even the implied warranty of
|		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|		GNU General Public License for more details.
|
|		You should have received a copy of the GNU General Public License
|		along with this program; if not, write to the Free Software
|		Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
\*============================================================================*/
class COMMAND_REDIT
{
	var $NAME = "COMMAND_REDIT";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_REDIT";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
	var $ROOM;
    
     function COMMAND_REDIT(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"REDIT", "REDIT", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG("BUILDER");
          $this->PFLAGS->ADD_FLAG("SYSADMIN");
          $this->PFLAGS->ADD_FLAG("HEADBUILDER");
          $this->PFLAGS->ADD_FLAG("BUILDER_ZONE");
          return True;
     }
     function REDIT()
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
     	if(($this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE") == $zone AND $this->PFLAGS->GET_PLAYER_FLAG("BUILDER") == true) OR $this->PFLAGS->GET_PLAYER_FLAG("SYSADMIN") == true OR $this->PFLAGS->GET_PLAYER_FLAG("HEADBUILDER") == true)
     	{
			$this->ROOM[$socket]['room'] = $room;
			$this->ROOM[$socket]['zone'] = $zone;
			$this->ROOM[$socket]['title'] = $this->ROOMS->GET_ROOM_TITLE($zone, $room);
			$this->ROOM[$socket]['descr'] = $this->ROOMS->GET_ROOM_DESCR($zone, $room);
			$this->ROOM[$socket]['flags'] = $flags;
			$this->DISPLAY_MENU();
	          $this->SOCKET->set_socketinfo($socket, 'MODE', 'ROOM_EDITOR');
	          $this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
		}else{
			if($this->PFLAGS->GET_PLAYER_FLAG("BUILDER_ZONE") == "")
			{
				$this->COMMUNICATION->SEND_TO_CHAR("You are not assigned to a zone so you may not build.\n");
			}elseif($this->PFLAGS->GET_PLAYER_FLAG("BUILDER") != true){
				$this->COMMUNICATION->SEND_TO_CHAR("You have not been assigned as a builder so you may not build.\n");
			}else{
	          	$this->COMMUNICATION->SEND_TO_CHAR("\FRYou are not allowed to build in this zone.\SN\n");
	          }
		}
     }
	function REDIT_INTERPRETER($socket, $data)
	{
		$this->SYSTEM->CURRENT_SOCKET = $socket;
		$room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
		$zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
		$mode = $this->SOCKET->GET_SOCKETINFO($socket, 'MODE');
		$submode = $this->SOCKET->GET_SOCKETINFO($socket, 'SUBMODE');

		if($submode == "MENU")
			switch(strtoupper(trim($data))):
				case 'A':
			          $this->COMMUNICATION->SEND_TO_CHAR("Type new room title now (80 chars max): ");
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'ROOM_TITLE');
					break;
				case 'B':
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'ROOM_DESCR');
					$this->EDITOR->INIT_EDITOR($this->ROOM[$socket]['descr']);
					break;
				case 'Z':
					$this->COMMUNICATION->SEND_TO_CHAR("\SCRoom Saved.\n");
					$this->SOCKET->set_socketinfo($socket, 'MODE', 'PLAYING');
					$this->SOCKET->set_socketinfo($socket, 'SUBMODE', '');
					$this->ROOMS->SET_ROOM_TITLE($zone, $room, $this->ROOM[$socket]['title']);
					$this->ROOMS->SET_ROOM_DESCR($zone, $room, $this->ROOM[$socket]['descr']);
          			unset($this->ROOM[$socket]);
					break;
				case 'X':
			          $this->COMMUNICATION->SEND_TO_CHAR("\SCRoom Not Saved.\n");
			          $this->SOCKET->set_socketinfo($socket, 'MODE', 'PLAYING');
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', '');
          			unset($this->ROOM[$socket]);
					break;
				default:
					$this->DISPLAY_MENU();
					break;
			endswitch;

		if($submode == "ROOM_TITLE")
			switch(trim($data)):
				case '':
			          $this->COMMUNICATION->SEND_TO_CHAR("\FRRoom title may not be blank.\SN\n");
			          $this->COMMUNICATION->SEND_TO_CHAR("Type new room title now (80 chars max): ");
					break;
				default:
					$this->ROOM[$socket]['title'] = trim($data);
          			$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
					$this->DISPLAY_MENU();
					break;
		endswitch;

		if($submode == "ROOM_DESCR")
		{
			switch($data):
				case '':
					if($this->SOCKET->GET_SOCKETINFO($socket, 'EDITOR_RETURN') == 'true')
					{
						$this->ROOM[$socket]['descr'] = $this->SOCKET->GET_SOCKETINFO($socket, 'EDITOR_BUFFER');
						$this->SOCKET->set_socketinfo($socket, 'EDITOR_RETURN', '');
						$this->SOCKET->set_socketinfo($socket, 'EDITOR_BUFFER', '');
						$this->SOCKET->set_socketinfo($socket, 'MODE', 'ROOM_EDITOR');
						$this->SOCKET->set_socketinfo($socket, 'SUBMODE', 'MENU');
						$this->DISPLAY_MENU();
					}
					break;
			endswitch;
		}
	}
	function DISPLAY_MENU()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $this->COMMUNICATION->SEND_TO_CHAR("\SC\FW================================================================================\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FWRoom Number: \FR".$this->ROOM[$socket]['room']."\t\t\t\t\t\FWZone Number: \FR".$this->ROOM[$socket]['zone']."\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRA\FW) Room Title: \FY".$this->ROOM[$socket]['title']."\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRB\FW) Room Description:\FY\n".$this->ROOM[$socket]['descr']."\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRC\FW) Room Flags: \FY\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\n\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FW================================================================================\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FRX\FW)\FY Cancel\t\t\FRZ\FW)\FY Save\SN\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FW================================================================================\n");
          $this->COMMUNICATION->SEND_TO_CHAR("\FREdit\FY > \FW");
	}
}
?>