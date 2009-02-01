<?
/*============================================================================*\
|
| CLASS ZONES
|
|  DESCRIPTION:
|		ZONES SYSTEM MODULE FOR NAGS GAMING SERVER
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
class ZONES
{
     var $NAME = "ZONES";
     var $ENABLED;
          /*   ENABLED = bool
               Denotes the class is active and available
          */
          
     function ZONES(&$sys_message)
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
     function CREATE_ZONE($zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("insert into zones set zone_id='".$zone."', zone_name='A New Zone';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_ZONE_TITLE($zone, $title)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update zones set zone_name='".mysql_real_escape_string($title)."' where zone_id='".$zone."';");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function GET_ZONE_TITLE($zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT zone_name FROM ZONES where zone_id='$zone';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_IDS_IN_ZONE($zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_RESULTS("SELECT user_id FROM players where zone='$zone';");
          return $return;
     }
     function LIST_PLAYERS_IN_ZONE($zone)
     {
          $user_ids_in_room = $this->ZONES->GET_USER_IDS_IN_ROOM($zone);
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          foreach($user_ids_in_zone as $user_id)
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