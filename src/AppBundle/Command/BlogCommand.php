<?php
namespace AppBundle\Command;

use AppBundle\Entity\Post;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Bundle\FrameworkBundle\Console;
use Symfony\Bundle\FrameworkBundle\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Doctrine\ORM\EntityManagerInterface;

class BlogCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this->setName('app:list-post')
            ->setDescription('List all post from the blog.')
            ->setHelp('this command will allow you to list all the posts from the blog')
        ->addOption('max', null, InputOption::VALUE_OPTIONAL, 'Limits the number of posts listed', 10);

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $maxResults = $input->getOption('max');
        $post = $this->getContainer()->get('doctrine')->getManager()->getRepository(Post::class)->findBy([], ['id' => 'DESC'],$maxResults);
//
//        $output->$this->render(['posts' => $posts]);

       // Doctrine query returns an array of objects and we need an array of plain arrays
        $postsAsPlainArrays = array_map(function (Post $post) {
            return [$post->getId(), $post->getTitle(), $post->getContent()];
        }, $post);

        // In your console commands you should always use the regular output type,
        // which outputs contents directly in the console window. However, this
        // particular command uses the BufferedOutput type instead.
        // The reason is that the table displaying the list of users can be sent
        // via email if the '--send-to' option is provided. Instead of complicating
        // things, the BufferedOutput allows to get the command output and store
        // it in a variable before displaying it.
        $bufferedOutput = new BufferedOutput();

        $table = new Table($bufferedOutput);
        $table
            ->setHeaders(['ID', 'Title', 'Content'])
            ->setRows($postsAsPlainArrays)
        ;
        $table->render();

        // instead of displaying the table of users, store it in a variable
        $tableContents = $bufferedOutput->fetch();

        $output->writeln($tableContents);
    }



}