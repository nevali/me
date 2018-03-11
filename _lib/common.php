<?php

ini_set('display-errors', 'on');
error_reporting(E_ALL);
    
require_once(dirname(__FILE__) . '/../_config/config.php');
require_once(dirname(__FILE__) . '/../_config/defaults.php');

class Page
{
	public $title = 'Music • Broadcasting • Technology';
	public $canonical;
	public $internal = false;

	public $html;
}

$page = new Page();

function emit($name)
{
	global $page;

	require(TEMPLATES_DIR . $name . '.phtml');
}

function e($str)
{
    echo htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function _e($str)
{
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function staticres($name)
{
	e(STATIC_URI . '/' . ltrim($name, '/'));
}

function css($name)
{
	staticres($name);
}

function js($name)
{
	staticres($name);
}

function font($name)
{
	staticres($name);
}

function img($name)
{
	staticres($name);
}

function datapath($path)
{
	return DATA_DIR . '/' . ltrim($path, '/');
}

function opendata($path)
{
	return fopen(datapath($path), 'rb');
}

function opendatadir($path)
{
	return opendir(DATA_DIR . '/' . ltrim($path, '/'));
}

function ymdhms($datestr)
{
	if(strlen($datestr) == 4)
	{
		return $datestr . '-01-01 00:00:00';
	}
	if(strlen($datestr) == 7)
	{
		return $datestr . '-01 00:00:00';
	}
	if(strlen($datestr) == 10)
	{
		return $datestr . ' 00:00:00';
	}
	return $datestr;
}

function sortdate($info)
{
	$now = strftime('%Y-%m-%d %H:%M:%S');
	if(isset($info['sortdate']))
	{
		$start = ymdhms($info['sortdate']);
		$end = $start;
	}
	else if(isset($info['date']))
	{
		$start = ymdhms($info['date']);
		$end = $start;
	}
	else if(isset($info['end']))
	{
		$start = ymdhms($info['start']);
		$end = ymdhms($info['end']);
	}
	else if(isset($info['start']))
	{
		$start = ymdhms($info['start']);
		$end = $now;
	}
	else
	{
		$start = $end = $now;
	}
	return $end . '-' . $start;
}

function humandatestr($str)
{
	$dt = strtotime(ymdhms($str));
	if(strlen($str) <= 4)
	{
		return strftime('%Y', $dt);
	}
	return strftime('%B %Y', $dt);
}

function humandate($info)
{
	if(isset($info['date']))
	{
		return humandatestr($info['date']);
	}
	if(isset($info['start']) && isset($info['end']))
	{
		return humandatestr($info['start']) . '—' . humandatestr($info['end']);
	}
	if(isset($info['start']))
	{
		return humandatestr($info['start']) . '—present';
	}
}

function getorg($name)
{
	static $orgs = array();

	if(isset($orgs[$name]))
	{
		return $orgs[$name];
	}
	$base = datapath('orgs') . '/';
	$path = $base . $name . '/org.json';
	if(file_exists($path))
	{
		$data = json_decode(file_get_contents($path), true);
		$data['name'] = $name;
		if(file_exists($base . $name . '/' . $name . '.jpeg'))
		{
			$data['thumb'] = 'orgs/' . $name . '/' . $name . '.jpeg';
		}
		$orgs[$name] = $data;
		return $data;
	}
	return false;
}
