<?php


namespace QuizAd\Service\Placements;


use QuizAd\Database\CredentialsRepository;
use QuizAd\Database\WebsiteRepository;
use QuizAd\Model\Placements\Website;

class HeaderCodeApiService
{
	protected $credentialsRepository;
	protected $websiteRepository;

	/**
	 * HeaderCodeApiService constructor.
	 *
	 * @param CredentialsRepository $credentialsRepository
	 * @param WebsiteRepository $websiteRepository
	 */
	public function __construct(CredentialsRepository $credentialsRepository, WebsiteRepository $websiteRepository)
	{
		$this->websiteRepository = $websiteRepository;
		$this->credentialsRepository = $credentialsRepository;
	}

	/**
	 * @return Website
	 */
	public function getWebsiteWithHeaderCode()
	{
		$credentials = $this->credentialsRepository->getClientCredentials();
		$website = $this->websiteRepository->getWebsite($credentials);
        return $this->websiteRepository->getUsersWebsite($website);
	}

}