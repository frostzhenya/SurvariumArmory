<?php
/**
 * @package Survarium Armory
 * @version Release 2.0
 * @revision 83
 * @copyright (c) 2014 - 2015 lovepsone
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 **/
	@include_once('../maincore.php');
	/*
	* $fraction 0 - ���/1 - �����������/2 - �������/3 - ������ �����/4 - ����� ������������/5 - ��������� ����/0 - ���
	* $typeItem 0 - ���/1 - �����/2 - ������
	* $typeSort 0 - ���������� �� ������/1 - ���������� �� ���������
	*/
	// ��������� ���������� ������
	list($fraction, $typeItem, $typeSort) = explode(":", $_POST['data']);

	$strSort = "";
	switch ($typeSort)
	{
	  case 1:
	    $strSort = " ORDER BY level, typeItem";
	    break;
	  case 2:
	     $strSort = " ORDER BY cost, typeItem";
	    break;
	  default:
	    $strSort = " ORDER BY level, typeItem";
	    break;
	}

	if ($fraction == 0 && $typeItem == 0)
	{
		$STH = $DBH->prepare("SELECT * FROM armory_items".$strSort);
		$STH->execute();
	}
	else if ($fraction != 0 && $typeItem != 0)
	{
		$data = array();
		$data['ti'] = $typeItem;
		$data['f'] = $fraction;
		$STH = $DBH->prepare("SELECT * FROM armory_items WHERE typeItem=:ti and fraction=:f".$strSort);
		$STH->execute($data);
	}
	else if ($fraction == 0 && $typeItem != 0)
	{
		$data = array();
		$data['ti'] = $typeItem;
		$STH = $DBH->prepare("SELECT * FROM armory_items WHERE typeItem=:ti".$strSort);
		$STH->execute($data);
	}
	else if ($fraction != 0 && $typeItem == 0)
	{
		$data = array();
		$data['f'] = $fraction;
		$STH = $DBH->prepare("SELECT * FROM armory_items WHERE fraction=:f".$strSort);
		$STH->execute($data);
	}
	function ToolTipIcon($data)
	{
		global $DBH, $modloc, $itemloc, $locale;

		$sm = ""; $d = array();
		$d['item'] = $data->id;
		$STH = $DBH->prepare("SELECT * FROM armory_items_mods LEFT JOIN armory_mods ON armory_mods.`id` = armory_items_mods.`idMod` WHERE armory_items_mods.`idItem`=:item");
		$STH->execute($d);
		while($res = $STH->fetch(PDO::FETCH_OBJ))
		{
			$sm .= "<tr><td><img src='images/mod/".$res->imgMod."' class='iconMod'/>".$modloc[$res->localeMod]."</td><td>".$res->mathsign.$res->value.$res->txtsign."</td></tr>";
		}

		$s =  "<table class='tooltipBody'><tr><td width='160px'>".$itemloc[$data->locale]."</td><td>".$data->level."</td></tr>";
		if ($data->typeItem == 1)
		{
			$s .= "<tr><td>".$locale['defence']."</td><td>".$data->defence."</td></tr>";
			$s .= "<tr><td>".$locale['isolation']."</td><td>".$data->isolation."</td></tr>";
			$s .= "<tr><td>".$locale['weight']."</td><td>".$data->weight."</td></tr>";
		}
		else if ($data->typeItem == 2)
		{
			$s .= "<tr><td>".$locale['damage']."</td><td>".$data->damage."</td></tr>";
			$s .= "<tr><td>".$locale['piercing']."</td><td>".$data->piercing."</td></tr>";
			$s .= "<tr><td>".$locale['sighting']."</td><td>".$data->piercing."</td></tr>";
			$s .= "<tr><td>".$locale['stoppower']."</td><td>".$data->piercing."</td></tr>";
			$s .= "<tr><td>".$locale['dispersion']."</td><td>".$data->dispersion."</td></tr>";
			$s .= "<tr><td>".$locale['distance']."</td><td>".$data->distance."</td></tr>";
			$s .= "<tr><td>".$locale['rate']."</td><td>".$data->rate."</td></tr>";
			$s .= "<tr><td>".$locale['weight']."</td><td>".$data->weight."</td></tr>";
		}
		$s .= $sm;
		$s .= "</table>";
		return $s;
	}

	$position = 1;
	while($res = $STH->fetch(PDO::FETCH_OBJ))
	{
		if ($position == 1)
		{
			echo '<tr><td valign="top">';
			echo '<div id="'.$res->selector.'"><img src="images/icon/'.$res->images.'.png" item="'.$res->id.'" class="icon" title="'.ToolTipIcon($res).'"/>';
			echo '</div></td>';
		}
		else if ($position == 2)
		{
			echo '<td valign="top">';
			echo '<div id="'.$res->selector.'"><img src="images/icon/'.$res->images.'.png" item="'.$res->id.'" class="icon" title="'.ToolTipIcon($res).'"/>';
			echo '</div></td>';
		}
		else if ($position == 3)
		{
			$position = 0;
			echo '<td valign="top">';
			echo '<div id="'.$res->selector.'"><img src="images/icon/'.$res->images.'.png" item="'.$res->id.'" class="icon" title="'.ToolTipIcon($res).'"/>';
			echo '</div></td></tr>';
		}
		$position++;
	}
	if ($position == 2 || $position == 3)
		echo '</tr>';
?>