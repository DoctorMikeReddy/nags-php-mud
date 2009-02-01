<?
/*============================================================================*\
|
| CLASS MOVEMENT
|
|  DESCRIPTION:
|		MOVEMENT SYSTEM MODULE FOR NAGS GAMING SERVER
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
class MOVEMENT
{
     var $NAME = "MOVEMENT";
     var $ENABLED;

     function MOVEMENT(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE($this->NAME, 'SYSTEM', 'LOAD MODULE');
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
     function GO_THROUGH_EXIT($direction)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $firstname = $this->PLAYER->GET_USER_FIRSTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
		$lastname = $this->PLAYER->GET_USER_LASTNAME($this->PLAYER->GET_UID_BY_SOCKET($socket));
	     $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
	     $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
	     if($this->EXITS->EXIT_EXISTS($zone, $room, $direction))
	     {
	          if($this->EXITS->CAN_USE_EXIT($zone, $room, $direction))
	          {
		          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$firstname." ".$lastname." leaves ".$this->GET_DIRECTION_NAME($direction).".\n");
		          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "> ");
				$this->PLAYER->SET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket), $this->EXITS->GET_EXIT_DEST_ZONE($zone, $room, $direction));
				$this->PLAYER->SET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket), $this->EXITS->GET_EXIT_DEST_ROOM($zone, $room, $direction));
		          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($this->EXITS->GET_EXIT_DEST_ZONE($zone, $room, $direction), $this->EXITS->GET_EXIT_DEST_ROOM($zone, $room, $direction), "\n".$firstname." ".$lastname." has arived.\n");
		          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($this->EXITS->GET_EXIT_DEST_ZONE($zone, $room, $direction), $this->EXITS->GET_EXIT_DEST_ROOM($zone, $room, $direction), "> ");
	               $this->INFORMATIVE->LOOK();               
	          }else{
		          $this->COMMUNICATION->SEND_TO_CHAR("The door is closed.\n");
	          }
	     }else{
	          $this->COMMUNICATION->SEND_TO_CHAR("There are no exits leading ".$this->GET_DIRECTION_NAME($direction).".\n");
		}
     }
     function GET_REVERSE_DIRECTION($direction)
     {
     	$direction = strtoupper(trim($direction));
     	$reverse_direction = Array("NO"=>"SO", "SO"=>"NO", "EA"=>"WE", "WE"=>"EA", "NE"=>"SW", "NW"=>"SE", "SE"=>"NW", "SW"=>"NE", "UP"=>"DO", "DO"=>"UP");
 		if(!array_key_exists(strtoupper(trim($direction)), $reverse_direction))
 		{
 			return 0;
 		}else{
 			return $reverse_direction[strtoupper(trim($direction))];
 		}
     }
     function GET_DIRECTION_CODE($direction)
     {
     	$direction = strtoupper(trim($direction));
 		$direction_code = Array("N"=>"NO", "NORTH"=>"NO", "S"=>"SO", "SOUTH"=>"SO", "E"=>"EA", "EAST"=>"EA", "W"=>"WE", "WEST"=>"WE", "NE"=>"NE", "NORTHEAST"=>"NE", "NW"=>"NW", "NORTHWEST"=>"NW", "SE"=>"SE", "SOUTHEAST"=>"SE", "SW"=>"SW", "SOUTHWEST"=>"SW", "U"=>"UP", "UP"=>"UP", "D"=>"DO", "DOWN"=>"DO");
 		if(array_key_exists($direction, $direction_code))
 		{
 			return $direction_code[strtoupper(trim($direction))];
 		}else{
 			return 0;
 		}
     }
     function GET_DIRECTION_NAME($direction)
     {
     	$direction = strtoupper(trim($direction));
     	$direction_name = Array("NO"=>"North", "SO"=>"South", "EA"=>"East", "WE"=>"West", "NE"=>"NorthEast", "NW"=>"NorthWest", "SE"=>"SouthEast", "SW"=>"SouthWest", "UP"=>"Up", "DO"=>"Down");
 		if(!array_key_exists(strtoupper(trim($direction)), $direction_name))
 		{
 			return 0;
 		}else{
 			return $direction_name[strtoupper(trim($direction))];
 		}
     }
}
?>