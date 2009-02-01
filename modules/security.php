<?
/*============================================================================*\
|
| CLASS SECURITY
|
|  DESCRIPTION:
|		SECURITY SYSTEM MODULE FOR NAGS GAMING SERVER
|		THIS MODULE HANDLES ACL AND AUTHENTICATION
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
class SECURITY
{
     var $ENABLED;
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function SECURITY(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE('SECURITY', 'SYSTEM', 'LOAD MODULE');
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
     function LOGIN($socket, $data = '')
     {
		$this->SYSTEM->CURRENT_SOCKET = $socket;
		switch($this->SOCKET->GET_SOCKETINFO($socket, 'MODE')):
			case 'LOGIN':
				$this->COMMUNICATION->SEND_TO_CHAR('\SC\SNWelcome to '.$this->CONFIGURE->CONFIG['SERVER_NAME'].'\n');
				$this->COMMUNICATION->SEND_TO_CHAR('Please Login.\n');
				$this->COMMUNICATION->SEND_TO_CHAR('\FWUsername: ');
				$this->SOCKET->set_socketinfo($socket, 'MODE', 'USERNAME');
				break;
          	case 'USERNAME':
	               if(!eregi('^[a-zA-Z0-9]{5,24}$', $data)){
	                    $this->COMMUNICATION->SEND_TO_CHAR('\FRInvalid username, may only contain upper and lower case characters and numbers.\n');
					$this->COMMUNICATION->SEND_TO_CHAR('\FRUsername must be between 5 and 24 characters long.\n');
	                    $this->COMMUNICATION->SEND_TO_CHAR('\FWUsername: ');
	                    return False;
	               }else{
	                    $this->SOCKET->set_socketinfo($socket, 'USERNAME', $data);
	                    $this->SOCKET->set_socketinfo($socket, 'MODE', 'PASSWORD');
	                    $this->COMMUNICATION->SEND_TO_CHAR('\FWPassword: ');
	                    return True;
	               }
	               break;
          	case 'PASSWORD':
	               if(!eregi('^[a-zA-Z0-9]{5,24}$', $data)){
	                    $this->COMMUNICATION->SEND_TO_CHAR('\FRInvalid password, may only contain upper and lower case characters and numbers.\n');
					$this->COMMUNICATION->SEND_TO_CHAR('\FRPassword must be between 5 and 24 characters long.\n');
	                    $this->COMMUNICATION->SEND_TO_CHAR('\FWPassword: ');
	                    return False;
	               }else{
	                    $this->SOCKET->set_socketinfo($socket, 'PASSWORD', $data);
	                    $this->SOCKET->set_socketinfo($socket, 'MODE', 'VERIFY');
	                    $this->COMMUNICATION->SEND_TO_CHAR('\FGPlease wait while we verify your account...\n\n');                         
	                    if($this->PLAYER->VERIFY_ACCOUNT($this->SOCKET->get_socketinfo($socket, 'USERNAME'),$this->SOCKET->get_socketinfo($socket, 'PASSWORD')))
	                    {
	                         $this->COMMUNICATION->SEND_TO_CHAR('\FGYou are now playing, thank you and have fun.\n\n');
	                         $this->INFORMATIVE->LOOK();
	                         $this->SOCKET->set_socketinfo($socket, 'MODE', 'PLAYING');
						return False;
					}else{
	                         $this->SOCKET->set_socketinfo($socket, 'USERNAME', '');
	                         $this->SOCKET->set_socketinfo($socket, 'PASSWORD', '');
	                         $this->SOCKET->set_socketinfo($socket, 'MODE', 'USERNAME');
	                         $this->COMMUNICATION->SEND_TO_CHAR('\FRLogin failed, please try again.\n');                         
	                         $this->COMMUNICATION->SEND_TO_CHAR('\FWUsername: ');                         
	                         return False;
	                    }
	               }
	               break;
		endswitch;		
     }
}
?>