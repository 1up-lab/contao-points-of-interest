<?php

declare(strict_types=1);

namespace Oneup\Contao\ContaoPointsOfInterestBundle\Provider;

use Contao\BackendUser;
use Contao\CoreBundle\Image\ImageSizes;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ImageSizeProvider
{
    /** @var ImageSizes */
    private $imageSizes;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    public function __construct(ImageSizes $imageSizes, TokenStorageInterface $tokenStorage)
    {
        $this->imageSizes = $imageSizes;
        $this->tokenStorage = $tokenStorage;
    }

    public function getImageSizes(): array
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof TokenInterface) {
            return $this->imageSizes->getAllOptions();
        }

        $user = $token->getUser();

        if (!$user instanceof BackendUser) {
            return $this->imageSizes->getAllOptions();
        }

        return $this->imageSizes->getOptionsForUser($user);
    }
}
