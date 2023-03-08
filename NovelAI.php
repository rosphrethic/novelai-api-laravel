<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NovelAI {

	public string $baseURL;
	public string $accessKey;
	public string $accessToken;
	public array $headers;

	public function __construct()
	{
		$this->baseURL = env('NOVELAI_BASE_URL');
		$this->accessKey = env('NOVELAI_ACCESS_KEY');
		$this->accessToken = session("novelai_access_token") ?? '';
	}

	public function call($method, $path, $body = null, $urlParameters = [])
	{
		$URL = $this->baseURL . $path;

		try {
			if ($method == "GET") $http = Http::withToken($this->accessToken)->withUrlParameters($urlParameters)->get($URL, $body);
			if ($method == "POST") $http = Http::withToken($this->accessToken)->withUrlParameters($urlParameters)->post($URL, $body);
			if ($method == "PUT") $http = Http::withToken($this->accessToken)->withUrlParameters($urlParameters)->put($URL, $body);
			if ($method == "PATCH") $http = Http::withToken($this->accessToken)->withUrlParameters($urlParameters)->patch($URL, $body);
			if ($method == "DELETE") $http = Http::withToken($this->accessToken)->withUrlParameters($urlParameters)->delete($URL, $body);

			return json_decode($http->getBody());

		} catch (\Exception $exception) {
			Log::error($exception);
			abort(500);
		}
	}

	public function instance()
	{
		return new NovelAI();
	}

	public function getTestConnection()
	{
		return $this->call("GET", "/", null, false);
	}

	public function postUserLogin()
	{
		$body = [
			"key" => $this->accessKey,
		];

		return $this->call("POST", "/user/login", $body);
	}

	public function postUserChangeAccessKey($currentAccessKey, $newAccessKey, $newEmail)
	{
		$body = [
			"currentAccessKey" => $currentAccessKey,
			"newAccessKey"     => $newAccessKey,
			"newEmail"         => $newEmail,
		];

		return $this->call("POST", "/user/change-access-key", $body);
	}

	public function postUserResendEmailVerification($email)
	{
		$body = [
			"email" => $email,
		];

		return $this->call("POST", "/user/resend-email-verification", $body);
	}

	public function postUserVerifyEmail($verificationToken)
	{
		$body = [
			"verificationToken" => $verificationToken,
		];

		return $this->call("POST", "/user/verify-email", $body);
	}

	public function getUserInformation()
	{
		return $this->call("GET", "/user/information");
	}

	public function postUserDeletionRequest($email)
	{
		$body = [
			"email" => $email,
		];

		return $this->call("POST", "/user/deletion/request", $body);
	}

	public function postUserDeletionDelete($deletionToken)
	{
		$body = [
			"deletionToken" => $deletionToken,
		];

		return $this->call("POST", "/user/deletion/delete", $body);
	}

	public function postUserRecoveryRequest($email)
	{
		$body = [
			"email" => $email,
		];

		return $this->call("POST", "/user/recovery/request", $body);
	}

	public function postUserRecoveryRecover($recoveryToken, $newAccessKey, $deleteContent)
	{
		$body = [
			"recoveryToken" => $recoveryToken,
			"newAccessKey"  => $newAccessKey,
			"deleteContent" => $deleteContent,
		];

		return $this->call("POST", "/user/recovery/recover", $body);
	}

	public function postUserDelete()
	{
		return $this->call("POST", "/user/delete");
	}

	public function getUserData()
	{
		return $this->call("GET", "/user/data");
	}

	public function getUserGiftKeys()
	{
		return $this->call("GET", "/user/giftkeys");
	}

	public function getUserSubscription()
	{
		return $this->call("GET", "/user/subscription");
	}

	public function getUserKeystore()
	{
		return $this->call("GET", "/user/keystore");
	}

	public function putUserKeystore($keystore, $changeIndex)
	{
		$body = [
			"keystore"    => $keystore,
			"changeIndex" => $changeIndex,
		];

		return $this->call("PUT	", "/user/keystore", $body);
	}

	public function getUserObjectsType($type)
	{
		return $this->call("GET", "/user/objects/{$type}");
	}

	public function putUserObjectsType($type, $meta, $data, $changeIndex)
	{
		$body = [
			"meta"        => $meta,
			"data"        => $data,
			"changeIndex" => $changeIndex,
		];

		return $this->call("PUT", "/user/objects/{$type}", $body);
	}

	public function getUserObjectsTypeId($type, $id)
	{
		return $this->call("GET", "/user/objects/{$type}/{$id}");
	}

	public function putUserObjectsTypeId($type, $id)
	{
		return $this->call("PUT", "/user/objects/{$type}/{$id}");
	}

	public function patchUserObjectsTypeId($type, $id, $meta, $data, $changeIndex)
	{
		$body = [
			"meta"        => $meta,
			"data"        => $data,
			"changeIndex" => $changeIndex,
		];

		return $this->call("PATCH", "/user/objects/{$type}/{$id}", $body);
	}

	public function deleteUserObjectsTypeId($type, $id)
	{
		return $this->call("DELETE", "/user/objects/{$type}/{$id}");
	}

	public function getUserClientSettings()
	{
		return $this->call("GET", "/user/clientsettings");
	}

	public function putUserClientSettings($string)
	{
		$body = $string;

		return $this->call("PUT", "/user/clientsettings", $body);
	}

	public function postUserSubmission($data, $dataName, $authorName, $authorEmail, $socials, $mediums, $event)
	{
		$body = [
			"data"        => $data,
			"dataName"    => $dataName,
			"authorName"  => $authorName,
			"authorEmail" => $authorEmail,
			"socials"     => $socials,
			"mediums"     => $mediums,
			"event"       => $event,
		];

		return $this->call("POST", "/user/submission", $body);
	}

	public function getUserSubmissionEvent($event)
	{
		return $this->call("GET", "/user/submission/{$event}");
	}

	public function getUserVoteSubmissionEvent($event)
	{
		return $this->call("GET", "/user/submission/{$event}");
	}

	public function postUserVoteSubmissionEvent($event, $id)
	{
		$body = [
			"id" => $id,
		];

		return $this->call("POST", "/user/submission/{$event}", $body);
	}

	public function deleteUserVoteSubmissionEvent($event, $id)
	{
		$body = [
			"id" => $id,
		];

		return $this->call("DELETE", "/user/submission/{$event}", $body);
	}

	public function postUserSubscriptionBind($paymentProcessor, $subscriptionId, $confirmedReplace, $confirmedIgnore)
	{
		$body = [
			"paymentProcessor" => $paymentProcessor,
			"subscriptionId"   => $subscriptionId,
			"confirmedReplace" => $confirmedReplace,
			"confirmedIgnore"  => $confirmedIgnore,
		];

		return $this->call("POST", "/user/subscription/bind", $body);
	}

	public function postUserSubscriptionChange($newSubscriptionPlan)
	{
		$body = [
			"newSubscriptionPlan" => $newSubscriptionPlan,
		];

		return $this->call("POST", "/user/subscription/change", $body);
	}

	public function postAIGenerate($input, $model, $parameters)
	{
		$body = [
			"input"      => $input,
			"model"      => $model,
			"parameters" => $parameters,
		];

		return $this->call("POST", "/ai/generate", $body);
	}

	public function postAIGenerateStream($input, $model, $parameters)
	{
		$body = [
			"input"      => $input,
			"model"      => $model,
			"parameters" => $parameters,
		];

		return $this->call("POST", "/ai/generate-stream", $body);
	}

	public function postAIGenerateImage($input, $model, $parameters, $url)
	{
		$body = [
			"input"      => $input,
			"model"      => $model,
			"parameters" => $parameters,
			"url"        => $url,
		];

		return $this->call("POST", "/ai/generate-image", $body);
	}

	public function postAIClassify()
	{
		return $this->call("POST", "/ai/classify");
	}

	public function getAIGenerateImageSuggestTags($model, $prompt)
	{
		$urlParameters = [
			"model"  => $model,
			"prompt" => $prompt,
		];

		return $this->call("GET", "/ai/generate-image/suggest-tags", null, $urlParameters);
	}

	public function getAIGenerateVoice($text, $seed, $voice, $opus, $version)
	{
		$urlParameters = [
			"text"    => $text,
			"seed"    => $seed,
			"voice"   => $voice,
			"opus"    => $opus,
			"version" => $version,
		];

		return $this->call("GET", "/ai/generate-voice", null, $urlParameters);
	}

	public function postAIModuleTrain($data, $lr, $steps, $model, $name, $description)
	{
		$body = [
			"data"        => $data,
			"lr"          => $lr,
			"steps"       => $steps,
			"model"       => $model,
			"name"        => $name,
			"description" => $description,
		];

		return $this->call("GET", "/ai/module/train", $body);
	}

	public function getAIModuleAll()
	{
		return $this->call("GET", "/ai/module/all");
	}

	public function getAIModuleId($id)
	{
		return $this->call("GET", "/ai/module/all/{$id}");
	}

	public function deleteAIModuleId($id)
	{
		return $this->call("DELETE", "/ai/module/{$id}");
	}

	public function postAIModuleBuyTrainingSteps($amount)
	{
		$body = [
			"amount" => $amount,
		];

		return $this->call("POST", "/ai/module/buy-training-steps", $body);
	}

}
