<?php

use Dompdf\Dompdf;
use Dompdf\Options;
use Kirby\Cms\App;

App::plugin('postscriptum/article-pdf', [
	'api' => [
		'routes' => [
			[
				'pattern' => 'article-pdf/(:any)',
				'method'  => 'POST',
				'action'  => function (string $pageId) {
					$pageId = str_replace('+', '/', $pageId);
					$page = kirby()->page($pageId);

					if (!$page) {
						return ['status' => 'error', 'message' => 'Page introuvable'];
					}

					$html = buildArticleHtml($page);

					// Header for even pages: issue info
					$issueHeader = '';
					if ($page->parent() && $page->parent()->template()->name() === 'issue') {
						$issue = $page->parent();
						$issueHeader = 'Post-Scriptum ' . $issue->num() . ' — ' . $issue->title();
					}

					// Header for odd pages: author and article title
					$authors = [];
					foreach ($page->authors()->toStructure() as $author) {
						$authors[] = (string) $author->name();
					}
					$authorName = implode(', ', $authors);
					$articleTitle = $page->title()->value();
					$articleHeader = $authorName . ' — ' . $articleTitle;

					$pdf = generatePdf($html, $issueHeader, $articleHeader);

					// Save the PDF as a file on the page
					$filename = Str::slug($page->title()) . '.pdf';
					$tmpBase = tempnam(sys_get_temp_dir(), 'pdf');
					$tmp = $tmpBase . '.pdf';
					file_put_contents($tmp, $pdf);

					// Remove existing generated PDF if any
					if ($existing = $page->file($filename)) {
						$existing->delete();
					}

					$file = $page->createFile([
						'source'   => $tmp,
						'filename' => $filename,
						'template' => 'pdf',
					]);

					// Link the file to the pdf field
					$page->update([
						'pdf' => $file->filename(),
					]);

					// Clean up both temp files
					@unlink($tmp);
					@unlink($tmpBase);

					return ['status' => 'ok', 'filename' => $filename];
				}
			]
		]
	],
	'sections' => [
		'article-pdf' => [
			'props' => [
				'label' => function (string $label = 'Générer le PDF') {
					return $label;
				}
			],
			'computed' => [
				'pageId' => function () {
					return $this->model()->id();
				}
			]
		]
	]
]);

function buildArticleHtml($page)
{
	// Reset footnote collector to avoid stale state
	Footnotes::$all = [];

	$authors = [];
	foreach ($page->authors()->toStructure() as $author) {
		$name = $author->name();
		$affiliation = $author->affiliation()->isNotEmpty()
			? ' (' . $author->affiliation() . ')'
			: '';
		$authors[] = $name . $affiliation;
	}
	$authorsHtml = implode(', ', $authors);

	$title = $page->formatted_title()->isNotEmpty()
		? $page->formatted_title()->smartypants()
		: $page->title()->smartypants();

	$subtitle = $page->subtitle()->isNotEmpty()
		? '<p class="subtitle">' . $page->subtitle() . '</p>'
		: '';

	$abstractFr = $page->abstract_fr()->isNotEmpty()
		? '<div class="abstract"><h2>Résumé</h2>' . $page->abstract_fr() . '</div>'
		: '';

	$abstractEn = $page->abstract_en()->isNotEmpty()
		? '<div class="abstract"><h2>Abstract</h2>' . $page->abstract_en() . '</div>'
		: '';

	// Collect all footnotes before rendering them
	$text = sanitizeForPdf($page->text()->toBlocks()->collectFootnotes(), $page);

	$bibliography = '';
	if ($page->bibliography()->isNotEmpty()) {
		$bibliography = '<div class="bibliography"><h2>Bibliographie</h2>'
			. sanitizeForPdf($page->bibliography()->toBlocks()->collectFootnotes(), $page)
			. '</div>';
	}

	// Render footnotes after all collectFootnotes() calls
	$footnotes = $page->footnotes() ?? '';

	$coverHtml = '';
	$cover = $page->cover()->toFile();
	if ($cover) {
		$coverPath = str_replace('\\', '/', $cover->root());
		$coverHtml = '<div class="cover"><img src="' . $coverPath . '" alt="' . ($cover->alt_text()->isNotEmpty() ? $cover->alt_text()->esc() : '') . '"></div>';
	}

	$issueInfo = '';
	if ($page->parent() && $page->parent()->template()->name() === 'issue') {
		$issue = $page->parent();
		$issueInfo = '<p class="issue-info">Post-Scriptum ' . $issue->num()
			. ' — ' . $issue->title() . '</p>';
	}

	return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<style>
		body {
			font-family: "Times New Roman", Times, serif;
			font-size: 10pt;
			line-height: 1.5;
			color: #000;
			margin: 1.5cm;
		}

		h1 {
			font-size: 18pt;
			margin-bottom: 0.25em;
			line-height: 1.3;
		}

		h2 {
			font-size: 13pt;
			margin-top: 1.5em;
			margin-bottom: 0.5em;
		}

		.authors {
			font-size: 12pt;
			margin-bottom: 0.5em;
		}

		.subtitle {
			font-size: 13pt;
			font-style: italic;
			margin-bottom: 1em;
		}

		.issue-info {
			font-size: 10pt;
			margin-bottom: 2em;
			color: #555;
		}

		.abstract {
			font-size: 10pt;
			margin: 1.5em 0;
			padding: 1em;
			border-left: 3px solid #000;
		}

		.bibliography {
			margin-top: 2em;
			font-size: 10pt;
		}

		.footnotes {
			margin-top: 2em;
			font-size: 9pt;
			border-top: 1px solid #000;
			padding-top: 0.5em;
		}

		.footnote-list {
			list-style: none;
			padding-left: 0;
		}

		.footnote-list__footnote {
			margin-bottom: 0.3em;
		}

		.footnote-list__backlink {
			text-decoration: none;
		}

		img {
			max-width: 100%;
		}

		.cover {
			margin-bottom: 1.5em;
		}

		.cover img {
			width: 100%;
		}

		a {
			color: #000;
			text-decoration: underline;
		}

		em {
			font-style: italic;
		}

		.footnote-ref {
			font-size: 0.65em;
			vertical-align: super;
			text-decoration: none;
		}
	</style>
</head>
<body>
	{$coverHtml}
	<p class="authors">{$authorsHtml}</p>
	<h1>{$title}</h1>
	{$subtitle}
	{$issueInfo}
	{$abstractFr}
	{$abstractEn}
	<div class="content">{$text}</div>
	<div class="footnotes">{$footnotes}</div>
	{$bibliography}
</body>
</html>
HTML;
}

