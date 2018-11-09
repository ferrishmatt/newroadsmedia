<?php

namespace NewRoadsMedia\FrontendBundle\Command;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use NewRoadsMedia\FrontendBundle\Entity\Payment;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class DatabaseJobToPaymentCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('jj:db:job_to_payment')
        ;
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        $jobManager = $this->getContainer()->get('journalismjobs.manager.job');
        $paymentManager = $this->getContainer()->get('journalismjobs.manager.payment');
        $paymentQuery = $paymentManager->getRepository()->createQueryBuilder('p')->select('p.id', 'p.jobList')->where('p.jobList IS NOT NULL')->getQuery();

        $index = 0;
        $batchSize = 100;
        $rows = array();
        $sqlTemplate = 'INSERT IGNORE INTO JobToPayment (JobID, PaymentID) VALUES' . ' %s';

        foreach ($paymentQuery->getResult() as $row) {
            $id = $row['id'];
            $jobList = $row['jobList'];
            if ($jobList) {
                $jobIds = explode(',', $jobList);
                foreach ($jobIds as $jobId) {
                    $job = $jobManager->getRepository()->createQueryBuilder('j')->select('j.id')->where('j.id = :id')->setParameter('id', $jobId)->getQuery()->getOneOrNullResult();
                    if ($job) {
                        $rows[] = '(' . $jobId . ', ' . $id . ')';
                        ++$index;
                    }
                }
                if ($index >= $batchSize) {
                    $this->executeSql(sprintf($sqlTemplate, implode(',', $rows)));
                    $rows = array();
                    $index = 0;
                }
            }
        }
        if ($rows) {
            $this->executeSql(sprintf($sqlTemplate, implode(',', $rows)));
        }
	}

    protected function executeSql($sql, $parameters = array())
    {
        $statement = $this->getContainer()->get('doctrine.orm.default_entity_manager')->getConnection()->prepare($sql);
        foreach ($parameters as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $statement->execute();
    }
}