<?php


namespace QuizAd\Service\OAuth2;


use QuizAd\Database\CredentialsRepository;
use QuizAd\Model\Credentials\Credentials;
use QuizAd\Model\Registration\API\OAuth2\OAuth2ApiRequest;
use QuizAd\Model\Registration\API\OAuth2\OAuth2FailureResponse;
use QuizAd\Model\Registration\OAuth2Request;

class OAuth2Service
{
    /** @var CredentialsRepository */
	protected $credentialsRepository;
	/** @var OAuth2ApiClient */
	protected $OAuth2ApiClient;

    /**
     * RegistrationService constructor.
     *
     * @param CredentialsRepository $credentialsRepository
     * @param OAuth2ApiClient       $OAuth2ApiClient
     */
	public function __construct(
		CredentialsRepository $credentialsRepository,
		OAuth2ApiClient $OAuth2ApiClient
	) {
		$this->credentialsRepository = $credentialsRepository;
		$this->OAuth2ApiClient       = $OAuth2ApiClient;
	}

	/**
	 * Get credentials.
	 * If possible get token as well.
	 * @return Credentials
	 */
	public function getCredentials()
	{
		$clientCredentials = $this->credentialsRepository->getClientCredentials();
		if ( ! $clientCredentials->hasCredentials() ) {
			return $clientCredentials;
		}
		if ( $clientCredentials->hasValidToken() ) {
			return $clientCredentials;
		}
		$apiModel = new OAuth2ApiRequest( new OAuth2Request( 'client_credentials', 'quiz_plugins_api' ) );

		$response = $this->OAuth2ApiClient->getToken( $apiModel, $clientCredentials->getClientId(), $clientCredentials->getClientSecret() );

		if ( $response instanceof OAuth2FailureResponse ) {
			return $clientCredentials;
		}

		$dbResult = $this->credentialsRepository->addToken( $response, $clientCredentials->getClientId() );
		if ( $dbResult === false ) {
			return $clientCredentials;
		}

		return $this->credentialsRepository->getClientCredentials();
	}
}
