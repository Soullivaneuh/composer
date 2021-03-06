<?php

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Composer\Command;

use Composer\Util\ProcessExecutor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class OutdatedCommand extends ShowCommand
{
    protected function configure()
    {
        $this
            ->setName('outdated')
            ->setDescription('Shows a list of installed packages including their latest version.')
            ->setDefinition(array(
                new InputArgument('package', InputArgument::OPTIONAL, 'Package to inspect. Or a name including a wildcard (*) to filter lists of packages instead.'),
                new InputOption('outdated', 'o', InputOption::VALUE_NONE, 'Show only packages that are outdated'),
                new InputOption('direct', 'D', InputOption::VALUE_NONE, 'Shows only packages that are directly required by the root package'),
            ))
            ->setHelp(<<<EOT
The outdated command is just a proxy for `composer show -l`

The color coding for dependency versions is as such:

- <info>green</info>: Dependency is in the latest version and is up to date.
- <comment>yellow</comment>: Dependency has a new version available that includes backwards
  compatibility breaks according to semver, so upgrade when you can but it
  may involve work.
- <highlight>red</highlight>: Dependency has a new version that is semver-compatible and you should upgrade it.


EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = array($input->getArgument('package') ? ProcessExecutor::escape($input->getArgument('package')) : '');
        $args[] = $input->getOption('outdated') ? ProcessExecutor::escape('--outdated') : '';
        $args[] = $input->getOption('direct') ? ProcessExecutor::escape('--direct') : '';
        $input = new StringInput('show --latest '.implode(' ', $args));

        return $this->getApplication()->run($input, $output);
    }
}
