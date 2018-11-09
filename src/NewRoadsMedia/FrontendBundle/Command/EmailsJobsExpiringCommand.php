<?php

namespace NewRoadsMedia\FrontendBundle\Command;

use Doctrine\ORM\Query;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Manager\JobManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class EmailsJobsExpiringCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('jj:emails:jobs_expiring')
			->setHelp('Checks if products are up for renewal. Sends email notifications as necessary.')
        ;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        /** @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('journalismjobs.manager.job');
        $jobManager->getManager()->getConnection()->getConfiguration()->setSQLLogger(null);
        $jjMailer = $this->getContainer()->get('journalismjobs.frontend.mailer');
        $siteTitle = $this->getContainer()->getParameter('site_title');

        $jjMailer->sendTextEmail('dan@journalismjobs.com', 'Jobs Expiring Email', sprintf('The jobs expiring email command is running for %s.', $siteTitle));

        /** @var Query $query */
        $query = $jobManager->getExpiringJobsQuery();
        foreach ($query->iterate() as $row) {
            /** @var Job $job */
            $job = $row[0];
            try {
                $jjMailer->sendJobExpiringEmail($job);
                $this->flush();
            } catch (\Swift_RfcComplianceException $e) {
            }
        }
        $this->flush();
	}

    protected function flush()
    {
        $mailer = $this->getContainer()->get('mailer');
        $spool = $mailer->getTransport()->getSpool();
        $transport = $this->getContainer()->get('swiftmailer.transport.real');
        $spool->flushQueue($transport);
    }
}