<?php
namespace Commissions;

class Calculator
{
    /**
     * Calculates all charges of services in the Log and returns a result.
     *
     * @param $serviceLog
     * Array of service records in CSV format.
     * @return
     * Array of service charges in order that corresponds Service Log input.
     * Upon failure returns NULL.
     */
    public static function solve($serviceLog)
    {
        if (empty($serviceLog)) {
            throw new \Exception('No Service Log found');
        }
        $clients = [];
        $changes = [];
        foreach ($serviceLog as $row) {
            list($date, $id, $type, $service, $amount, $currency) = str_getcsv($row);

            if (!isset($clients[$id])) {
                $clients[$id] = new \Commissions\Client($type);
            } else {
                $clients[$id]->setType($type);
            }

            $charge = $clients[$id]->getPayment(
                [
                    'name' => $service,
                    'date' => $date,
                    'cash' => new \Commissions\Money($amount, $currency)
                ]
            );
            array_push($changes, $charge);
        }

        if (!empty($changes)) {
            return $changes;
        }
        return null;
    }
}
