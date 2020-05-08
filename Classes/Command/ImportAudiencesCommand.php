<?php

namespace Keizer\KoningMailchimpSignup\Command;

use Keizer\KoningMailchimpSignup\Domain\Model\Audience;
use Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository;
use Keizer\KoningMailchimpSignup\Exception\ExtensionException;
use Keizer\KoningMailchimpSignup\Service\MailChimpService;
use Keizer\KoningMailchimpSignup\Utility\ConfigurationUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;

class ImportAudiencesCommand extends Command
{

    /**
     * Configure the command by defining the name, options and arguments
     */
    protected function configure(): void
    {
        $this->setDescription('Import audiences from MailChimp.');
    }

    /**
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        try {
            ConfigurationUtility::validate();
        } catch (ExtensionException $e) {
            $io->writeln($e->getMessage());

            return $e->getCode();
        }

        $processed = 0;
        foreach ($this->getMailChimpService()->lists() as $list) {
            try {
                $this->getAudienceRepository()->createOrUpdate(new Audience([
                    'identifier' => $list['id'],
                    'web_identifier' => $list['web_id'],
                    'name' => $list['name'],
                ]));
                $processed++;
            } catch (ExtensionException $e) {
                $io->writeln($e->getMessage());
            }
        }
        $io->writeln($processed . ' audiences imported.');

        return 0;
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Service\MailChimpService
     */
    protected function getMailChimpService(): MailChimpService
    {
        return GeneralUtility::makeInstance(ObjectManager::class)
            ->get(MailChimpService::class);
    }

    /**
     * @return \Keizer\KoningMailchimpSignup\Domain\Repository\AudienceRepository
     */
    protected function getAudienceRepository(): AudienceRepository
    {
        return GeneralUtility::makeInstance(ObjectManager::class)
            ->get(AudienceRepository::class);
    }
}
