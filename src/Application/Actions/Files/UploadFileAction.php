<?php
declare(strict_types=1);

namespace App\Application\Actions\Files;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

class UploadFileAction extends Action
{

	protected function action(): Response
	{
		$directory = '/var/www';
		$uploadedFiles = $this->request->getUploadedFiles();
		$response = $this->response;

		// handle single input with single file upload
		$uploadedFile = $uploadedFiles['example1'];
		if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
			$filename = $this->moveUploadedFile($directory, $uploadedFile);
			$response->getBody()->write('Uploaded: ' . $filename . '<br/>');
		}

		return $response;
	}

	function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile): string
	{
		$extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);

		// see http://php.net/manual/en/function.random-bytes.php
		$basename = bin2hex(random_bytes(8));
		$filename = sprintf('%s.%0.8s', $basename, $extension);

		$uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

		return $filename;
	}

}
