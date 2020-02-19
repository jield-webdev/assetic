<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2014 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Extension\Twig;

use Assetic\Factory\AssetFactory;
use Assetic\ValueSupplierInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AsseticExtension extends AbstractExtension implements GlobalsInterface
{
    protected $factory;
    protected $functions;
    protected $valueSupplier;

    public function __construct(AssetFactory $factory, $functions = [], ValueSupplierInterface $valueSupplier = null)
    {
        $this->factory       = $factory;
        $this->functions     = [];
        $this->valueSupplier = $valueSupplier;

        foreach ($functions as $function => $options) {
            if (is_integer($function) && is_string($options)) {
                $this->functions[$options] = ['filter' => $options];
            } else {
                $this->functions[$function] = $options + ['filter' => $function];
            }
        }
    }

    public function getTokenParsers()
    {
        return [
            new AsseticTokenParser($this->factory, 'javascripts', 'js/*.js'),
            new AsseticTokenParser($this->factory, 'stylesheets', 'css/*.css'),
            new AsseticTokenParser($this->factory, 'image', 'images/*', true),
        ];
    }

    public function getFunctions()
    {
        $functions = [];
        foreach ($this->functions as $function => $filter) {
            $functions[] = new AsseticFilterFunction($function);
        }

        return $functions;
    }

    public function getGlobals(): array
    {
        return [
            'assetic' => [
                'debug' => $this->factory->isDebug(),
                'vars'  => null !== $this->valueSupplier ? new ValueContainer($this->valueSupplier) : [],
            ],
        ];
    }

    public function getFilterInvoker($function)
    {
        return new AsseticFilterInvoker($this->factory, $this->functions[$function]);
    }

    public function getName()
    {
        return 'assetic';
    }
}
