<?
/*============================================================================*\
|
| CLASS PFLAGS
|
|  DESCRIPTION:
|		PLAYER FLAGS SYSTEM MODULE FOR NAGS GAMING SERVER
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
class PFLAGS
{
     var $NAME = "PFLAGS";
     var $ENABLED;
     var $PFLAG_ARRAY = Array();

     function PFLAGS(&$sys_message)
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
     function ADD_FLAG($FLAG)
     {
     	if($FLAG != "" AND in_array($FLAG, $this->PFLAG_ARRAY) == false)
     	{
	          $this->PFLAG_ARRAY[$FLAG] = array('FLAG'=>$FLAG);
          	$this->MESSAGE->SYSMESSAGE('New Player Flag Loaded: '.$FLAG);
          }
     }
     function REMOVE_FLAG($FLAG)
     {
          unset($this->PFLAG_ARRAY[$FLAG]);
     }
	function GET_PLAYER_FLAG($flag)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		$user_id = $this->SOCKET->GET_SOCKETINFO($socket, 'USER_ID');
		if($flag == '')
		{
			return true;
		}else{
			$split_flags = explode(", ", $flag);
			$sql_string = "select value from player_flags where user_id='$user_id' and flag IN (";
			foreach($split_flags as $value)
			{
				$sql_string .= "'$value',";
			}
			$sql_string = substr($sql_string, 0, -1);
			$sql_string .= ");";
			$flags = $this->DATABASE->GET_VAR($sql_string);
			switch ($flags):
				case 'true':
					return true;
				case 'false':
					return false;	
				default:
					return $flags;
			endswitch;
		}
	}
}
?>