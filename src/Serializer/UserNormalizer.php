<?php
namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserNormalizer implements ContextAwareNormalizerInterface
{
    private ObjectNormalizer $normalizer;

    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        return $data instanceof User;
    }

    /**
     * @param User $object
     * @param string|null $format
     * @param array $context
     * @return array
     */
    public function normalize($object, string $format = null, array $context = [])
    {
        $data = [
            'id' => $object->getId(),
            'email' => $object->getEmail(),
            'token' => $object->getApiToken(),
        ];
        return $data;
    }
}
