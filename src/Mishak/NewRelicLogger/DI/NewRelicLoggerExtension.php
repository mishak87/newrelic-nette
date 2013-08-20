<?php

namespace Mishak\NewRelicLogger\DI;

use Kdyby;
use Nette;


class NewRelicLoggerExtension extends Nette\Config\CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$config = $this->getConfig(array(
			'enabled' => !$builder->expand('%debugMode%')
		));

		Nette\Utils\Validators::assertField($config, 'enabled');

		if ($builder->expand($config['enabled'])) {
			$builder->addDefinition($this->prefix('listener'))
			->setClass('Mishak\NewRelicLogger\NewRelicProfilingListener')
			->addTag(Kdyby\Events\DI\EventsExtension::SUBSCRIBER_TAG);
		}
	}


	public static function register(Nette\Config\Configurator $configurator)
	{
		$configurator->onCompile[] = function ($config, Nette\DI\Compiler $compiler) {
			$compiler->addExtension('newRelic', new NewRelicLoggerExtension);
		};
	}

}