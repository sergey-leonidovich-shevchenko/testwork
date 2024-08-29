<?php

declare(strict_types=1);

namespace App\Command;

use App\UseCase\Command\CurrencyRateUpdateUseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'currency-rates:update',
    description: 'Updates currency rates from configured provider.'
)]
class CurrencyRatesUpdateCommand extends Command
{
    public function __construct(
        private readonly CurrencyRateUpdateUseCase $currencyRateUpdateCommandUseCase,
    ) {
        parent::__construct();
    }

    final public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currencyRateUpdateCommandUseCase->execute();
        $output->writeln('Currency rates updated successfully.');

        return Command::SUCCESS;
    }
}
