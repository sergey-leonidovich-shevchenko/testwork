<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\CurrencyRateService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:update-currency-rates',
    description: 'Updates currency rates from configured provider.'
)]
class UpdateCurrencyRatesCommand extends Command
{
    public function __construct(private readonly CurrencyRateService $currencyRateService)
    {
        parent::__construct();
    }

    final public function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: Move to Use Case
        $this->currencyRateService->updateRates();
        $output->writeln('Currency rates updated successfully.');

        return Command::SUCCESS;
    }
}
