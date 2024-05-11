<?php

namespace Tsl\Training\Command;

use PrestaShopBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ShopStatusCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('training:shops-status')
            ->setDescription('Displays shop status')
            ->setHelp('This command helps you display status of a shop')
            ->addArgument(
                'shopName',
                InputArgument::OPTIONAL,
                'The shop name, the default one will be used.'
            )
        ;
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $io = new SymfonyStyle($input, $output);
        $io->title('Training status shop command by Tsl');

        $shops = $this->getContainer()->get('prestashop.core.admin.shop.repository')->findAll();
        $io->table(
            ['ID', 'name', 'theme', 'Activated?', 'Deleted?'],
            $this->formatShopInformation($shops)
        );
    }

    private function formatShopInformation(array $shops)
    {
        $shopsInformation = [];

        foreach($shops as $shop) {
            $shopsInformation[] = [
                $shop->getId(),
                $shop->getName(),
                $shop->getThemeName(),
                $shop->getActive() ? 'Eka' : 'Tsia',
                $shop->getDeleted() ? 'Eny' : 'Tsia',
            ];
        }

        return $shopsInformation;
    }
} 

