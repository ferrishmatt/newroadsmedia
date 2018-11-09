<?php

namespace NewRoadsMedia\FrontendBundle\Command;

use NewRoadsMedia\FrontendBundle\Entity\Resume;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeleteOldAccountsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jj:resume:delete')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resumeManager = $this->getContainer()->get('journalismjobs.manager.resume');
        $date = new \DateTime('-6 months');
        $resumes = $resumeManager->getRepository()->createQueryBuilder('resume')
            ->select('partial resume.{id, resumeFile, profilePicture}')
            ->where('resume.dateEntered <= :date')
            ->setParameter('date', $date->format('Y-m-d'))
            ->getQuery()
            ->iterate()
        ;
        $i = 0;
        foreach ($resumes as $resume) {
            /** @var Resume $resume */
            $resume = $resume[0];
            $resumeManager->deleteResume($resume);
            if ((++$i % 20) == 0) {
                $resumeManager->flush();
                $resumeManager->clear();
            }
        }
        $resumeManager->flush();
        $resumeManager->clear();
    }
}