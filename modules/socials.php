<?
/*============================================================================*\
|
| CLASS SOCIALS
|
|  DESCRIPTION:
|		SOCIAL SYSTEM MODULE FOR NAGS GAMING SERVER
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
class SOCIALS
{
     var $NAME = "SOCIALS";
     var $ENABLED;
         
     function SOCIALS(&$sys_message)
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
     function action($action, $target='')
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $room = $this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_SOCKET($socket));
          $zone = $this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_SOCKET($socket));
     	$char_array = $this->DATABASE->GET_ROW("SELECT first_name, last_name, sex FROM players where user_id='".$this->PLAYER->GET_UID_BY_SOCKET($socket)."';");
     	$action_array = $this->DATABASE->GET_ROW("SELECT * FROM socials where social_command='$action';");
     	if($this->DATABASE->NUM_ROWS() == 0)
     	{
     		return false;
     	}
     	$char = array(
     				'first_name'	=>	$char_array->first_name,
     				'last_name'	=>	$char_array->last_name,
     				'sex'		=>	$char_array->sex
     			   );
		if($target=='')
		{
	     	if($action_array->act_char_no_vict!='')
	     	{
		     	$this->COMMUNICATION->SEND_TO_CHAR($this->COMMUNICATION->PARSE_ACTION($action_array->act_char_no_vict."\n", $char));
		     }
	     	if($action_array->act_room_no_vict!='')
	     	{
	     		$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_room_no_vict."\n", $char));
		          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\FY> \SN");
		     }
	     }else{
	     	if($this->PLAYER->IS_ONLINE($this->PLAYER->GET_USER_FULLNAME($this->PLAYER->GET_UID_BY_FIRSTNAME($target))))
	     	{
	     		$vict_socket = $this->PLAYER->GET_SOCKET_BY_UID($this->PLAYER->GET_UID_BY_FIRSTNAME($target));
	     		if($vict_socket == $socket)
	     		{
			     	if($action_array->act_char_vict_self!='')
			     	{
			     		$this->COMMUNICATION->SEND_TO_CHAR($this->COMMUNICATION->PARSE_ACTION($action_array->act_char_vict_self."\n", $char));
			     	}
			     	if($action_array->act_room_vict_self!='')
			     	{
				     	$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_room_vict_self."\n", $char));
				          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\FY> \SN");
				     }
				     return true;
	     			return true;
	     		}
	     		if($vict_socket != '')
	     		{
			     	$vict_array = $this->DATABASE->GET_ROW("SELECT first_name, last_name, sex FROM players where user_id='".$this->PLAYER->GET_UID_BY_FIRSTNAME($target)."';");
		     		$vict = array(
     					'first_name'	=>	$vict_array->first_name,
     					'last_name'	=>	$vict_array->last_name,
     					'sex'		=>	$vict_array->sex
	    					);
	    				if($vict['first_name'] == '' or  $room!=$this->PLAYER->GET_USER_ROOM($this->PLAYER->GET_UID_BY_FIRSTNAME($target)) OR $zone!=$this->PLAYER->GET_USER_ZONE($this->PLAYER->GET_UID_BY_FIRSTNAME($target)))
	    				{
				     	if($action_array->act_char_vict_not_found!='')
				     	{
				     		$this->COMMUNICATION->SEND_TO_CHAR($this->COMMUNICATION->PARSE_ACTION($action_array->act_char_vict_not_found."\n", $char));
				     	}
				     	if($action_array->act_room_vict_not_found!='')
				     	{
					     	$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_room_vict_not_found."\n", $char));
					          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\FY> \SN");
					     }
					     return true;
					}
			     	if($action_array->act_char_vict_found!='')
			     	{
			     		$this->COMMUNICATION->SEND_TO_CHAR($this->COMMUNICATION->PARSE_ACTION($action_array->act_char_vict_found."\n", $char, $vict));
			     	}
			     	if($action_array->act_vict_vict_found!='')
			     	{
				     	$this->COMMUNICATION->SEND_TO_CHAR("\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_vict_vict_found."\n", $char, $vict), $vict_socket);
				          $this->COMMUNICATION->SEND_TO_char("\FY> \SN", $vict_socket);
				     }
			     	if($action_array->act_room_vict_found!='')
			     	{
				     	$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_room_vict_found."\n", $char, $vict), array($this->PLAYER->GET_UID_BY_SOCKET($socket), $this->PLAYER->GET_UID_BY_SOCKET($vict_socket)));
				          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\FY> \SN", array($this->PLAYER->GET_UID_BY_SOCKET($socket), $this->PLAYER->GET_UID_BY_SOCKET($vict_socket)));
					}
	     		}else{
			     	if($action_array->act_char_vict_not_found!='')
			     	{
			     		$this->COMMUNICATION->SEND_TO_CHAR($this->COMMUNICATION->PARSE_ACTION($action_array->act_char_vict_not_found."\n", $char));
			     	}
			     	if($action_array->act_room_vict_not_found!='')
			     	{
				     	$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_room_vict_not_found."\n", $char));
				          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\FY> \SN");
				     }
	     		}
	     	}else{
		     	if($action_array->act_char_vict_not_found!='')
		     	{
			     	$this->COMMUNICATION->SEND_TO_CHAR($this->COMMUNICATION->PARSE_ACTION($action_array->act_char_vict_not_found."\n", $char));
			     }
		     	if($action_array->act_room_vict_not_found!='')
		     	{
			     	$this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\n".$this->COMMUNICATION->PARSE_ACTION($action_array->act_room_vict_not_found."\n", $char));
			          $this->COMMUNICATION->SEND_TO_ROOM_EXCEPT($zone, $room, "\FY> \SN");
			     }
	     	}
	     }
	     return true;
     }
}
?>