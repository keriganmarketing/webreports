<?php

namespace App;

use App\Company;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Helpers extends Model
{
    public static function getCompanies()
    {
        return Company::all()->sortBy('name');
    }

    public static function buildDates()
    {
        $dates = [];
       
        for($i = 1; $i < 26; $i++){
            $dates[] = [
                'year' => Carbon::now()->firstOfMonth()->subMonths($i)->year,
                'month' => Carbon::now()->firstOfMonth()->subMonths($i)->month,
                'name' => Carbon::now()->firstOfMonth()->subMonths($i)->format('F, Y')
            ];
        }

        return json_encode($dates);
    }

    public static function percentChange($newNumber, $oldNumber)
    {
        if (! is_numeric($newNumber) || ! is_numeric($oldNumber)) {
            return 'No Data';
        }
        if ($oldNumber > 0) {
            return ((($newNumber - $oldNumber) / $oldNumber) * 100);
        }

        return 100;
    }

    /**
     * @param $part
     * @param $whole
     * @return float
     */
    public static function calculatePercentage($part, $whole)
    {
        if (! is_numeric($part) || ! is_numeric($whole)) {
            return 0;
        }
        return round(($part / $whole) * 100, 2);
    }
}
