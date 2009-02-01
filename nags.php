#!/usr/bin/php5
<?
/*============================================================================*\
|	NAGS - an AMP (Apache, MySql and PHP) backed mud system
|	by Terry Valladon
|
|	if you like my work, respect me and dont remove my Notes :-)
|
|	If you find this somewhere and want more info check www.get-nags.info.
|
|	Copyright (C) 2007-2010 by Terry Valladon (get-nags@terryvalladon.com)
|
|	This program is free software
|	you can redistribute it and/or modify
|	it under the terms of the GNU General Public License as published by
|	the Free Software Foundation; either version 2, or (at your option)
|	any later version.
|
|	This program is distributed in the hope that it will be useful,
|	but WITHOUT ANY WARRANTY; without even the implied warranty of
|	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
|	GNU General Public License for more details.
|
|	You should have received a copy of the GNU General Public License
|	along with this program; if not, write to the Free Software
|	Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
|
\*============================================================================*/

	$QUIET = False;
	$DEAMON = False;
	//Read command line arguments into an array	
	$arg_array = ARGUMENTS($_SERVER['argv']);
	if($arg_array['quiet'] == True OR $arg_array['q'] == True)
	{
		$QUIET = True;
	}
	if($arg_array['deamon'] == True OR $arg_array['d'] == True)
	{
		$DEAMON = True;
	}

	require_once('modules/main.php');
	declare(ticks = 1);

	pcntl_signal(SIGTERM, 'signal_handler');
	pcntl_signal(SIGINT, 'signal_handler');
	pcntl_signal(SIGALRM, "signal_handler");
	
	if(!$MAIN = new MAIN())
	{
		systemlog('FULL', '\FRSYSTEM:\t(FATAL ERROR)\tMODULE LOAD FAIL ON\tSYS_MAIN\n');
		exit;
	}

	pcntl_alarm(60);
	$MAIN->MAIN_PROGRAM_LOOP();

	function signal_handler($signal) {
		global $MAIN;
		switch($signal)
		{
			case SIGTERM:
				$MAIN->SYSTEM->SHUTDOWN('(SIGTERM) Forced shutdown from server...\n');
				exit;
			case SIGKILL:
				$MAIN->SYSTEM->SHUTDOWN('(SIGKILL) Forced shutdown from server...\n');
				exit;
			case SIGINT:
				$MAIN->SYSTEM->SHUTDOWN('(SIGINT) Forced shutdown from server...\n');
				exit;
			case SIGALRM:
				$MAIN->ALRM_TRIGGER();
				pcntl_alarm(60);
				break;
		}
	}

	function parse($LEVEL, $MESSAGE)
	{
		if($LEVEL == 'FULL')
		{
			$ANSI_BG = array('BLACK'=>chr(27).'[40m','RED'=>chr(27).'[41m','GREEN'=>chr(27).'[42m','YELLOW'=>chr(27).'[43m','BLUE'=>chr(27).'[44m','MAGENTA'=>chr(27).'[45m','CYAN'=>chr(27).'[46m','WHITE'=>chr(27).'[47m');
			$ANSI_FG = array('BLACK'=>chr(27).'[1;30m','RED'=>chr(27).'[1;31m','GREEN'=>chr(27).'[1;32m','YELLOW'=>chr(27).'[1;33m','BLUE'=>chr(27).'[1;34m','MAGENTA'=>chr(27).'[1;35m','CYAN'=>chr(27).'[1;36m','WHITE'=>chr(27).'[1;37m');
			$ANSI_SPECIAL = array('BOLD'=>chr(27).'[1m','UNDERLINE'=>chr(27).'[4m','BLINK'=>chr(27).'[5m','HIDDEN'=>chr(27).'[8m','INVERSE'=>chr(27).'[7m','NORMAL'=>chr(27).'[0m', 'CLEAR'=>chr(27).'[2J'.chr(27).'[0;0H');
		}elseif($LEVEL == 'OFF'){
			$ANSI_BG = array('BLACK'=>'','RED'=>'','GREEN'=>'','YELLOW'=>'','BLUE'=>'','MAGENTA'=>'','CYAN'=>'','WHITE'=>'');
			$ANSI_FG = array('BLACK'=>'','RED'=>'','GREEN'=>'','YELLOW'=>'','BLUE'=>'','MAGENTA'=>'','CYAN'=>'','WHITE'=>'');
			$ANSI_SPECIAL = array('BOLD'=>'','UNDERLINE'=>'','BLINK'=>'','HIDDEN'=>'','INVERSE'=>'','NORMAL'=>'', 'CLEAR'=>'');
		}

		//Replace forground char codes
		$MESSAGE = str_replace('\FB',$ANSI_FG['BLACK'],$MESSAGE);
		$MESSAGE = str_replace('\FR',$ANSI_FG['RED'],$MESSAGE);
		$MESSAGE = str_replace('\FG',$ANSI_FG['GREEN'],$MESSAGE);
		$MESSAGE = str_replace('\FY',$ANSI_FG['YELLOW'],$MESSAGE);
		$MESSAGE = str_replace('\FL',$ANSI_FG['BLUE'],$MESSAGE);
		$MESSAGE = str_replace('\FM',$ANSI_FG['MAGENTA'],$MESSAGE);
		$MESSAGE = str_replace('\FC',$ANSI_FG['CYAN'],$MESSAGE);
		$MESSAGE = str_replace('\FW',$ANSI_FG['WHITE'],$MESSAGE);
		//Replace background char codes
		$MESSAGE = str_replace('\BB',$ANSI_BG['BLACK'],$MESSAGE);
		$MESSAGE = str_replace('\BR',$ANSI_BG['RED'],$MESSAGE);
		$MESSAGE = str_replace('\BG',$ANSI_BG['GREEN'],$MESSAGE);
		$MESSAGE = str_replace('\BY',$ANSI_BG['YELLOW'],$MESSAGE);
		$MESSAGE = str_replace('\BL',$ANSI_BG['BLUE'],$MESSAGE);
		$MESSAGE = str_replace('\BM',$ANSI_BG['MAGENTA'],$MESSAGE);
		$MESSAGE = str_replace('\BC',$ANSI_BG['CYAN'],$MESSAGE);
		$MESSAGE = str_replace('\BW',$ANSI_BG['WHITE'],$MESSAGE);
		//Replace special char codes
		$MESSAGE = str_replace('\SB',$ANSI_SPECIAL['BOLD'],$MESSAGE);
		$MESSAGE = str_replace('\SU',$ANSI_SPECIAL['UNDERLINE'],$MESSAGE);
		$MESSAGE = str_replace('\SL',$ANSI_SPECIAL['BLINK'],$MESSAGE);
		$MESSAGE = str_replace('\SH',$ANSI_SPECIAL['HIDDEN'],$MESSAGE);
		$MESSAGE = str_replace('\SI',$ANSI_SPECIAL['INVERSE'],$MESSAGE);
		$MESSAGE = str_replace('\SN',$ANSI_SPECIAL['NORMAL'],$MESSAGE);
		$MESSAGE = str_replace('\SC',$ANSI_SPECIAL['CLEAR'],$MESSAGE);
		$MESSAGE = str_replace('\t',"\t",$MESSAGE);
		$MESSAGE = str_replace('\n',"\n",$MESSAGE);

		return($MESSAGE);
	}
	function systemlog($message)
	{
		global $QUIET;
		global $DEAMON;
		if($QUIET != True)
		{
			if($DEAMON != True)
			{
				echo(parse('FULL', $message));
			}else{
				echo(parse('OFF', $message));
			}
		}
		$SYSLOG_FILE = 'syslog';
		$SYSLOG_HANDLE = fopen($SYSLOG_FILE, 'a') or die('can`t open syslog file');
		fwrite($SYSLOG_HANDLE, date('m/j/Y g:i a').' '.parse('OFF', $message));
		fclose($SYSLOG_HANDLE);
	}
	function ARGUMENTS($argv)
	{
		//ARGUMENTS Function taken from earomero _{at}_ gmail.com from the php site:
		//	http://us3.php.net/features.commandline
		$_ARG = array();
		foreach ($argv as $arg)
		{
			if (preg_match('#^-{1,2}([a-zA-Z0-9]*)=?(.*)$#', $arg, $matches))
			{
				$key = $matches[1];
				switch ($matches[2]) 
				{
					case '':
					case 'true':
						$arg = true;
						break;
					case 'false':
						$arg = false;
						break;
					default:
						$arg = $matches[2];
				}
				/* make unix like -afd == -a -f -d */           
				if(preg_match('/^-([a-zA-Z0-9]+)/', $matches[0], $match))
				{
					$string = $match[1];
					for($i=0; strlen($string) > $i; $i++) 
					{
						$_ARG[$string[$i]] = true;
					}
				} else {
					$_ARG[$key] = $arg;   
				}           
			} else {
				$_ARG['input'][] = $arg;
			}       
		}
		return $_ARG;   
	}
?>