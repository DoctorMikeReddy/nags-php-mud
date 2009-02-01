<?
/*============================================================================*\
|
| CLASS DATABASE
|
|  DESCRIPTION:
|		DATABASE SYSTEM MODULE FOR NAGS GAMING SERVER
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
class DATABASE
{
     var $ENABLED;
          /*   ENABELED = bool
               Denotes the class is active and available
          */
	var $DB = array();
	var $num_rows_array = array();
     
     function DATABASE(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE('DATABASE', 'SYSTEM', 'LOAD MODULE');
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
     function LOAD_DATABASE_SYSTEM()
     {
          require('helpers/ez_sql_core.php');
          require('helpers/ez_sql_mysql.php');
     }
	function CONNECT_DATABASE()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $this->DB[$socket] = new ezSQL_mysql();
          if(!$this->DB[$socket]->quick_connect($this->CONFIGURE->CONFIG['DB_USERNAME'],$this->CONFIGURE->CONFIG['DB_PASSWORD'],$this->CONFIGURE->CONFIG['DB_DATABASE'],$this->CONFIGURE->CONFIG['DB_HOST']))
          {
               $this->MESSAGE->SYSMESSAGE('COULD NOT CONNECT TO DATABASE, CHECK CONFIG VALUES', 'SYSTEM', 'FATAL ERROR');
               exit;
          }else{
               $this->MESSAGE->SYSMESSAGE('DATBASE CONNECTION MADE FOR SOCKET '.$socket, 'SYSTEM', 'NOTICE');
          }
	}
	function DISCONNECT_DATABASE()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $this->MESSAGE->SYSMESSAGE('DATBASE CONNECTION REMOVED FOR SOCKET '.$socket, 'SYSTEM', 'NOTICE');
          unset($this->DB[$socket]);
	}
	function GET_VAR($query)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          return $this->DB[$socket]->GET_VAR($query);
	}
	function QUERY($query)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          return $this->DB[$socket]->QUERY($query);
	}
	function GET_RESULTS($query, $format=ARRAY_N)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          return $this->DB[$socket]->GET_RESULTS($query, $format);
	}
	function GET_ROW($query)
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          return $this->DB[$socket]->GET_ROW($query);
	}
	function ROWS_AFFECTED()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          return $this->DB[$socket]->ROWS_AFFECTED;
	}
	function NUM_ROWS()
	{
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          return $this->DB[$socket]->num_rows;
	}

     function GET_USER_IDS_IN_ZONE($zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_RESULTS("SELECT user_id FROM players where zone='$zone';");
          return $return;
     }
}
?>