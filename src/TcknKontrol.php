<?php

namespace Netliva\TcknBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TcknKontrol
{


	private function strtoupper_tr ($text)
	{
		$search   = ['ç', 'ğ', 'ı', 'ö', 'ş', 'ü', 'i'];
		$replace  = ['Ç', 'Ğ', 'I', 'Ö', 'Ş', 'Ü', 'İ'];

		return mb_strtoupper(str_replace($search, $replace, trim($text)));
	}

	public function vknTcknDogrula($vkn_tckn)
	{
		if (preg_match("/^[1-9]{1}[0-9]{10}$/", $vkn_tckn)) return $this->tcknDogrula($vkn_tckn);

		if (preg_match("/^[0-9]{10}$/", $vkn_tckn)) return $this->vknDogrula($vkn_tckn);

		return false;
	}


	public function tcknDogrula ($tckn)
	{
		$ignored = array('11111111110','22222222220','33333333330','44444444440','55555555550','66666666660','7777777770','88888888880','99999999990');

		if (in_array($tckn, $ignored)) return false;

		if($tckn[0]==0 or !ctype_digit($tckn) or strlen($tckn)!=11)
			return false;

		$tek_hane_toplam = 0;
		$cift_hane_toplam = 0;
		$tum_basamak_toplam = 0;
		for($a=0;$a<9;$a=$a+2) { $tek_hane_toplam = $tek_hane_toplam + $tckn[$a]; }
		for($a=1;$a<9;$a=$a+2) { $cift_hane_toplam = $cift_hane_toplam + $tckn[$a]; }
		for($a=0;$a<10;$a++) { $tum_basamak_toplam = $tum_basamak_toplam + $tckn[$a]; }

		if(($tek_hane_toplam * 7 - $cift_hane_toplam) % 10 != $tckn[9] or $tum_basamak_toplam % 10 != $tckn[10])
			return false;

		return true;
	}

	public function vknDogrula($vkn)
	{
		if (!preg_match("/^[0-9]{10}$/", $vkn)) return false;

		$total = 0;
		$checkNum = null;
		for ($i = 0; $i < 9; $i++) {
			$tmp1 = ($vkn[$i] + (9 - $i)) % 10;
			$tmp2 = ($tmp1 * (2 ** (9 - $i))) % 9;
			if ($tmp1 !== 0 && $tmp2 === 0) {
				$tmp2 = 9;
			}
			$total += $tmp2;
		}
		if ($total % 10 === 0) {
			$checkNum = 0;
		} else {
			$checkNum = 10 - ($total % 10);
		}
		if ((int)$vkn[9] !== $checkNum) {
			return false;
		}
		return true;

	}

}
