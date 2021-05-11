<?php

namespace Memo\ImageUsageBundle\ContaoManager;


use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Memo\MailJetBundle\Controller\BackendController;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Memo\ModEventRegistrationBundle\ModEventRegistrationBundle;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Contao\ManagerPlugin\Config\ContainerBuilder as PluginContainerBuilder;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Memo\ImageUsageBundle\ImageUsageBundle;

class Plugin implements BundlePluginInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function getBundles(ParserInterface $parser)
	{
		return [
			BundleConfig::create(ImageUsageBundle::class)->setLoadAfter([ContaoCoreBundle::class])
		];
	}
}
