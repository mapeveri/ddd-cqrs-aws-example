<?php

declare(strict_types=1);

namespace App\Controller;

use App\Process\Image\Application\Query\PreviewImage\PreviewImageQuery;
use App\Process\Image\Infrastructure\Response\ImageResponse;
use App\Shared\Infrastructure\Ports\ApiController;
use Symfony\Component\HttpFoundation\Request;

final class PreviewImageController extends ApiController
{
    public function __invoke(Request $request, string $id, string $imageName): ImageResponse
    {
        $imagePreview = $this->handle(new PreviewImageQuery($id, $imageName));
        return new ImageResponse(
            $imagePreview->contentImage(),
            $imageName,
        );
    }
}