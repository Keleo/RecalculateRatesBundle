<?php

/*
 * This file is part of the Kimai RecalculateRatesBundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KimaiPlugin\RecalculateRatesBundle\Timesheet\Calculator;

use App\Entity\Timesheet;
use App\Timesheet\Calculator\RateCalculator;
use App\Timesheet\CalculatorInterface;

class RecalculateRateCalculator implements CalculatorInterface
{
    /**
     * @var RateCalculator
     */
    protected $calculator;

    public function __construct(RateCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @param Timesheet $record
     */
    public function calculate(Timesheet $record)
    {
        if (null !== $record->getHourlyRate()) {
            $record->setHourlyRate(null);
        }
        if (null !== $record->getFixedRate()) {
            $record->setFixedRate(null);
        }

        // we have to trigger it again, as there is no order defined for the calculator
        $this->calculator->calculate($record);
    }
}
