<?php declare(strict_types=1);

namespace App\CustomExceptionListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionResponseBuilder
{
    /**
     * @param ValidationFailedErrorsBuilder $validationFailedErrorsBuilder
     */
    public function __construct(
        private readonly ValidationFailedErrorsBuilder $validationFailedErrorsBuilder
    ) {
    }

    /**
     * @param \Throwable $exception
     * @return JsonResponse|null
     */
    public function getExceptionResponse(\Throwable $exception): ?JsonResponse
    {
        if ($exception instanceof UnprocessableEntityHttpException
            && $exception->getPrevious() instanceof ValidationFailedException) {
            return $this->getResponseForValidationFailedException($exception->getPrevious());
        }

        if ($exception instanceof ValidationFailedException) {
            return $this->getResponseForValidationFailedException($exception);
        }

        if ($exception instanceof HttpExceptionInterface) {
            return $this->getResponseForHttpException($exception);
        }

        return null;
    }

    /**
     * @param ValidationFailedException $exception
     * @return JsonResponse
     */
    private function getResponseForValidationFailedException(ValidationFailedException $exception): JsonResponse
    {
        $errors = $this->validationFailedErrorsBuilder->build($exception->getViolations());

        return new JsonResponse(['message' => 'Invalid payload', 'errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param HttpExceptionInterface $exception
     * @return JsonResponse
     */
    private function getResponseForHttpException(HttpExceptionInterface $exception): JsonResponse
    {
        return new JsonResponse(['message' => $exception->getMessage(), 'errors' => []], $exception->getStatusCode());
    }
}
