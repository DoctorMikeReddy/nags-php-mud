<?
/*============================================================================*\
|
| CLASS COMMUNICATION
|
|  DESCRIPTION:
|		BASIC COMMUNICATION SYSTEM MODULE FOR NAGS GAMING SERVER
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
class COMMUNICATION
{
	var $NAME = "COMMUNICATION";
     var $ENABLED;
          /*   ENABLED = bool
               Denotes the class is active and available
          */
     
     function COMMUNICATION(&$sys_message)
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
     function SEND_TO_ALL($message)
     {
          foreach($this->SOCKET->clients as $client)
          {
               $this->SOCKET->WRITE($client,parse('FULL', $message));
          }
          return;
     }
     function SEND_TO_ALL_EXCEPT($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          foreach($this->SOCKET->clients as $client)
          {
               if($client != $socket)
               {
				$this->COMMUNICATION->SEND_TO_CHAR($message, $client);
               }
          }
          return;
     }
     function SEND_PROMPT($socket)
     {
		$this->SYSTEM->CURRENT_SOCKET = $socket;
          $this->SOCKET->write ($socket, parse('FULL', '\SN\FY> \FW'));
          return;
     }
     function SEND_TO_CHAR($message, $socket = '')
     {
     	if($socket == ''){$socket = $this->SYSTEM->CURRENT_SOCKET;}
          $this->SOCKET->write($socket,parse('FULL', $message));
          return;
     }
     function SEND_TO_CHAR_RAW($message, $socket = '')
     {
     	if($socket == ''){$socket = $this->SYSTEM->CURRENT_SOCKET;}
          $this->SOCKET->write ($socket,$message);
          return;
     }
     function SEND_TO_ROOM($zone, $room, $message)
     {
          $user_ids_in_room = $this->ROOMS->GET_USER_IDS_IN_ROOM($zone, $room);
          if(isset($user_ids_in_room[0]))
          {
	          foreach($user_ids_in_room as $user_id)
	          {
	               foreach($this->SOCKET->clients as $client)
	               {
	                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
	                    {
	                         $this->COMMUNICATION->SEND_TO_CHAR($message, $client);
	                    }
	               }
	          }
		}
          return;
     }
     function SEND_TO_ROOM_EXCEPT($zone, $room, $message, $exception='')
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $user_ids_in_room = $this->ROOMS->GET_USER_IDS_IN_ROOM($zone, $room);
          if($exception == '')
          {
	          foreach($user_ids_in_room as $user_id)
	          {
	               foreach($this->SOCKET->clients as $client)
	               {
	                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
	                    {
	                         if($client != $socket)
	                         {
	                              $this->COMMUNICATION->SEND_TO_CHAR($message, $client);
	                         }
	                    }
	               }
	          }
	     }else{
	          foreach($user_ids_in_room as $user_id)
	          {
	               foreach($this->SOCKET->clients as $client)
	               {
	                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
	                    {
	                         if(!in_array($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID'), $exception))
	                         {
	                              $this->COMMUNICATION->SEND_TO_CHAR($message, $client);
	                         }
	                    }
	               }
	          }
	     }          return;
     }
     function SEND_TO_Q($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $this->SOCKET->SET_SOCKETINFO($socket, 'QUE', $this->SOCKET->GET_SOCKETINFO($socket, 'QUE') . $message);
     }
     function WRITE_Q()
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $this->SEND_TO_CHAR($this->SOCKET->GET_SOCKETINFO($socket, 'QUE'));
          $this->COMMUNICATION->SOCKET->SET_SOCKETINFO($socket, 'QUE', '');
     }



     function SEND_TO_ALL_OUTDOOR(){}
     //function SEND_TO_ROOM(){}
     function SEND_TO_ZONE($zone, $message)
	{
          $user_ids_in_zone = $this->DATABASE->GET_USER_IDS_IN_ZONE($zone);
          foreach($user_ids_in_zone as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
                    {
                         $this->COMMUNICATION->SEND_TO_CHAR($message, $client);
                    }
               }
          }
          return;
	}
     function SEND_TO_OUTDOOR_ZONE($zone, $message)/*chnage*/
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $user_ids_in_zone = $this->DATABASE->GET_USER_IDS_IN_ZONE($zone);
          foreach($user_ids_in_zone as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
                    {
                         if($client != $socket)
                         {
                              $this->COMMUNICATION->SEND_TO_CHAR($message, $client);
                         }
                    }
               }
          }
          return;
	}
     function SEND_TO_ZONE_EXCEPT($zone, $message)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $user_ids_in_zone = $this->DATABASE->GET_USER_IDS_IN_ZONE($zone);
          foreach($user_ids_in_zone as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
                    {
                         if($client != $socket)
                         {
                              $this->COMMUNICATION->SEND_TO_CHAR($message, $client);
                         }
                    }
               }
          }
          return;
	}
	function PARSE_HESHEIT($sex)
	{
		switch(ucfirst($sex)):
			case 'M':
				$return_value = "he";
				break;
			case 'F':
				$return_value = "she";
				break;
			default:
				$return_value = "it";
				break;
		endswitch;
		return $return_value;
	}
	function PARSE_HIMHERIT($sex)
	{
		switch(ucfirst($sex)):
			case 'M':
				$return_value = "him";
				break;
			case 'F':
				$return_value = "her";
				break;
			default:
				$return_value = "it";
				break;
		endswitch;
		return $return_value;
	}
	function PARSE_HISHERITS($sex)
	{
		switch(ucfirst($sex)):
			case 'M':
				$return_value = "his";
				break;
			case 'F':
				$return_value = "her";
				break;
			default:
				$return_value = "its";
				break;
		endswitch;
		return $return_value;
	}
	function PARSE_ACTION($input, $char, $vict='')
	{
		/*
			$char and $vict = arrays with the following format:
			array(
				'first_name'=>'Tom',
				'last_name'=>'Jones',
				'sex'=>'M' or 'F'
			)				
		*/
		/*
		%a = actor name (lower case)
		%A = actor name (proper case)
		%v = vice name (lower case)
		%V = vict name (proper case)
		%h = actor (He, She, It)
		%H = vict (He, She, It)
		%e = actor (Him, Her, It)
		%E = vict (Him, Her, It)
		%s = actor (His, Hers, Its)
		%S = vict (His, Hers, Its)
		*/
		
		if(isset($char) AND is_array($char) AND count($char) != 0)
		{
			$input = str_replace('%a', strtolower($char['first_name'])." ".strtolower($char['last_name']), $input);// replace %a
			$input = str_replace('%A', ucfirst($char['first_name'])." ".ucfirst($char['last_name']), $input);//replace %A
			$input = str_replace('%h', $this->COMMUNICATION->PARSE_HESHEIT($char['sex']), $input);//replace %h
			$input = str_replace('%e', $this->COMMUNICATION->PARSE_HIMHERIT($char['sex']), $input);//replace %e
			$input = str_replace('%s', $this->COMMUNICATION->PARSE_HISHERITS($char['sex']), $input);//repalce %s
		}else{
			return false;	
		}
		
		if(isset($vict) AND is_array($vict) AND count($vict) != 0)
		{
			$input = str_replace('%v', strtolower($vict['first_name'])." ".strtolower($vict['last_name']), $input);// replace %a
			$input = str_replace('%V', ucfirst($vict['first_name'])." ".ucfirst($vict['last_name']), $input);//replace %A
			$input = str_replace('%H', $this->COMMUNICATION->PARSE_HESHEIT($vict['sex']), $input);//replace %h
			$input = str_replace('%E', $this->COMMUNICATION->PARSE_HIMHERIT($vict['sex']), $input);//replace %e
			$input = str_replace('%S', $this->COMMUNICATION->PARSE_HISHERITS($vict['sex']), $input);//repalce %s
		}		
		return $input;	
	}
}
?>