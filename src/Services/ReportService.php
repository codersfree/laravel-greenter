<?php

namespace CodersFree\LaravelGreenter\Services;

use Greenter\Model\DocumentInterface;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Greenter\Report\Resolver\DefaultTemplateResolver;
use Illuminate\Support\Facades\File;

class ReportService
{
    public function setParams(array $params): self
    {
        $dafultParams = config('greenter.report.params');
        $customParams = array_replace_recursive($dafultParams, $params);

        config(['greenter.report.params' => $customParams]);

        return $this;
    }

    public function setOptions(array $options): self
    {
        $defaultOptions = config('greenter.report.options');
        $customOptions = array_replace_recursive($defaultOptions, $options);

        config(['greenter.report.options' => $customOptions]);

        return $this;
    }

    public function generateHtml(DocumentInterface $document)
    {
        $htmlReport = $this->createHtmlReport($document);
        return $htmlReport->render($document, $this->getParamsWithLogo());
    }

    public function generatePdf(DocumentInterface $document)
    {
        $htmlReport = $this->createHtmlReport($document);

        $pdfReport = new PdfReport($htmlReport);
        $pdfReport->setBinPath(config('greenter.report.bin_path'));
        $pdfReport->setOptions(config('greenter.report.options'));
        
        $pdf = $pdfReport->render($document, $this->getParamsWithLogo());

        if ($pdf === null) {
            throw new \RuntimeException($pdfReport->getExporter()->getError());
        }

        return $pdf;
    }

    public function createHtmlReport(DocumentInterface $document): HtmlReport
    {
        $templatePath = config('greenter.report.templates');
        $twigOptions = config('greenter.report.twigOptions');

        $htmlReport = File::isDirectory($templatePath)
            ? new HtmlReport($templatePath, $twigOptions)
            : new HtmlReport();

        $resolver = new DefaultTemplateResolver();
        $htmlReport->setTemplate($resolver->getTemplate($document));

        return $htmlReport;
    }

    protected function getParamsWithLogo(): array
    {
        $params = config('greenter.report.params');

        if (isset($params['system']['logo']) && file_exists($params['system']['logo'])) {
            $params['system']['logo'] = file_get_contents($params['system']['logo']);
        }

        return $params;
    }
}