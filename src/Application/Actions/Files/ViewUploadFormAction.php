<?php
declare(strict_types=1);

namespace App\Application\Actions\Files;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;

class ViewUploadFormAction extends Action
{
	protected function action(): Response
	{
		$html = <<<HTML
<!-- make sure the attribute enctype is set to multipart/form-data -->
<form method="post" enctype="multipart/form-data">
    <!-- upload of a single file -->
    <p>
        <label>Add file (single): </label><br/>
        <input type="file" name="example1"/>
    </p>
    <p>
        <input type="submit"/>
    </p>
</form>
HTML;
		$response = $this->response;
		$response->getBody()->write($html);
		return $response;
	}
}
