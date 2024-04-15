<?php

namespace App\Helpers;

class Helper
{
    public static function trackables(object $table): void
	{
		$table->uuid('created_by')->nullable();
		$table->foreign('created_by')->references('id')->on('users');

		$table->uuid('updated_by')->nullable();
		$table->foreign('updated_by')->references('id')->on('users');

		$table->uuid('deleted_by')->nullable();
		$table->foreign('deleted_by')->references('id')->on('users');
	}

    public static function validateCorporateRegistry(string $number): bool
    {
        $number = preg_replace( '/[^0-9]/is', '', $number );

        if (strlen($number) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $number)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $number[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($number[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}