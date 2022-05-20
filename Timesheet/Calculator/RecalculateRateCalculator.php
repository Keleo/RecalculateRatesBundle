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
use App\Constants;
use App\Entity\Timesheet;
use App\Timesheet\Calculator\RateCalculator;
use App\Timesheet\CalculatorInterface;
use Doctrine\ORM\PersistentCollection;

final class RecalculateRateCalculator implements CalculatorInterface
{
    private $calculator;
    private $systemConfiguration;

    public function __construct(RateCalculator $calculator, SystemConfiguration $systemConfiguration)
    {
        $this->calculator = $calculator;
        $this->systemConfiguration = $systemConfiguration;
    }

    /**
     * @param Timesheet $record
     * @return void
     */
    public function calculate(Timesheet $record)
    {
        $mode = $this->systemConfiguration->find('timesheet.recalculate.mode');
        if ($mode === null) {
            return;
        }

        // this means we have Kimai > 1.20
        // we can use the improved calculation method
        if ($mode === 'default' && \func_num_args() === 2) {
            /** @var array<string, array{mixed, mixed}|PersistentCollection> $changes */
            $changes = func_get_arg(1);

            // check if the rate was changed manually
            $changedRate = false;
            foreach (['hourlyRate', 'fixedRate', 'internalRate', 'rate'] as $field) {
                if (\array_key_exists($field, $changes)) {
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
                    if (\array_key_exists($field, $changes)) {
                        // this has room for minor improvements: entries with a manual rate might be changed
                        $this->resetRates($record);
                        break;
                    }
                }
            }

            return;
        }

        if ($mode === 'always') {
            $this->resetRates($record);

            // we have to trigger it again, as there was no order defined for the calculator in earlier versions
            /* @phpstan-ignore-next-line */
            if (\defined('App\Constants::VERSION_ID') && Constants::VERSION_ID < 12001) {
                $this->calculator->calculate($record);
            }
        }
    }

    private function resetRates(Timesheet $record): void
    {
        if (method_exists($record, 'resetRates')) {
            $record->resetRates();

            return;
        }

        $record->setRate(0.00);
        $record->setInternalRate(null);
        $record->setHourlyRate(null);
        $record->setFixedRate(null);
    }

    public function getPriority(): int
    {
        return 250;
    }
}
