<?
/*============================================================================*\
|
| CLASS SYSTEM
|
|  DESCRIPTION:
|		SERVER SYSTEM CONTROL MODULE FOR NAGS GAMING SERVER
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
class SYSTEM
{
     var $ENABLED;
          /*   ENABLED = bool
               Denotes the class is active and available
          */
     var $MESSAGE;
     var $SOCKET;
     var $CURRENT_SOCKET;
          
     function SYSTEM(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE('SYSTEM', 'SYSTEM', 'LOAD MODULE');
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
     function SHUTDOWN($message='Server shutting down for maintanance....' )
     {
          if(trim($message) == '')
          {
               $message='Server shutting down for maintanance....';
          }else{
               $message = trim($message);
          }
          $this->MESSAGE->SYSMESSAGE('***System Shutdown: '.$message, 'SYSTEM', 'SHUTDOWN');
          $this->COMMUNICATION->SEND_TO_ALL($this->MESSAGE->ANSI_FG['RED'].'***System Shutdown: ' . $message.$this->MESSAGE->ANSI_SPECIAL['NORMAL']);
          $this->SOCKET->close();
          exit;
     }
     function LOGOUT($socket, $message = 'Connection link dropped by peer.')
     {
	   	$this->SYSTEM->CURRENT_SOCKET = $socket;
		$this->DATABASE->DISCONNECT_DATABASE();
          $this->MESSAGE->SYSMESSAGE($socket.' '.$message, 'SYSTEM', 'DISCONNECT');
	     $this->SOCKET->close($socket);
          return;
     }
}
?>