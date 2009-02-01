<?
/*============================================================================*\
|
| CLASS PLAYER
|
|  DESCRIPTION:
|		PLAYER MODULE FOR NAGS GAMING SERVER
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
class PLAYER
{
     var $ENABLED;
          /*   ENABELED = bool
               Denotes the class is active and available
          */
     
     function PLAYER(&$sys_message)
     {
          $this->MESSAGE =& $sys_message;
          $this->MESSAGE->SYSMESSAGE("PLAYER", "SYSTEM", "LOAD MODULE");
          $this->ENABLED = True;
          return True;
     }
     function INITIALIZE($OBJECT_ARRAY)
     {
          foreach($OBJECT_ARRAY as $key => &$value)
          {
               if(!$this->{$key} =& $value){return False;}
          }
          $this->MESSAGE->SYSMESSAGE($this->NAME, "SYSTEM", "INIT MODULE");
          return True;
     }
     function GET_PLAYER_BY_UID($uid)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
		return $this->DATABASE->GET_ROW("select username, password, first_name, last_name, email, sex, level, zone, room from players where user_id='".$uid."'");
     }
     function SET_PLAYER_USERNAME($uid, $username)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set username='".mysql_real_escape_string($username)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_PASSWORD($uid, $password)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set password=md5('".mysql_real_escape_string($password)."') where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_FIRSTNAME($uid, $firstname)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set first_name='".mysql_real_escape_string($firstname)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_LASTNAME($uid, $lastname)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set last_name='".mysql_real_escape_string($lastname)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_SEX($uid, $sex)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set sex='".mysql_real_escape_string($sex)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_EMAIL($uid, $email)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set email='".mysql_real_escape_string($email)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_LEVEL($uid, $level)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set level='".mysql_real_escape_string($level)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_ROOM($uid, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set room='".mysql_real_escape_string($room)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_PLAYER_ZONE($uid, $zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set zone='".mysql_real_escape_string($zone)."' where user_id='".$uid."'");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function GET_USER_USERNAME($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT username FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_PASSWORD($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT PASSWORD FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_FIRSTNAME($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT first_name FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_LASTNAME($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT LAST_NAME FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
	function GET_USER_FULLNAME($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT CONCAT(FIRST_NAME, ' ', LAST_NAME) AS FULLNAME FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_EMAIL($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT EMAIL FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_SEX($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT SEX FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_LEVEL($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT LEVEL FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_ZONE($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT ZONE FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_ROOM($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT ROOM FROM players where user_id='$user_id';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_USER_FLAGS($userid)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_RESULTS("SELECT FLAG, VALUE from player_flags where user_id='$userid';");
          if($this->DATABASE->NUM_ROWS() != 0)
          {
	          foreach($return as $pflag)
	          {
	          	$returncode .= "\t".str_pad($pflag[0], 20, " ")."\t".$pflag[1]."\n";
	          }
		}
          return $returncode;
     }
     function GET_UID_BY_USERNAME($username)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT user_id FROM players where username='$username';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_UID_BY_FIRSTNAME_LASTNAME($firstname, $lastname)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT user_id FROM players where first_name='$firstname' and last_name='$lastname';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function GET_UID_BY_FIRSTNAME($firstname)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT user_id FROM players where first_name='$firstname';");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               return $return;
          }
          return false;
     }
     function SET_USER_ZONE($user_id, $zone)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set zone='$zone' where user_id=$user_id;");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function SET_USER_ROOM($user_id, $room)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->QUERY("update players set room='$room' where user_id=$user_id;");
          if($this->DATABASE->ROWS_AFFECTED() == 1)
          {
               return True;
          }
          return false;
     }
     function LIST_PLAYERS_ONLINE()
     {
          foreach($this->PLAYER->GET_ALL_USER_IDS() as $user_id)
          {
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id[0])
                    {
					$returncode = $returncode . $this->PLAYER->GET_USER_FIRSTNAME($user_id[0]) . ' ' . $this->PLAYER->GET_USER_LASTNAME($user_id[0]) . '\n';
                    }
               }
          }
          return $returncode;
     }
     function IS_ONLINE($target)
     {
          $target = strtoupper($target);
          $cmd = explode(' ',$target);
          if($cmd[1] == '')
          { //No last name, must be asking by username... 
               $user_id = $this->PLAYER->GET_UID_BY_USERNAME($cmd[0]);
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id)
                    {
                         return True;
                    }
               }
          }else{ //not self, not username.. must be first and last name... 
               $user_id = $this->PLAYER->GET_UID_BY_FIRSTNAME_LASTNAME($cmd[0], $cmd[1]);
               foreach($this->SOCKET->clients as $client)
               {
                    if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id)
                    {
                         return True;
                    }
               }
          }
          return False;
     }
     function VERIFY_ACCOUNT($username, $password)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_VAR("SELECT user_id FROM players where username='$username' and password=md5('$password');");
          if($this->DATABASE->NUM_ROWS() == 1)
          {
               $this->SOCKET->set_socketinfo($socket, 'USER_ID', $return);
               return true;
          }
          return false;
     }
     function GET_UID_BY_SOCKET($socket)
     {
          return $this->SOCKET->GET_SOCKETINFO($socket, 'USER_ID');
     }
     function GET_SOCKET_BY_UID($user_id)
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return_value = "";
          foreach($this->SOCKET->clients as $client)
          {
               if($this->SOCKET->GET_SOCKETINFO($client, 'USER_ID') == $user_id)
               {
                    $return_value = $client;
               }
          }
          return $return_value;
     }     
     function GET_ALL_USER_IDS()
     {
		$socket = $this->SYSTEM->CURRENT_SOCKET;
          $return = $this->DATABASE->GET_RESULTS("SELECT user_id FROM players;");
          return $return;
     }
}
?>