<?
/*============================================================================*\
|
| CLASS PFLAGS
|
|  DESCRIPTION:
|		PFLAGS COMMAND FOR NAGS GAMING SERVER
|
|  REQUIREMENTS:
|		NAGS. This is a core command.
|
|  USAGE:
|
|  AUTHOR:
|		TERRY JAMES VALLADON
|
|  LICENSE:
|		Copyright (C) 2001-2009 by Terry Valladon (get-nags@terryvalladon.com)
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
class COMMAND_PFLAGS
{
	var $NAME = "COMMAND_PFLAGS";
     var $ENABLED;
     var $REQUIRED_FLAG = "USE_PFLAGS";
          /*   ENABELED = bool
               Denotes the class is active and available
          */
    
     function COMMAND_PFLAGS(&$sys_message)
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
          $this->INTERPRETER->ADD_ACTION($this->NAME,"PFLAGS", "PFLAGS", "", $this->REQUIRED_FLAG);
          $this->PFLAGS->ADD_FLAG($this->REQUIRED_FLAG);
          return True;
     }
     function PFLAGS($message)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		
          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
          foreach($this->PFLAGS->PFLAG_ARRAY as $FLAG)
          {
			$this->COMMUNICATION->SEND_TO_Q("\FW".$FLAG['FLAG']."\SN\n");
          }
          $this->COMMUNICATION->SEND_TO_Q("========================================\n");
		$this->COMMUNICATION->WRITE_Q();
     }  
}
?>