<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Parameter Translator Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface TranslatorParameterInterface
{
    /**
     * Text setter.
     *
     * @param mixed $value The parameter text.
     *
     * @return $this.
     */
    public function setText($text);

    /**
     * Text getter.
     *
     * @return string The parameter text.
     */
    public function getText();

    /**
     * Translatable state setter.
     *
     * @param bool The parameter is made translatable if true, non-translatable if false.
     *
     * @return $this.
     */
    public function setTranslatable($state);

    /**
     * Tells if the parameter is translatable.
     *
     * @return bool True if translatable, false otherwise.
     */
    public function isTranslatable();

    /**
     * Label getter.
     *
     * A label uniquely identifies a parameter.
     *
     * @return string The parameter label.
     */
    public function getLabel();

    /**
     * Translator setter.
     *
     * @param KTranslator $translator The parameter translator.
     *
     * @return $this.
     */
    public function setTranslator(KTranslator $translator);

    /**
     * Translator getter.
     *
     * @return KTranslator The parameter translator.
     */
    public function getTranslator();

    /**
     * Renderer setter.
     *
     * @param TranslatorParameterRendererInterface $renderer
     *
     * @return $this.
     */
    public function setRenderer(TranslatorParameterRendererInterface $renderer);

    /**
     * Renderer getter.
     *
     * @return TranslatorParameterRendererInterface The parameter renderer.
     */
    public function getRenderer();

    /**
     * Renders the parameter object.
     *
     * @return string The rendered parameter.
     */
    public function render();

    /**
     * URL setter.
     *
     * @param string $url The parameter URL.
     *
     * @return $this.
     */
    public function setUrl($url);

    /**
     * URL getter.
     *
     * @return string The parameter url.
     */
    public function getUrl();

    /**
     * Tells if the parameter is linkable or not.
     *
     * @return mixed
     */
    public function isLinkable();

    /**
     * Link attributes setter.
     *
     * @param array $attributes The parameter link attributes.
     *
     * @return $this.
     */
    public function setLinkAttributes($attributes);

    /**
     * Link attributes getter.
     *
     * @return array The parameter attributes.
     */
    public function getLinkAttributes();

    /**
     * Attributes setter.
     *
     * @param array $attributes The parameter attributes.
     *
     * @return $this.
     */
    public function setAttributes($attributes);

    /**
     * Attributes getter.
     *
     * @return array The parameter attributes.
     */
    public function getAttributes();
}