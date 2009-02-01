<?
/*============================================================================*\
|
| CLASS ROOMS
|
|  DESCRIPTION:
|		ROOMS SYSTEM MODULE FOR NAGS GAMING SERVER
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
class ROOMS
{
     var $NAME = "ROOMS";
     var $ENABLED;
          /*   ENABLED = bool
               Denotes the class is active and available
          */
          
     function ROOMS(&$sys_message)
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
     function CREATE_ROOM($zone, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("insert into rooms set room_id='".$room."', zone_id='".$zone."', room_name='A new room', room_descr='Your room desc goes here.';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_ROOM_TITLE($zone, $room, $title)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update rooms set room_name='".mysql_real_escape_string($title)."' where room_id='".$room."' and zone_id='".$zone."';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_ROOM_DESCR($zone, $room, $descr)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update rooms set room_descr='".mysql_real_escape_string($descr)."' where room_id='".$room."' and zone_id='".$zone."';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function GET_ROOM_TITLE($zone, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT room_name FROM rooms where zone_id='$zone' and room_id='$room';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_ROOM_DESCR($zone, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT room_descr FROM rooms where zone_id='$zone' and room_id='$room';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_IDS_IN_ROOM($zone, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_RESULTS("SELECT user_id FROM players where zone='$zone' and room='$room';");
          return $return;
     }
     function LIST_PLAYERS_IN_ROOM($zone, $room)
     {
          $user_ids_in_room = $this->ROOMS->GET_USER_IDS_IN_ROOM($zone, $room);
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          foreach($user_ids_in_room as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
                    {
                         if($client != $socket)
                         {
                              $returncode = $returncode . $this->PLAYER->GET_USER_FIRSTNAME($user_id[0]) . ' ' . $this->PLAYER->GET_USER_LASTNAME($user_id[0]) . '\n';
                         }
                    }
               }
          }
          return $returncode;
     }
}
?>