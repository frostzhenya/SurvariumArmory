<?php
/**
 * @package Survarium Armory
 * @version Release 2.0
 * @revision 75
 * @copyright (c) 2014-2015 lovepsone
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
	if ($fraction == 0 && $typeItem == 0)
	{
		$STH = $DBH->prepare("SELECT * FROM armory_items");
		$STH->execute();
	}
	else if ($fraction != 0 && $typeItem != 0)
	{
		$data = array();
		$data['ti'] = $typeItem;
		$data['f'] = $fraction;
		$STH = $DBH->prepare("SELECT * FROM armory_items WHERE typeItem=:ti and fraction=:f");
		$STH->execute($data);
	}
	else if ($fraction == 0 && $typeItem != 0)
	{
		$data = array();
		$data['ti'] = $typeItem;
		$STH = $DBH->prepare("SELECT * FROM armory_items WHERE typeItem=:ti");
		$STH->execute($data);
	}
	else if ($fraction != 0 && $typeItem == 0)
	{
		$data = array();
		$data['f'] = $fraction;
		$STH = $DBH->prepare("SELECT * FROM armory_items WHERE typeItem=:f");
		$STH->execute($data);
	}

	$position = 1;
	while($res = $STH->fetch(PDO::FETCH_OBJ))
	{
		if ($position == 1)
		{
			echo '<tr><td valign="top">';
			echo '<div id="'.$res->selector.'"><img src="images/icon/'.$res->images.'.png" item="'.$res->id.'" class="icon" />';
			echo '</div></td>';
		}
		else if ($position == 2)
		{
			echo '<td valign="top">';
			echo '<div id="'.$res->selector.'"><img src="images/icon/'.$res->images.'.png" item="'.$res->id.'" class="icon" />';
			echo '</div></td>';
		}
		else if ($position == 3)
		{
			$position = 0;
			echo '<td valign="top">';
			echo '<div id="'.$res->selector.'"><img src="images/icon/'.$res->images.'.png" item="'.$res->id.'" class="icon" />';
			echo '</div></td></tr>';
		}
		$position++;
	}
	if ($position == 2 || $position == 3)
		echo '</tr>';
?>