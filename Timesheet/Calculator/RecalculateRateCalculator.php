<?php

/*
 * This file is part of the RecalculateRatesBundle for Kimai.
 * All rights reserved by Kevin Papst (www.keleo.de).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\RecalculateRatesBundle\Timesheet\Calculator;

use App\Configuration\SystemConfiguration;
use App\Entity\Timesheet;
use App\Timesheet\CalculatorInterface;

final class RecalculateRateCalculator implements CalculatorInterface
{
    public function __construct(private SystemConfiguration $systemConfiguration)
    {
    }

    public function calculate(Timesheet $record, array $changeset): void
    {
        $mode = $this->systemConfiguration->find('timesheet.recalculate.mode');
        if ($mode === null) {
            return;
        }

        // we can use the improved calculation method
        if ($mode === 'default') {
            // check if the rate was changed manually
            $changedRate = false;
            foreach (['hourlyRate', 'fixedRate', 'internalRate', 'rate'] as $field) {
                if (\array_key_exists($field, $changeset)) {
                    $changedRate = true;
                    break;
                }
            }

            // if no manual rate changed was applied:
            // check if a field changed, that is relevant for the rate calculation: if one was changed =>
            // reset all rates, because most users do not even see their rates and would not be able
            // to fix or empty the rate, even if they knew that the changed project has another base rate
            if (!$changedRate) {
                foreach (['project', 'activity', 'user'] as $field) {
                    if (\array_key_exists($field, $changeset)) {
                        // this has room for minor improvements: entries with a manual rate might be changed
                        $record->resetRates();
                        break;
                    }
                }
            }

            return;
        }

        if ($mode === 'always') {
            $record->resetRates();
        }
    }

    public function getPriority(): int
    {
        return 250;
    }
}
