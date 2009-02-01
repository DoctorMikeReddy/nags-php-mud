<?
/*============================================================================*\
|
| CLASS INTERPRETER
|
|  DESCRIPTION:
|		INTERPRETER SYSTEM MODULE FOR NAGS GAMING SERVER
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
class INTERPRETER
{
     var $NAME = "INTERPRETER";
     var $ENABLED;

     function INTERPRETER(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE('INTERPRETER', 'SYSTEM', 'LOAD MODULE');
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
     function COMMAND_INTERPRETER($socket, $data)
     {
		$this->SYSTEM->CURRENT_SOCKET = $socket;
          $cmd = explode(' ',$data);
          //Check to see if this is an alias first...
          if(strtoupper(isset($this->ALIAS_ARRAY[strtoupper($cmd[0])])))
          {
          	$cmd[0] = $this->ALIAS_ARRAY[strtoupper($cmd[0])]['COMMAND'];
          }
          if(!$this->PFLAGS->GET_PLAYER_FLAG($this->ACTION_ARRAY[strtoupper($cmd[0])]['TYPE']))
          {
               $this->COMMUNICATION->SEND_TO_CHAR('Unknown command.\n');
          }else{
               if(isset($this->ACTION_ARRAY[strtoupper($cmd[0])]))
               {
	                    $this->{$this->ACTION_ARRAY[strtoupper($cmd[0])]['CLASS']}->{$this->ACTION_ARRAY[strtoupper($cmd[0])]['ACTION']}(implode(" ",array_slice($cmd, 1)));
               }elseif($data){
				if($this->SOCIALS->ACTION(strtoupper($cmd[0]), implode(" ",array_slice($cmd, 1))) != true)
				{
					$this->COMMUNICATION->SEND_TO_CHAR('Unknown command.\n');
				}
			}
          }
          return True;
     }
     function ADD_ACTION($CLASS, $COMMAND, $ACTION, $LEVEL, $TYPE)
     {
          $this->ACTION_ARRAY[$COMMAND] = array('CLASS'=>$CLASS,'ACTION'=>$ACTION, 'COMMAND'=>$COMMAND, 'LEVEL'=>$LEVEL,'TYPE'=>$TYPE);
          $this->MESSAGE->SYSMESSAGE('New command loaded: '.$COMMAND);
     }
     function REMOVE_ACTION($COMMAND)
     {
          unset($this->ACTION_ARRAY[$COMMAND]);
     }
     function ADD_ALIAS($ALIAS, $COMMAND)
     {
          $this->ALIAS_ARRAY[$ALIAS] = array('ALIAS'=>$ALIAS,'COMMAND'=>$COMMAND);
          $this->MESSAGE->SYSMESSAGE('Alias loaded: '.$ALIAS);
     }
     function REMOVE_ALIAS($ALIAS)
     {
          unset($this->ALIAS_ARRAY[$ALIAS]);
          $this->MESSAGE->SYSMESSAGE('Alias removed: '.$ALIAS);
     }
}
?>