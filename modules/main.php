<?
/*============================================================================*\
|
| CLASS MAIN
|
|  DESCRIPTION:
|		MAIN GAME SYSTEM MODULE FOR NAGS GAMING SERVER
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
class MAIN
{
	var $MODULE_LIST = array(
						'MESSAGE',
						'CONFIGURE',
						'DATABASE',
						'SOCKET',
						'SYSTEM',
						'COMMUNICATION',
						'PLUGINS',
						'INTERPRETER',
						'SYSOP',
						'PLAYER',
						'INFORMATIVE',
						'SECURITY',
						'CHOICE',
						'MOVEMENT',
						'PFLAGS',
						'EDITOR',
						'ZONES',
						'ROOMS',
						'EXITS',
						'SOCIALS'
						);		//Create and fill array for modules. Only modules in this list will be loaded.
	var $PLUGIN_LIST = array();		//Create array for holding plugins. Every plugin in the directory will be loaded.
	var $COMMAND_LIST = array();		//Create array for holding commands. Every command in the directory will be loaded.
	var $SYSTEM_IO;
              
	function MAIN()
	{
		$this->LOAD_MODULES();
		$this->LOAD_PLUGINS();
		$this->LOAD_COMMANDS();
		$this->MESSAGE->SYSMESSAGE('MAIN', 'SYSTEM', 'LOAD MODULE');
		$this->ENABLED = True;
		$this->MESSAGE->SYSMESSAGE('','', 'BLANK');
		$this->INITIALIZE_MODULES_PLUGINS_AND_COMMANDS();
		$this->MESSAGE->SYSMESSAGE('MAIN', 'SYSTEM', 'INIT MODULE');
		$this->MESSAGE->SYSMESSAGE('','', 'BLANK');
		$this->INIT_COMMANDS();
		$this->MESSAGE->SYSMESSAGE('','', 'BLANK');
		return True;
	}

	function MAIN_PROGRAM_LOOP()
	{
		error_reporting (7);
		set_time_limit (0);
		ob_implicit_flush ();

		$this->CONFIGURE->CONFIG_LOAD('./config.php');
		/*Load System Configuration*/
		$this->DATABASE->LOAD_DATABASE_SYSTEM();

		$this->SYSTEM_IO = $this->SOCKET->LISTEN($this->CONFIGURE->CONFIG['IP_ADDRESS'],$this->CONFIGURE->CONFIG['SERVER_PORT']);
		$this->MESSAGE->SYSMESSAGE('NAGS Server running.');
		$this->MESSAGE->SYSMESSAGE('HOST:'.$this->SOCKET->myhost.' PORT:'.$this->SOCKET->myport);
		$this->MESSAGE->SYSMESSAGE('Awating Connections...');
		
		while(1):
			usleep(200);
			foreach($this->SOCKET->CAN_READ() as $sock)
			{
				if($this->SYSTEM_IO == $sock):
					if($sock = $this->SOCKET->ACCEPT()):
						//New  Connection
					   	$this->SYSTEM->CURRENT_SOCKET = $sock;
						$this->DATABASE->CONNECT_DATABASE();
						$this->SOCKET->set_socketinfo($sock, 'MODE', 'LOGIN');
						$this->SECURITY->LOGIN($sock);
					endif;
				else:
					//Anyone had something to say
					$data = $this->SOCKET->READ($sock);
					//Client pinged out
					if($data === false):
						$this->SYSTEM->LOGOUT($sock);
						continue;
					else:
						switch($this->SOCKET->GET_SOCKETINFO($sock, 'MODE')):
							case "EDITOR":
								$this->EDITOR->EDITOR_INTERPRETER($sock, $data);
								break;
						endswitch;
						switch($this->SOCKET->GET_SOCKETINFO($sock, 'MODE')):
							case 'LOGIN':
							case 'USERNAME':
							case 'PASSWORD':
								$this->SECURITY->LOGIN($sock, $data);
								break;
							case 'PLAYING':
								$this->INTERPRETER->COMMAND_INTERPRETER($sock, $data);
								break;
							case "ROOM_EDITOR":
								$this->COMMAND_REDIT->REDIT_INTERPRETER($sock, $data);
								break;
							case "PLAYER_EDITOR":
								$this->COMMAND_PEDIT->PEDIT_INTERPRETER($sock, $data);
								break;
						endswitch;
						if($this->SOCKET->GET_SOCKETINFO($sock, 'MODE') == 'PLAYING')
						{
							$this->COMMUNICATION->SEND_PROMPT($sock);
						}
						switch($this->SOCKET->GET_SOCKETINFO($sock, 'MODE')):
							case 'LOGOUT':
						          $this->SYSTEM->LOGOUT($sock, "User logged out.");
								break;
						endswitch;
					endif;
				endif;
			}
		endwhile;
		$this->SHUTDOWN('End of proccess');
	}
	function LOAD_MODULES()
	{
		$list = $this->MODULE_LIST;
		foreach($list as $module)
		{
			$filename = "modules/".str_replace("_", ".", strtolower($module)).".php";
			if(file_exists($filename)):
				@require_once($filename);
				if(!$this->{$module} = new $module($this->MESSAGE)):
					$this->MESSAGE->SYSMESSAGE($module, "SYSTEM", "FATAL ERROR");
					exit;
				endif;
			else:
				$this->MESSAGE->SYSMESSAGE("LOAD MODULE ".$module." FAILED", "SYSTEM", "FATAL ERROR");
				exit;
			endif;
		}
	}
	function LOAD_PLUGINS()
	{
		$file_arr = explode( "\n", shell_exec( "ls ./plugins/*.php" ));
		array_pop($file_arr); // last line is always blank
		foreach($file_arr as $file)
		{
			$plugin = strtoupper(str_replace(".","_",str_replace(".php", "",str_replace("./plugins/","",$file))));
			$this->PLUGIN_LIST[] = $PLUGIN;
			$filename = "plugins/".str_replace("_", ".", strtolower($plugin)).".php";
			if(file_exists($filename)):
				@require_once($filename);
				if(!$this->{$plugin} = new $plugin($this->MESSAGE)):
					$this->MESSAGE->SYSMESSAGE($plugin, "SYSTEM", "FATAL ERROR");
					exit;
				endif;
			else:
				$this->MESSAGE->SYSMESSAGE("LOAD PLUGIN ".$plugin." FAILED", "SYSTEM", "FATAL ERROR");
				exit;
			endif;
		}
	}
	function LOAD_COMMANDS()
	{
		$file_arr = explode( "\n", shell_exec( "ls ./commands/*.php" ));
		array_pop($file_arr); // last line is always blank
		foreach($file_arr as $file)
		{
			$command = strtoupper(str_replace(".","_",str_replace(".php", "",str_replace("./commands/","",$file))));
			$this->COMMAND_LIST[] = $command;
			$filename = "commands/".str_replace("_", ".", strtolower($command)).".php";
			if(file_exists($filename)):
				@require_once($filename);
				if(!$this->{$command} = new $command($this->MESSAGE)):
					$this->MESSAGE->SYSMESSAGE($command, "SYSTEM", "FATAL ERROR");
					exit;
				endif;
			else:
				$this->MESSAGE->SYSMESSAGE("LOAD COMMAND ".$command." FAILED", "SYSTEM", "FATAL ERROR");
				exit;
			endif;
		}
	}
     function INITIALIZE_MODULES_PLUGINS_AND_COMMANDS()
     {
          $list = array_merge($this->MODULE_LIST,$this->PLUGIN_LIST,$this->COMMAND_LIST);
          foreach($list as $module)
          {
               $OBJECT_ARRAY[$module]= &$this->{$module}; 
          }
          foreach($list as $module)
          {
               $OBJECT_ARRAY['NAME'] = $module;
               if(!$this->{$module}->INITIALIZE($OBJECT_ARRAY)):
                    $this->MESSAGE->SYSMESSAGE("INIT MODULE ".$module." FAILED", "SYSTEM", "FATAL ERROR");
                    exit;
               endif;
          }          
     }
     function INIT_COMMANDS()
     {
		$list = $this->COMMAND_LIST;
          foreach($list as $command)
          {
               if(!$this->{$command}->LOAD_COMMANDS()):
                    $this->MESSAGE->SYSMESSAGE("INIT COMMAND ".$plugin." FAILED", "SYSTEM", "FATAL ERROR");
                    exit;
               endif;
          }          
     }
     function SHUTDOWN($message)
     {
          $this->SYSTEM->SHUTDOWN($message);
     }
	function ALRM_TRIGGER()
	{
          foreach($this->SOCKET->clients as $client)
          {
               if(isset($this->DATABASE->DB[$client]))
               {
               	$ping = $this->DATABASE->DB[$client]->GET_VAR("select now() from dual;");
               }
          }
	}
}
?>
