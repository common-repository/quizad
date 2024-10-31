<?php

namespace QuizAd\Assets\Loaders;

use QuizAd\Assets\AssetLoaderInterface;

class AdminAssetsLoader implements AssetLoaderInterface
{
	public function loader()
	{
		wp_enqueue_style('style-name',
			plugins_url('/assets/css/admin.css',realpath(dirname(__DIR__) . '/..')));

		add_action('wp_ajax_form', 'function');

		wp_localize_script('report-a-bug', 'settings', array(
			'ajaxurl'    => admin_url('admin-ajax.php'),
			'send_label' => __('Send report', 'example@example.pl')
		));
		if (isset($_SERVER['HTTPS']))
		{
			$protocol = 'https://';
		}
		else
		{
			$protocol = 'http://';
		}
	}

	public function init()
	{
		add_action('admin_enqueue_scripts', array($this, 'loader'));
	}

}