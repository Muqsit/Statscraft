<?php

declare(strict_types=1);

namespace statscraft\thread;

use statscraft\utils\APIException;

class StatscraftConnector{

	public const URL = "https://api.statscraft.net/";

	/** @var string */
	private $secret;

	/** @var string */
	private $tempfile;

	/** @var string */
	private $public_secret;

	public function __construct(string $secret){
		$this->secret = $secret;
		$this->validate();

		$this->tempfile = tempnam(sys_get_temp_dir(), "statscraft");
	}

	public function close() : void{
		unlink($this->tempfile);
	}

	public function getSecret() : string{
		return $this->secret;
	}

	public function validate() : void{
		$result = $this->request("server/poll", json_encode([
			"privateKey" => $this->secret,
			"serverTime" => time()
        ]));

		if(isset($result["error"])){
			throw new APIException($result["message"]);
		}
	}

	public function verify() : string{
		$result = $this->request("server/verify", json_encode([
			"privateKey" => $this->secret,
			"serverTime" => time()
        ]));

		if(isset($result["error"])){
			throw new APIException($result["message"]);
		}

		return $result["message"];

	}

	public function setStatistics(Statistics $statistics) : array{
		return $this->request("server/zipuploader", json_encode($statistics), true);
	}

	public function request(string $query, string $data, bool $compress = false) : array{
		if($compress){
			$zip = new \ZipArchive();
			$zip->open($this->tempfile, \ZipArchive::CREATE);
			$zip->addFromString("data.json", $data);
			$zip->addFromString("privateKey.json", json_encode(["privateKey" => $this->secret]));
			$zip->close();

			$data = file_get_contents($this->tempfile);
		}

		$curl = curl_init(self::URL . $query);
		curl_setopt_array($curl, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/" . ($compress ? "zip" : "json"),
				"Content-Length: " . strlen($data)
			]
		]);

		$result = curl_exec($curl);
		curl_close($curl);

		return json_decode($result, true);
	}

	public function apiRequest(string $query) : array{
		$curl = curl_init(self::URL . $query);
		curl_setopt_array($curl, [
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => [],
			CURLOPT_HTTPHEADER => [
				"X-Private-Key: " . $this->secret
			]
		]);

		$result = curl_exec($curl);
		curl_close($curl);

		return json_decode($result, true);
	}
}