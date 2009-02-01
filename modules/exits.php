<?
/*============================================================================*\
|
| CLASS EXITS
|
|  DESCRIPTION:
|		EXITS SYSTEM MODULE FOR NAGS GAMING SERVER
|
|  REQUIREMENTS:
|		NAGS. This is a core module.
|
|  USAGE:
|
|  AUTHOR:
|		TERRY JAMES VALLADON
|
|  LICENSE:
|		Copyright (C) 2007-2010 by Terry Valladon (get-nags@terryvalladon.com)
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
class EXITS
{
     var $ENABLED;
          /*   ENABLED = bool
               Denotes the class is active and available
          */
     var $MESSAGE;
     var $SOCKET;
     var $CURRENT_SOCKET;
          
     function EXITS(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE('EXITS', 'SYSTEM', 'LOAD MODULE');
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, 'SYSTEM', 'INIT MODULE');
          return True;
     }
     function LIST_EXITS_IN_ROOM($zone, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$count=$this->DATABASE->GET_VAR("SELECT count(direction) from exits where zone=$zone and room=$room;");
		if($count <> 0)
		{
	          $exits_in_room = $this->DATABASE->GET_RESULTS("select direction from exits where zone=$zone and room=$room;");
	          foreach($exits_in_room as $exits)
	          {
	          	switch($exits[0]):
	          		case 'NO':
						$returncode = $returncode . 'North';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "NO"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'SO':
						$returncode = $returncode . 'South';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "SO"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'EA':
						$returncode = $returncode . 'East';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "EA"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'WE':
						$returncode = $returncode . 'West';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "WE"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'UP':
						$returncode = $returncode . 'Up';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "UP"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'DO':
						$returncode = $returncode . 'Down';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "DO"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'NE':
						$returncode = $returncode . 'Northeast';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "NE"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'NW':
						$returncode = $returncode . 'Northwest';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "NW"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'SE':
						$returncode = $returncode . 'Southeast';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "SE"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
	          		case 'SW':
						$returncode = $returncode . 'Southwest';
						if($this->EXITS->IS_DOOR_CLOSED($zone, $room, "SW"))
						{
							$returncode = $returncode . ' \FR(CLOSED)\SN';
						}
						$returncode = $returncode . ', ';
						break;
				endswitch;
	          }
			$returncode = substr($returncode, 0, -2);
		}else{
			$returncode = "None";	
		}
          return $returncode.'\n';
     }
     function EXIT_EXISTS($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$count=$this->DATABASE->GET_VAR("SELECT count(direction) from exits where zone=$zone and room=$room and direction='$direction';");
		if($count <> 0)
		{
			return true;
		}else{
			return false;
		}
     }
     function CAN_USE_EXIT($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_RESULTS("SELECT door, closed from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit[0][0] == 1)
		{
			if($exit[0][1] == 1)
			{
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
     }
     function EXIT_HAS_DOOR($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_VAR("SELECT door from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit == 1)
		{
			return true;
		}else{
			return false;
		}
     }
     function IS_DOOR_LOCKED($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_VAR("SELECT locked from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit == 1)
		{
			return true;
		}else{
			return false;
		}
     }
     function IS_DOOR_UNLOCKED($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_VAR("SELECT locked from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit == 0)
		{
			return true;
		}else{
			return false;
		}
     }
     function IS_DOOR_CLOSED($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_VAR("SELECT closed from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit == 1)
		{
			return true;
		}else{
			return false;
		}
     }
     function IS_DOOR_OPENED($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_VAR("SELECT closed from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit == 0)
		{
			return true;
		}else{
			return false;
		}
     }
     function IS_DOOR_LOCKABLE($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->GET_VAR("SELECT lockable from exits where zone=$zone and room=$room and direction='$direction';");
		if($exit == 1)
		{
			return true;
		}else{
			return false;
		}
     }
     function CLOSE_DOOR($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->QUERY("update exits set closed='1' where zone=$zone and room=$room and direction='$direction';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function OPEN_DOOR($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->QUERY("update exits set closed='0' where zone=$zone and room=$room and direction='$direction';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function LOCK_DOOR($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->QUERY("update exits set LOCKED='1' where zone=$zone and room=$room and direction='$direction';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function UNLOCK_DOOR($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->QUERY("update exits set LOCKED='0' where zone=$zone and room=$room and direction='$direction';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_LOCK_OWNER($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$uid = $this->PLAYER->GET_UID_BY_SOCKET($socket);
		$exit=$this->DATABASE->QUERY("update exits set lock_owner='$uid' where zone=$zone and room=$room and direction='$direction';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function GET_LOCK_OWNER($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$uid = $this->PLAYER->GET_UID_BY_SOCKET($socket);
		return $this->DATABASE->GET_VAR("select lock_owner from exits where zone=$zone and room=$room and direction='$direction';");
     }
     function CLEAR_LOCK_OWNER($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$exit=$this->DATABASE->QUERY("update exits set lock_owner=null where zone=$zone and room=$room and direction='$direction';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function GET_EXIT_DEST_ZONE($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		return $this->DATABASE->GET_VAR("SELECT dest_zone from exits where zone=$zone and room=$room and direction='$direction';");
     }
     function GET_EXIT_DEST_ROOM($zone, $room, $direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		return $this->DATABASE->GET_VAR("SELECT dest_room from exits where zone=$zone and room=$room and direction='$direction';");
     }

}
?>