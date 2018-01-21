<?php declare(strict_types=1);

namespace Neoship;

class Neoship
{
	const API_URL_PRODUCTION = 'https://www.neoship.sk';
	const API_URL_DEVELOPMENT = 'http://test.neoship.sk';

	/* @var string */
	protected $clientId;

	/* @var string */
	protected $clientSecret;

	/* @var string */
	protected $redirectUrl;

	/* @var string */
	protected $apiUrl;

	/** @var object */
	protected $token;

	/* @var string */
	protected $tempDir;



	/**
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @throws \Neoship\NeoshipException
	 */
	public function __construct(string $clientId, string $clientSecret, string $redirectUrl, string $apiUrl = self::API_URL_PRODUCTION, string $tempDir = NULL)
	{
		$this->setClientId($clientId);
		$this->setClientSecret($clientSecret);
		$this->setRedirectUrl($redirectUrl);
		$this->setApiUrl($apiUrl);

		if ($tempDir === NULL) {
			$tempDir = sys_get_temp_dir();
		}
		$this->tempDir = rtrim($tempDir, '/');
	}



	/**
	 * @param string
	 * @return self
	 */
	protected function setClientId(string $clientId) : self
	{
		$this->clientId = $clientId;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getClientId() : string
	{
		return $this->clientId;
	}



	/**
	 * @param string
	 * @return self
	 */
	protected function setClientSecret(string $clientSecret) : self
	{
		$this->clientSecret = $clientSecret;
		return $this;
	}



	/**
	 * @return string
	 */
	public function getClientSecret() : string
	{
		return $this->clientSecret;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Neoship\NeoshipException
	 */
	protected function setRedirectUrl(string $url = NULL) : self
	{
		if ($url === NULL || filter_var($url, FILTER_VALIDATE_URL)) {
			$this->redirectUrl = $url;
		} else {
			throw new NeoshipException('Invalid redirect URL format');
		}

		return $this;
	}



	/**
	 * @return string
	 */
	public function getRedirectUrl() : string
	{
		return $this->redirectUrl;
	}



	/**
	 * @param string
	 * @return self
	 * @throws \Neoship\NeoshipException
	 */
	protected function setApiUrl(string $url) : self
	{
		if (filter_var($url, FILTER_VALIDATE_URL)) {
			$this->apiUrl = rtrim($url, '/');
		} else {
			throw new NeoshipException('Invalid API URL format');
		}

		return $this;
	}



	/**
	 * @return string
	 */
	public function getApiUrl() : string
	{
		return $this->apiUrl;
	}



	/**
	 * @return string
	 */
	public function getTempDir() : string
	{
		return $this->tempDir;
	}



	/**
	 * @param string
	 * @param string
	 * @param int
	 * @param string
	 * @param string
	 * @return self
	 * @throws \Neoship\NeoshipException
	 */
	public function setToken(string $accessToken, string $refreshToken, int $expiresIn, string $tokenType = NULL, string $scope = NULL) : self
	{
		$data = (object) [
			'access_token'    => $accessToken,
			'expires_in'      => $expiresIn,
			'token_type'      => $tokenType,
			'scope'           => $scope,
			'refresh_token'   => $refreshToken,
			'expiration_time' => new \DateTime('+' . $expiresIn . ' seconds'),
		];

		if (@file_put_contents($this->getTokenFile(), serialize($data)) === false) {
			throw new NeoshipException('Could not write to temp directory');
		}

		$this->token = (object) $data;

		return $this;
	}



	/**
	 * @return \stdClass
	 * @throws \Neoship\NeoshipException
	 */
	public function getToken() : \stdClass
	{
		if (!$this->isAuthorized()) {
			throw new NeoshipException('OAuth token missing or expired');
		}

		return $this->token;
	}



	/**
	 * @return string
	 */
	protected function getTokenFile() : string
	{
		return $this->tempDir . '/' . md5(implode('|', [$this->clientId, $this->clientSecret, $this->redirectUrl]));
	}



	/**
	 * @return string
	 */
	public function getAuthorizationUrl() : string
	{
		return $this->getApiUrl() . '/oauth/v2/auth?' . http_build_query([
			'client_id' => $this->getClientId(),
			'response_type' => 'code',
			'redirect_uri' => $this->getRedirectUrl(),
		]);
	}



	/**
	 * @return bool
	 */
	public function isAuthorized() : bool
	{
		if ($this->token === NULL && is_file($this->getTokenFile())) {
			$data = unserialize(file_get_contents($this->getTokenFile()));
			if ($data->expiration_time > new \DateTime('now')) {
				$this->token = $data;
				return true;
			}

		} else if ($this->token !== NULL && $this->token->expiration_time > new \DateTime('now')) {
			return true;
		}

		return false;
	}



	/**
	 * @return bool
	 * @throws \Neoship\NeoshipException
	 */
	public function requestAccessToken(string $code) : bool
	{
		$url = $this->getApiUrl() . '/oauth/v2/token?' . http_build_query([
			'client_id' => $this->getClientId(),
			'client_secret' => $this->getClientSecret(),
			'grant_type' => 'authorization_code',
			'code' => $code,
			'redirect_uri' => $this->getRedirectUrl(),
		]);

		try {
			$response = $this->sendHttpRequest($url);
		} catch (\Exception $e) {
			throw new NeoshipException('Could not access API', 0, $e);
		}

		if (!($data = @json_decode($response, true))) {
			throw new NeoshipException('Could not read API response');
		}

		if (isset($data['error'])) {
			throw new NeoshipException("Error: " . $data['error'] . (isset($data['error_description']) ? ' - ' . $data['error_description'] : ''));
		}

		foreach (['access_token','refresh_token','expires_in','token_type','scope'] as $key) {
			if (!array_key_exists($key, $data)) {
				throw new NeoshipException("JSON response is missing '" . $key . "' key");
			}
		}

		$this->setToken($data['access_token'], $data['refresh_token'], (int) $data['expires_in'], $data['token_type'], $data['scope']);

		return true;
	}



	/**
	 * @param string
	 * @param string
	 * @param array
	 * @return string
	 * @throws \Neoship\NeoshipException
	 * @throws \RuntimeException
	 */
	protected function sendHttpRequest(string $url, string $type = 'GET', array $data = []) : string
	{
		$types = ['GET', 'POST', 'PUT', 'DELETE'];
		if (!in_array($type, $types)) {
			throw new NeoshipException('Only the following requests type are allowed: ' . implode(', ', $types));
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		if (($response = curl_exec($ch)) === false) {
			throw new \RuntimeException('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
		}
		curl_close($ch);

		return $response;
	}



	/**
	 * @param string
	 * @param string
	 * @param array
	 * @param bool
	 * @return mixed
	 * @throws \Neoship\NeoshipException
	 */
	protected function sendApiRequest(string $name, string $type, array $data = [], bool $rawOutput = false)
	{
		$params = (array) $this->getToken();
		$params['expiration_time'] = $params['expiration_time']->format('U');

		if (in_array($type, ['GET', 'DELETE'])) {
			$params = array_merge($params, $data);
			$data = [];
		}

		$url = $this->getApiUrl() . '/api/rest/' . $name . '?' . http_build_query($params);

		switch ($type) {
			case 'GET':
			case 'POST':
			case 'PUT':
			case 'DELETE':
				$response = $this->sendHttpRequest($url, $type, $data);
				return $rawOutput ? $response : json_decode($response);

			default:
				throw new NeoshipException("Invalid request type '$type'");
		}
	}




	/**
	 * Returns all log entries for user
	 * @return \stdClass
	 */
	public function apiGetLog()
	{
		return $this->sendApiRequest('log', 'GET');
	}



	/**
	 * Returns count of log entries for user
	 * @return int
	 */
	public function apiGetLogCount()
	{
		return $this->sendApiRequest('log/count', 'GET');
	}



	/**
	 * Returns requested page of log entries (each page has 200 entries)
	 * @param int
	 * @return \stdClass
	 */
	public function apiGetLogPage(int $page)
	{
		return $this->sendApiRequest('log/page/' . $page, 'GET');
	}



	/**
	 * Returns current user
	 * @return \stdClass
	 */
	public function apiGetUser()
	{
		return $this->sendApiRequest('user', 'GET');
	}



	/**
	 * Returns list of all states
	 * @return \stdClass
	 */
	public function apiGetState()
	{
		return $this->sendApiRequest('state', 'GET');
	}



	/**
	 * Returns list of all currencies
	 * @return \stdClass
	 */
	public function apiGetCurrency()
	{
		return $this->sendApiRequest('currency', 'GET');
	}



	/**
	 * Returns list of statuses of package with given ID
	 * @param int package ID
	 * @return \stdClass
	 */
	public function apiGetStatus(int $id)
	{
		return $this->sendApiRequest('status', 'GET');
	}



	/**
	 * Returns package with given ID, returns all for current user if no ID is given.
	 * If $ref is set, return all packages with given reference numbers.
	 * @param int package ID
	 * @param array List of reference numbers to obtain
	 * @return \stdClass
	 */
	public function apiGetPackage(int $id, array $ref = [])
	{
		return $this->sendApiRequest('package/' . $id, 'GET', ['ref' => $ref]);
	}



	/**
	 * Returns count of packages for current user
	 * @return int
	 */
	public function apiGetPackageCount()
	{
		return $this->sendApiRequest('package/count', 'GET');
	}



	/**
	 * Returns requested page of packages (each page has 50 packages)
	 * @param  int page number
	 * @return \stdClass
	 */
	public function apiGetPackagePage(int $page)
	{
		return $this->sendApiRequest('package/page/' . $page, 'GET');
	}



	/**
	 * Calculates price of package
	 * @param array array of package info, refer to http://neoship.sk/help/api-volania#package for array content
	 * @return \stdClass
	 */
	public function apiPostPackagePrice(array $prices)
	{
		return $this->sendApiRequest('package/price', 'POST', $prices);
	}



	/**
	 * Creates new package
	 * @param \Neoship\Package
	 * @return \stdClass
	 */
	public function apiPostPackage(Package $package)
	{
		return $this->sendApiRequest('package', 'POST', $package->asArray());
	}



	/**
	 * Edits existing package
	 * @param int ID of package to edit
	 * @param array array of package info, refer to http://neoship.sk/help/api-volania#package for array content
	 * @return \stdClass
	 */
	public function apiPutPackage(int $id, array $package)
	{
		return $this->sendApiRequest('package/' . $id, 'POST', $package);
	}



	/**
	 * Deletes package
	 * @param string ID of package to delete
	 * @return \stdClass
	 */
	public function apiDeletePackage(int $id)
	{
		return $this->sendApiRequest('package/' . $id, 'DELETE');
	}



	/**
	 * Outputs sticker PDF to browser for download
	 * @param array variable numbers of packages
	 * @param int PDF template for sticker.
	 * @return string
	 */
	public function apiGetPackageSticker(array $ref, int $template = 0)
	{
		return $this->sendApiRequest('package/sticker', 'GET', ['ref' => $ref, 'template' => $template], true);
	}



	/**
	 * Outputs acceptance PDF to browser for download
	 * @param array variable numbers of packages
	 * @return string
	 */
	public function apiGetPackageAcceptance(array $ref)
	{
		return $this->sendApiRequest('package/acceptance', 'GET', ['ref' => $ref], true);
	}



	/**
	 * Returns packagemat with given ID, returns all for current user if no ID is given
	 * @param int packagemat ID
	 * @return \stdClass
	 */
	public function apiGetPackagemat(int $id)
	{
		return $this->sendApiRequest('packagemat/' . $id, 'GET');
	}



	/**
	 * Returns list of packagemat boxes
	 * @return \stdClass
	 */
	public function apiGetPackagematBoxes()
	{
		return $this->sendApiRequest('packagemat/boxes', 'GET');
	}
}
