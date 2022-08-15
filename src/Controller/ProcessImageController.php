<?php

declare(strict_types=1);

namespace App\Controller;

use App\Process\Image\Application\Command\UploadImage\UploadImageCommand;
use App\Shared\Infrastructure\Ports\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;

final class ProcessImageController extends ApiController
{
    public function __invoke(Request $request): Response
    {
        $image = $request->files->get('image');

        if (empty($image)) {
            throw new RuntimeException('The file is empty');
        }

        $filename = $image->getClientOriginalName();
        $content = file_get_contents($image->getPathname());

        $this->dispatch(new UploadImageCommand($filename, $content));

        return new Response('', Response::HTTP_ACCEPTED);
    }
}