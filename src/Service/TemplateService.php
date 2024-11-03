<?php

declare(strict_types=1);

namespace Planet\InterviewChallenge\Service;

use Smarty\Smarty;

class TemplateService
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->initSmarty();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $templateName, array $data = []): string
    {
        ob_start();
        foreach ($data as $key => $value) {
            $this->assign($key, $value);
        }
        $this->display($templateName);
        $content = ob_get_contents();
        ob_end_clean();

        if ($content === false) {
            throw new \RuntimeException('Output buffering is not active');
        }

        return $content;
    }

    /**
     * @param mixed $value
     */
    public function assign(string $key, $value): void
    {
        $this->smarty->assign($key, $value);
    }

    public function fetch(string $string): string
    {
        return $this->smarty->fetch($string);
    }

    public function display(string $templateFile): void
    {
        $this->smarty->display($templateFile);
    }

    /**
     * @throws \Smarty\Exception
     */
    private function initSmarty(): void
    {
        $this->smarty = new Smarty();

        $this->smarty
            ->setTemplateDir([ __DIR__ . '/../tpl'])
            ->setCompileDir(__DIR__ . '/../../tmp/templates_c')
            ->setCacheDir(__DIR__ . '/../../tmp/cache');

        $this->smarty->registerPlugin('modifier', 'format_date', function ($timestamp, $format = 'Y-m-d') {
            return date($format, $timestamp);
        });
    }
}