function sanitizeForPdf($html, $page)
{
	// Remove video/iframe blocks (Dompdf can't render them)
	$html = preg_replace('/<figure class="video-block">.*?<\/figure>/s', '', $html);
	$html = preg_replace('/<iframe[^>]*>.*?<\/iframe>/s', '', $html);

	// Build a map of filename => absolute source path for all page files
	$fileMap = [];
	foreach ($page->files() as $file) {
		$fileMap[$file->filename()] = str_replace('\\', '/', $file->root());
	}

	// Debug: log file map and src attributes found
	// Replace any src URL containing a known filename with the source path
	$html = preg_replace_callback(
		'/src="([^"]*)"/',
		function ($matches) use ($fileMap) {
			$url = $matches[1];
			$filename = basename($url);
			if (isset($fileMap[$filename])) {
				return 'src="' . $fileMap[$filename] . '"';
			}
			return $matches[0];
		},
		$html
	);

	return $html;
}

function generatePdf($html, $issueHeader = '', $articleHeader = '')
{
	$options = new Options();
	$options->set('isRemoteEnabled', true);
	$options->set('defaultFont', 'Times New Roman');
	$options->set('chroot', kirby()->root('index'));
	$options->set('isPhpEnabled', false);

	$dompdf = new Dompdf($options);
	$dompdf->loadHtml($html);
	$dompdf->setPaper('letter');
	$dompdf->render();

	$canvas = $dompdf->getCanvas();
	$font = $dompdf->getFontMetrics()->getFont('Times New Roman');

	// Add headers on all pages except the first
	// Even pages: issue info (Post-Scriptum {num} — {issue title})
	// Odd pages: author — article title
	if ($issueHeader || $articleHeader) {
		$canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) use ($issueHeader, $articleHeader) {
			if ($pageNumber > 1) {
				$font = $fontMetrics->getFont('Times New Roman');
				$headerText = ($pageNumber % 2 === 0) ? $issueHeader : $articleHeader;
				$canvas->text(72, 36, $headerText, $font, 9, [0, 0, 0]);
			}
		});
	}

	// Add page numbers (centered at bottom)
	$canvas->page_text(
		$canvas->get_width() / 2 - 15,
		$canvas->get_height() - 36,
		'{PAGE_NUM} / {PAGE_COUNT}',
		$font,
		9
	);

	return $dompdf->output();
}
