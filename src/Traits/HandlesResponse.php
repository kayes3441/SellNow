<?php

namespace SellNow\Traits;

trait HandlesResponse
{
    /**
     * Redirect with success message
     */
    protected function redirectWithSuccess(string $url, string $message): void
    {
        $_SESSION['flash_success'] = $message;
        $this->redirect($url);
    }

    /**
     * Redirect with error message
     */
    protected function redirectWithError(string $url, string $message): void
    {
        $_SESSION['flash_error'] = $message;
        $this->redirect($url);
    }

    /**
     * Redirect with info message
     */
    protected function redirectWithInfo(string $url, string $message): void
    {
        $_SESSION['flash_info'] = $message;
        $this->redirect($url);
    }

    /**
     * Redirect with warning message
     */
    protected function redirectWithWarning(string $url, string $message): void
    {
        $_SESSION['flash_warning'] = $message;
        $this->redirect($url);
    }

    /**
     * Get flash messages and clear them
     */
    protected function getFlashMessages(): array
    {
        $messages = [
            'success' => $_SESSION['flash_success'] ?? null,
            'error' => $_SESSION['flash_error'] ?? null,
            'info' => $_SESSION['flash_info'] ?? null,
            'warning' => $_SESSION['flash_warning'] ?? null,
        ];

        // Clear flash messages after reading
        unset($_SESSION['flash_success']);
        unset($_SESSION['flash_error']);
        unset($_SESSION['flash_info']);
        unset($_SESSION['flash_warning']);

        return $messages;
    }

    /**
     * Render with flash messages automatically included
     */
    protected function renderWithFlash(string $template, array $data = []): void
    {
        $data = array_merge($data, $this->getFlashMessages());
        $this->render($template, $data);
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Return JSON success response
     */
    protected function jsonSuccess(string $message, array $data = [], int $statusCode = 200): void
    {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Return JSON error response
     */
    protected function jsonError(string $message, array $errors = [], int $statusCode = 400): void
    {
        $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
