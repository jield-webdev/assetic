<?php

/*
 * This file is part of the Assetic package, an OpenSky project.
 *
 * (c) 2010-2014 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Assetic\Filter;

use Assetic\Contracts\Asset\AssetInterface;

/**
 * CleanCss filter.
 *
 * @link https://github.com/jakubpawlowicz/clean-css
 * @author Jakub Pawlowicz <http://JakubPawlowicz.com>
 */
class CleanCssFilter extends BaseNodeFilter
{
    /**
     * @var string Path to the binary for this process based filter
     */
    protected $binaryPath = '/usr/bin/cleancss';
    protected $debug;
    private $keepLineBreaks;
    private $compatibility;
    private $rootPath;
    private $skipImport = true;
    private $timeout;
    private $semanticMerging;
    private $roundingPrecision;
    private $removeSpecialComments;
    private $onlyKeepFirstSpecialComment;
    private $skipAdvanced;
    private $skipAggresiveMerging;
    private $skipImportFrom;
    private $mediaMerging;
    private $skipRebase;
    private $skipRestructuring;
    private $skipShorthandCompacting;
    private $sourceMap;
    private $sourceMapInlineSources;

    /**
     * Keep line breaks
     * @param bool $keepLineBreaks True to enable
     */
    public function setKeepLineBreaks($keepLineBreaks)
    {
        $this->keepLineBreaks = $keepLineBreaks;
    }

    /**
     * Remove all special comments
     * @param bool $removeSpecialComments True to enable
     */ // i.e.  /*! comment */
    public function setRemoveSpecialComments($removeSpecialComments)
    {
        $this->removeSpecialComments = $removeSpecialComments;
    }

    /**
     * Remove all special comments except the first one
     * @param bool $onlyKeepFirstSpecialComment True to enable
     */
    public function setOnlyKeepFirstSpecialComment($onlyKeepFirstSpecialComment)
    {
        $this->onlyKeepFirstSpecialComment = $onlyKeepFirstSpecialComment;
    }

    /**
     * Enables unsafe mode by assuming BEM-like semantic stylesheets (warning, this may break your styling!)
     * @param bool $semanticMerging True to enable
     */
    public function setSemanticMerging($semanticMerging)
    {
        $this->semanticMerging = $semanticMerging;
    }

    /**
     * A root path to which resolve absolute @import rules
     * @param string $rootPath
     */
    public function setRootPath($rootPath)
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Disable @import processing
     * @param bool $skipImport True to enable
     */
    public function setSkipImport($skipImport)
    {
        $this->skipImport = $skipImport;
    }

    /**
     * Per connection timeout when fetching remote @imports; defaults to 5 seconds
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Disable URLs rebasing
     * @param bool $skipRebase True to enable
     */
    public function setSkipRebase($skipRebase)
    {
        $this->skipRebase = $skipRebase;
    }

    /**
     * Disable restructuring optimizations
     * @param bool $skipRestructuring True to enable
     */
    public function setSkipRestructuring($skipRestructuring)
    {
        $this->skipRestructuring = $skipRestructuring;
    }

    /**
     * Disable shorthand compacting
     * @param bool $skipShorthandCompacting True to enable
     */
    public function setSkipShorthandCompacting($skipShorthandCompacting)
    {
        $this->skipShorthandCompacting = $skipShorthandCompacting;
    }

    /**
     * Enables building input's source map
     * @param bool $sourceMap True to enable
     */
    public function setSourceMap($sourceMap)
    {
        $this->sourceMap = $sourceMap;
    }

    /**
     * Enables inlining sources inside source maps
     * @param bool $sourceMapInlineSources True to enable
     */
    public function setSourceMapInlineSources($sourceMapInlineSources)
    {
        $this->sourceMapInlineSources = $sourceMapInlineSources;
    }

    /**
     * Disable advanced optimizations - selector & property merging, reduction, etc.
     * @param bool $skipAdvanced True to enable
     */
    public function setSkipAdvanced($skipAdvanced)
    {
        $this->skipAdvanced = $skipAdvanced;
    }

    /**
     * Disable properties merging based on their order
     * @param bool $skipAggresiveMerging True to enable
     */
    public function setSkipAggresiveMerging($skipAggresiveMerging)
    {
        $this->skipAggresiveMerging = $skipAggresiveMerging;
    }

    /**
     * Disable @import processing for specified rules
     * @param string $skipImportFrom
     */
    public function setSkipImportFrom($skipImportFrom)
    {
        $this->skipImportFrom = $skipImportFrom;
    }

    /**
     * Disable @media merging
     * @param bool $mediaMerging True to enable
     */
    public function setMediaMerging($mediaMerging)
    {
        $this->mediaMerging = $mediaMerging;
    }

    /**
     * Rounds to `N` decimal places. Defaults to 2. -1 disables rounding.
     * @param int $roundingPrecision
     */
    public function setRoundingPrecision($roundingPrecision)
    {
        $this->roundingPrecision = $roundingPrecision;
    }

    /**
     * Force compatibility mode (see https://github.com/jakubpawlowicz/clean-css/blob/master/README.md#how-to-set-compatibility-mode for advanced examples)
     * @param string $compatibility
     */
    public function setCompatibility($compatibility)
    {
        $this->compatibility = $compatibility;
    }

    /**
     * Shows debug information (minification time & compression efficiency)
     * @param bool $debug True to enable
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
    }

    /**
     * Run the asset through CleanCss
     *
     * @see \Assetic\Contracts\Filter\FilterInterface::filterDump()
     */
    public function filterDump(AssetInterface $asset)
    {
        $args = [];

        if ($this->keepLineBreaks) {
            $args[] = '--keep-line-breaks';
        }

        if ($this->compatibility) {
            $args[] = '--compatibility ' . $this->compatibility;
        }

        if ($this->debug) {
            $args[] = '--debug';
        }

        if ($this->rootPath) {
            $args[] = '--root ' . $this->rootPath;
        }

        if ($this->skipImport) {
            $args[] = '--skip-import';
        }

        if ($this->timeout) {
            $args[] = '--timeout ' . $this->timeout;
        }

        if ($this->roundingPrecision) {
            $args[] = '--rounding-precision ' . $this->roundingPrecision;
        }

        if ($this->removeSpecialComments) {
            $args[] = '--s0';
        }

        if ($this->onlyKeepFirstSpecialComment) {
            $args[] = '--s1';
        }

        if ($this->semanticMerging) {
            $args[] = '--semantic-merging';
        }

        if ($this->skipAdvanced) {
            $args[] = '--skip-advanced';
        }

        if ($this->skipAggresiveMerging) {
            $args[] = '--skip-aggressive-merging';
        }

        if ($this->skipImportFrom) {
            $args[] = '--skip-import-from ' . $this->skipImportFrom;
        }

        if ($this->mediaMerging) {
            $args[] = '--skip-media-merging';
        }

        if ($this->skipRebase) {
            $args[] = '--skip-rebase';
        }

        if ($this->skipRestructuring) {
            $args[] = '--skip-restructuring';
        }

        if ($this->skipShorthandCompacting) {
            $args[] = '--skip-shorthand-compacting';
        }

        if ($this->sourceMap) {
            $args[] = '--source-map';
        }

        if ($this->sourceMapInlineSources) {
            $args[] = '--source-map-inline-sources';
        }

        $args[] = '{INPUT}';

        $result = $this->runProcess($asset->getContent(), $args);
        $asset->setContent($result);
    }
}
