<?php

/**
 * lsbBB - LandSandBoat extension for phpBB
 * @author Ganiman <ganiman@ganiman.com>
 * @copyright (c) 2025, Made to Raid, https://madetoraid.com
 * @license GNU GPL-3.0
 */

namespace madetoraid\lsbbb\includes;

class functions_xi
{
	function xi_ucwords($xi_string)
	{
		$xi_string = ucwords(str_replace('_', ' ', $xi_string), "\t\r\n\f\v- ");
		$xi_string = str_replace(' Of ', ' of ', $xi_string);
		$xi_string = str_replace(' A ', ' a ', $xi_string);
		$xi_string = str_replace(' The ', ' the ', $xi_string);
		$xi_string = str_replace(' Vi', ' VI', $xi_string);
		$xi_string = str_replace(' Iv', ' IV', $xi_string);
		$xi_string = str_replace(' Iii', ' III', $xi_string);
		$xi_string = str_replace(' Ii', ' II', $xi_string);
		$xi_string = str_replace('-Int', '-INT', $xi_string);
		$xi_string = str_replace('-Dex', '-DEX', $xi_string);
		$xi_string = str_replace('-Str', '-STR', $xi_string);
		$xi_string = str_replace('-Acc', '-ACC', $xi_string);
		$xi_string = str_replace('-Agi', '-AGI', $xi_string);
		$xi_string = str_replace('-Chr', '-CHR', $xi_string);
		$xi_string = str_replace('-Mnd', '-MND', $xi_string);
		$xi_string = str_replace('-Tp', '-TP', $xi_string);
		$xi_string = str_replace('-Vit', '-VIT', $xi_string);
		return $xi_string;
	}
}
