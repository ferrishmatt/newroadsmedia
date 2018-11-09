<?php
// sudo php -f /var/www/vhosts/newroadsmedia/apps/journalismjobs.com/console jj:emails:notifications --env=prod --test
namespace NewRoadsMedia\FrontendBundle\Command;

use Doctrine\ORM\Query;
use NewRoadsMedia\FrontendBundle\Entity\Job;
use NewRoadsMedia\FrontendBundle\Entity\Notification;
use NewRoadsMedia\FrontendBundle\Manager\JobManager;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\HttpFoundation\Request;

class EmailsNotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('jj:emails:notifications')
            ->addOption('test', null, InputOption::VALUE_NONE, 'Run a test without sending the actual emails.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $notificationManager = $this->getContainer()->get('journalismjobs.manager.notification');
        /** @var JobManager $jobManager */
        $jobManager = $this->getContainer()->get('journalismjobs.manager.job');
        $jobManager->getManager()->getConnection()->getConfiguration()->setSQLLogger(null);
        $jjMailer = $this->getContainer()->get('journalismjobs.frontend.mailer');
        $jjMailer->setLogoPath('/bundles/newroadsmediajournalismjobs/images/jjobs.png');

        $this->getContainer()->enterScope('request');
        $this->getContainer()->set('request', Request::create($this->getContainer()->getParameter('base_url')), 'request');
        $siteTitle = $this->getContainer()->getParameter('site_title');
        $isTest = $input->getOption('test');

        if (!$isTest) {
            $jjMailer->sendTextEmail('dan@journalismjobs.com', 'Job Notifications Email', sprintf('The job notifications email command is running for %s.', $siteTitle));
        }

        /** @var Query $query */
        $query = $notificationManager->getActiveNotificationsQuery();
        foreach ($query->iterate() as $row) {
            /** @var Notification $notification */
            $notification = $row[0];

            /** @var Job[] $jobs */
            $jobs = $jobManager->getJobsByNotification($notification);
            if ($jobs) {
                if ($isTest) {

                    $notification->setEmail('erlanbekenov@gmail.com');
                    $jobs[] = $jobs[0];
                    $jjMailer->sendJobNotification($notification, $jobs);
                    $this->flush();
                    die('tut');


                    $output->writeln(sprintf('Notification %d to %s', $notification->getId(), $notification->getEmail()));
                    foreach ($jobs as $job) {
                        $output->writeln(sprintf('Job %d: Industries: %s; Focus %s'
                            , $job->getId()
                            , implode(', ', $job->getIndustries()->toArray())
                            , implode(', ', $job->getFocus()->toArray())
                        ));
                    }
                } else {
                    try {
                        $jjMailer->sendJobNotification($notification, $jobs);
                        $this->flush();
                    } catch (\Swift_RfcComplianceException $e) {
                    }
                }
            }

            $jobManager->getManager()->detach($notification);
            foreach ($jobs as $job) {
                $jobManager->getManager()->detach($job);
            }
            unset($notification, $jobs);
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