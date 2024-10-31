<?php

namespace QuizAd\Controller;

use QuizAd\Model\View\ViewModel;

/**
 * Base controller class.
 */
abstract class AbstractViewController
{
    protected static $scriptsCounter = 1;
    protected        $version;

    /**
     * That method should be used by each controller.
     *
     * @param string $version - set script version tag, to allow/avoid cache
     */
    public function setVersion($version = '')
    {
        $this->version = $version;
    }

    /**
     * Prepare data to render - template (php file) and data for that template.
     *
     * @return ViewModel
     */
    abstract protected function render();

    public function renderTemplate()
    {
        $this->addCommonScripts();
        $this->addScripts();

        $viewModel = $this->render();

        $data = $viewModel->getDataModel();
        // pass dataset - remove IDE inspections
        /** @noinspection PhpIncludeInspection */
        require $viewModel->getView();
    }

    /**
     * Add plugin script from inside of this plugin directory using Wordpress API.
     *
     * @param string $pathToScript - path relative to plugin (quizAd) directory, like: '/assets/js/request.js'.
     */
    protected function addPluginScript($pathToScript)
    {
        $scriptId = self::$scriptsCounter++;
        wp_enqueue_script(
            'quizAd_script_' . $scriptId,
            plugins_url($pathToScript, dirname(__DIR__) . '/../../'),
            [],
            $this->version,
            true
        );
    }

    /**
     * Add external script - attach it to this page.
     *
     * @param string  $url - url address of external script to attach to this page
     * @param boolean $addInFooter
     */
    protected function addPluginAssetScript($url, $addInFooter = false)
    {
        $scriptId = self::$scriptsCounter++;
        wp_enqueue_script(
            'quizAd_asset_script_' . $scriptId,
            $url,
            [],
            $this->version,
            $addInFooter
        );
    }

    /**
     * Add plugin script content.
     *
     * @param string $scriptContent - content like `console.log(2); var a = 2 + 4;`.
     */
    protected function addPluginInlineScript($scriptContent)
    {
        $scriptId = self::$scriptsCounter++;
        \wp_add_inline_script('quizAd_script_' . $scriptId, $scriptContent, 'after');
    }

    /**
     * To be implemented by each controller. Should use this class'es methods to add scripts.
     *
     * @return mixed
     */
    protected abstract function addScripts();

    /**
     * Shorthand for enqueue script.
     */
    private function addCommonScripts()
    {
        $this->addPluginScript('/assets/js/request.js');
    }

    protected function getRequestField($request, $field, $default = null)
    {
        return isset($request[$field]) ? $request[$field] : $default;
    }
}