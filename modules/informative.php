<?
/*============================================================================*\
|
| CLASS INFORMATIVE
|
|  DESCRIPTION:
|		INFORMATIVE SYSTEM MODULE FOR NAGS GAMING SERVER
|		THIS MODULE HANDLES "LOOKING" and "SEEING" 
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
class INFORMATIVE
{
     function INFORMATIVE(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE("INFORMATIVE", "SYSTEM", "LOAD MODULE");
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "INIT MODULE");
          return True;
     }
     function LOOK($target = "")
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          if($target == "")
          {
               $user_id = $this->PLAYER->GET_UID_BY_SOCKET($socket);         
               $this->INFORMATIVE->LOOK_AT_ROOM($this->PLAYER->GET_USER_ZONE($user_id),$this->PLAYER->GET_USER_ROOM($user_id));
          }
     }
     function LOOK_AT_ROOM($zone, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $this->COMMUNICATION->SEND_TO_Q("\FG".$this->ROOMS->GET_ROOM_TITLE($zone, $room)."\SN\n");
          $this->COMMUNICATION->SEND_TO_Q($this->ROOMS->GET_ROOM_DESCR($zone, $room)."\n");
          $this->COMMUNICATION->SEND_TO_Q("\FYAlso in this room:\SN\n");
          $this->COMMUNICATION->SEND_TO_Q("\FG".$this->ROOMS->LIST_PLAYERS_IN_ROOM($zone, $room)."\SN");
          $this->COMMUNICATION->SEND_TO_Q("\FRExits:\SN\n");
          $this->COMMUNICATION->SEND_TO_Q($this->EXITS->LIST_EXITS_IN_ROOM($zone, $room));
          $this->COMMUNICATION->WRITE_Q();
     }

}
?>