<?php
class style {
	public static function toMoney($number) {
		return number_format($number, 2, '.', ' ')." &euro;";
	}
}
