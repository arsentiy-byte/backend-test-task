<?php declare(strict_types=1);

namespace App\CustomExceptionListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

#[AsEventListener(event: ExceptionEvent::class)]
class ExceptionListener
{
    /**
     * @param ExceptionResponseBuilder $exceptionResponseBuilder
     */
    public function __construct(
        private readonly ExceptionResponseBuilder $exceptionResponseBuilder
    ) {
    }

    /**
     * @param ExceptionEvent $event
     * @return void
     */
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $response = $this->exceptionResponseBuilder->getExceptionResponse($exception);

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
