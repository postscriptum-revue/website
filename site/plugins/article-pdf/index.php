<?php

use Kirby\Cms\App;
use Mpdf\Mpdf;

App::plugin('postscriptum/article-pdf', [
	'hooks' => [
		'page.create:after' => function ($page) {
			if ($page->template()->name() === 'issue') {
				$page->update([
					'logo_style_p' => str_pad(random_int(1, 20), 2, '0', STR_PAD_LEFT),
					'logo_style_s' => str_pad(random_int(1, 20), 2, '0', STR_PAD_LEFT),
				]);
			}
		}
	],
	'routes' => [
		[
			'pattern' => 'logos',
			'action'  => function () {
				$issues = page('numeros')->children()->listed()->sortBy('num', 'desc');
				$logosDir = realpath(__DIR__ . '/logos');

				$html = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">';
				$html .= '<title>Logos — Post-Scriptum</title>';
				$html .= '<style>
					body { font-family: system-ui, sans-serif; max-width: 900px; margin: 2rem auto; padding: 0 1rem; color: #1a1a1a; }
					h1 { font-size: 1.5rem; margin-bottom: 2rem; }
					table { width: 100%; border-collapse: collapse; }
					th { text-align: left; font-size: 0.8rem; color: #999; text-transform: uppercase; letter-spacing: 0.05em; padding: 0.5rem 1rem 0.5rem 0; border-bottom: 2px solid #ddd; }
					td { padding: 0.75rem 1rem 0.75rem 0; border-bottom: 1px solid #eee; vertical-align: middle; }
					.col-logo { width: 220px; }
					.col-ps { width: 60px; }
					.col-num { width: 50px; }
					.col-title { }
					.col-style { width: 100px; font-size: 0.85rem; color: #666; }
					.col-dl { width: 110px; text-align: right; white-space: nowrap; }
					.dl-btn { display: inline-block; padding: 0.3rem 0.6rem; font-size: 0.8rem; color: #555; border: 1px solid #ccc; border-radius: 3px; text-decoration: none; cursor: pointer; background: #fafafa; }
					.dl-btn:hover { background: #eee; }
					.logo-container { width: 220px; height: 28px; display: flex; align-items: center; }
					.logo-container img { height: 22px; width: auto; display: block; }
					.no-logo { color: #999; font-style: italic; font-size: 0.85rem; }
				</style></head><body>';
				$html .= '<h1>Logos par numéro</h1>';
				$html .= '<table><thead><tr>';
				$html .= '<th class="col-logo">Logo</th>';
				$html .= '<th class="col-ps">PS</th>';
				$html .= '<th class="col-num">N°</th>';
				$html .= '<th class="col-title">Titre</th>';
				$html .= '<th class="col-style">Styles</th>';
				$html .= '<th class="col-dl"></th>';
				$html .= '</tr></thead><tbody>';

				foreach ($issues as $issue) {
					$num = $issue->num();
					$title = $issue->title();
					$p = str_pad($issue->logo_style_p()->value() ?: '01', 2, '0', STR_PAD_LEFT);
					$s = str_pad($issue->logo_style_s()->value() ?: '01', 2, '0', STR_PAD_LEFT);
					$logoFile = $logosDir . '/logo-p' . $p . '-s' . $s . '.png';
					$psFile = $logosDir . '/logo-ps-p' . $p . '-s' . $s . '.png';

					$logoData = file_exists($logoFile) ? base64_encode(file_get_contents($logoFile)) : '';
					$psData = file_exists($psFile) ? base64_encode(file_get_contents($psFile)) : '';

					$html .= '<tr>';

					// Full logo
					$html .= '<td class="col-logo"><div class="logo-container">';
					if ($logoData) {
						$html .= '<img src="data:image/png;base64,' . $logoData . '" alt="Logo n°' . $num . '" style="height: 22px; width: auto;">';
					} else {
						$html .= '<span class="no-logo">—</span>';
					}
					$html .= '</div></td>';

					// PS logo
					$html .= '<td class="col-ps"><div class="logo-container">';
					if ($psData) {
						$html .= '<img src="data:image/png;base64,' . $psData . '" alt="PS n°' . $num . '" style="height: 22px; width: auto;">';
					} else {
						$html .= '<span class="no-logo">—</span>';
					}
					$html .= '</div></td>';

					$html .= '<td class="col-num">' . $num . '</td>';
					$html .= '<td class="col-title">' . htmlspecialchars($title) . '</td>';
					$html .= '<td class="col-style">P' . $p . ' / S' . $s . '</td>';

					// Download buttons
					$html .= '<td class="col-dl">';
					if ($logoData) {
						$html .= '<a class="dl-btn" href="data:image/png;base64,' . $logoData . '" download="postscriptum-' . $num . '.png">Logo</a> ';
					}
					if ($psData) {
						$html .= '<a class="dl-btn" href="data:image/png;base64,' . $psData . '" download="ps-' . $num . '.png">PS</a>';
					}
					$html .= '</td>';

					$html .= '</tr>';
				}

				$html .= '</tbody></table>';

				$html .= '</body></html>';
				return new Kirby\Http\Response($html, 'text/html', 200);
			}
		]
	],
	'api' => [
		'routes' => [
			[
				'pattern' => 'article-pdf/(:any)',
				'method'  => 'POST',
				'action'  => function (string $pageId) {
					try {
						$pageId = str_replace('+', '/', $pageId);
						$page = kirby()->page($pageId);

						if (!$page) {
							return ['status' => 'error', 'message' => 'Page introuvable'];
						}

						$template = $page->template()->name();
						if ($template === 'creation') {
							$html = buildCreationHtml($page);
							$pdf = generateCreationPdf($html, $page);
						} elseif ($template === 'review') {
							$html = buildReviewHtml($page);
							$pdf = generateReviewPdf($html, $page);
						} elseif ($template === 'interview') {
							$html = buildInterviewHtml($page);
							$pdf = generateInterviewPdf($html, $page);
						} else {
							$html = buildArticleHtml($page);
							$pdf = generatePdf($html, $page);
						}

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
					} catch (Throwable $e) {
						return ['status' => 'error', 'message' => $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()];
					}
				}
			]
		],
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

/* ===================================================================
   SHARED HELPERS — mPDF instance, headers, footers, fonts
   =================================================================== */

/**
 * Create a configured mPDF instance with EB Garamond fonts.
 */
function createMpdf(array $overrides = []): \Mpdf\Mpdf
{
	$defaults = [
		'mode'           => 'utf-8',
		'format'         => 'Letter',
		'margin_left'    => 22,
		'margin_right'   => 22,
		'margin_top'     => 28,
		'margin_bottom'  => 22,
		'margin_header'  => 10,
		'margin_footer'  => 10,
		'default_font'   => 'EB Garamond',
		'tempDir'        => sys_get_temp_dir() . '/mpdf',
		'mirrorMargins'  => true,
	];

	$mpdf = new \Mpdf\Mpdf(array_merge($defaults, $overrides));

	$fontsDir = realpath(__DIR__ . '/fonts');

	// Register Mercure (primary font)
	if ($fontsDir && file_exists($fontsDir . '/Mercure-Regular.ttf')) {
		$mpdf->fontdata['mercure'] = [
			'R'  => $fontsDir . '/Mercure-Regular.ttf',
			'I'  => $fontsDir . '/Mercure-Italic.ttf',
			'B'  => $fontsDir . '/Mercure-Regular.ttf',
			'BI' => $fontsDir . '/Mercure-Italic.ttf',
		];
		$mpdf->default_font = 'mercure';
	}

	// Register EB Garamond (fallback)
	if ($fontsDir && file_exists($fontsDir . '/EBGaramond[wght].ttf')) {
		$mpdf->fontdata['ebgaramond'] = [
			'R'  => $fontsDir . '/EBGaramond[wght].ttf',
			'I'  => $fontsDir . '/EBGaramond-Italic[wght].ttf',
			'B'  => $fontsDir . '/EBGaramond[wght].ttf',
			'BI' => $fontsDir . '/EBGaramond-Italic[wght].ttf',
		];
		if (!isset($mpdf->fontdata['mercure'])) {
			$mpdf->default_font = 'ebgaramond';
		}
	}

	if (!isset($mpdf->fontdata['mercure']) && !isset($mpdf->fontdata['ebgaramond'])) {
		$mpdf->default_font = 'Times New Roman';
	}

	return $mpdf;
}

/**
 * Get common page metadata (authors, issue header, date).
 */
function getPageMeta($page): array
{
	$authors = [];
	foreach ($page->authors()->toStructure() as $author) {
		$authors[] = (string) $author->name();
	}
	$authorName = implode(', ', $authors);

	$issueHeader = 'Post-Scriptum';
	$issueNum = '';
	$issueTitle = '';
	if ($page->parent() && $page->parent()->template()->name() === 'issue') {
		$issue = $page->parent();
		$issueNum = $issue->num();
		$issueTitle = (string) $issue->title();
		$issueHeader = 'Post-Scriptum ' . $issueNum . ' — ' . $issueTitle;
	}

	$pubDate = '';
	if (method_exists($page, 'fmt_date')) {
		$pubDate = $page->fmt_date();
	}

	return [
		'authorName'  => $authorName,
		'issueHeader' => $issueHeader,
		'issueNum'    => $issueNum,
		'issueTitle'  => $issueTitle,
		'pubDate'     => $pubDate,
		'articleTitle' => $page->title()->value(),
	];
}

/**
 * Register alternating headers and footers on an mPDF instance.
 * Even: issue info (left) + date (right)
 * Odd: author (left) + article title (right)
 *
 * @param string $prefix  Unique prefix for header/footer names
 */
function registerHeadersFooters(\Mpdf\Mpdf $mpdf, array $meta, string $prefix = ''): void
{
	$style = 'font-size: 8pt; color: #999; padding-bottom: 4pt; border-bottom: 0.5pt solid #ccc; vertical-align: top;';
	$tdStyle = 'vertical-align: top;';

	$mpdf->DefHTMLHeaderByName(
		$prefix . 'evenHeader',
		'<table width="100%" style="' . $style . '"><tr>'
		. '<td style="' . $tdStyle . 'text-align: left; font-style: italic;">' . htmlspecialchars($meta['issueHeader']) . '</td>'
		. '<td style="' . $tdStyle . 'text-align: right;">' . htmlspecialchars($meta['pubDate']) . '</td>'
		. '</tr></table>'
	);

	$mpdf->DefHTMLHeaderByName(
		$prefix . 'oddHeader',
		'<table width="100%" style="' . $style . '"><tr>'
		. '<td style="' . $tdStyle . 'text-align: left;">' . htmlspecialchars($meta['authorName']) . '</td>'
		. '<td style="' . $tdStyle . 'text-align: right; font-style: italic;">' . htmlspecialchars($meta['articleTitle']) . '</td>'
		. '</tr></table>'
	);

	$footerHtml = '<div style="text-align: center; font-size: 8pt; color: #999;">{PAGENO}</div>';
	$mpdf->DefHTMLFooterByName($prefix . 'evenFooter', $footerHtml);
	$mpdf->DefHTMLFooterByName($prefix . 'oddFooter', $footerHtml);
}

/**
 * Suppress headers/footers (for title pages).
 */
function suppressHeadersFooters(\Mpdf\Mpdf $mpdf): void
{
	$mpdf->SetHTMLHeader('', 'O', true);
	$mpdf->SetHTMLHeader('', 'E', true);
	$mpdf->SetHTMLFooter('', 'O');
	$mpdf->SetHTMLFooter('', 'E');
}

/**
 * Activate headers/footers from current page.
 */
function activateHeadersFooters(\Mpdf\Mpdf $mpdf, string $prefix = '', bool $showThisPage = true): void
{
	$mpdf->SetHTMLHeaderByName($prefix . 'oddHeader', 'O', $showThisPage);
	$mpdf->SetHTMLHeaderByName($prefix . 'evenHeader', 'E', $showThisPage);
	$mpdf->SetHTMLFooterByName($prefix . 'oddFooter', 'O');
	$mpdf->SetHTMLFooterByName($prefix . 'evenFooter', 'E');
}

/**
 * Build pagebreak HTML that activates headers/footers.
 */
function pagebreakWithHeaders(string $prefix = ''): string
{
	return '<pagebreak'
		. ' odd-header-name="html_' . $prefix . 'oddHeader"'
		. ' even-header-name="html_' . $prefix . 'evenHeader"'
		. ' odd-header-value="1" even-header-value="1"'
		. ' odd-footer-name="html_' . $prefix . 'oddFooter"'
		. ' even-footer-name="html_' . $prefix . 'evenFooter"'
		. ' odd-footer-value="1" even-footer-value="1" />';
}

/**
 * Generate sethtmlpageheader/footer tags (for no-title-page templates).
 */
function delayedHeadersHtml(string $prefix = ''): string
{
	return '<sethtmlpageheader name="html_' . $prefix . 'oddHeader" value="1" show-this-page="0" />'
		. '<sethtmlpageheader name="html_' . $prefix . 'evenHeader" value="1" show-this-page="0" side="E" />'
		. '<sethtmlpagefooter name="html_' . $prefix . 'oddFooter" value="1" show-this-page="0" />'
		. '<sethtmlpagefooter name="html_' . $prefix . 'evenFooter" value="1" show-this-page="0" side="E" />';
}

/**
 * Build the issue header block (shared across all templates).
 * Shows the issue logo + "n° X — Issue Title" with rules.
 * Returns empty string if page is not part of an issue.
 */
function buildIssueHeader(array $meta, $page = null): string
{
	if (empty($meta['issueNum'])) {
		return '';
	}

	// Look for the issue logo PNG — first in the issue directory, then in the pre-generated set
	$logoHtml = '<div class="issue-header__journal">Post-Scriptum</div>';
	if ($page && $page->parent()) {
		$issue = $page->parent();
		$logoPng = null;

		// Check issue directory first (legacy per-issue logos)
		$issueDir = str_replace('\\', '/', $issue->root());
		$issueLogo = $issueDir . '/logo-' . $meta['issueNum'] . '-postscriptum-black.png';
		if (file_exists($issueLogo)) {
			$logoPng = $issueLogo;
		}

		// Fall back to pre-generated logo based on stylistic set values
		if (!$logoPng) {
			$pStyle = str_pad($issue->logo_style_p()->value(), 2, '0', STR_PAD_LEFT);
			$sStyle = str_pad($issue->logo_style_s()->value(), 2, '0', STR_PAD_LEFT);
			$pregenLogo = realpath(__DIR__ . '/logos') . '/logo-p' . $pStyle . '-s' . $sStyle . '.png';
			if (file_exists($pregenLogo)) {
				$logoPng = str_replace('\\', '/', $pregenLogo);
			}
		}

		if ($logoPng) {
			$logoHtml = '<div class="issue-header__logo"><img src="' . $logoPng . '" style="height: 1.2cm; width: auto;"></div>';
		}
	}

	return '<div class="issue-header">'
		. '<div class="issue-header__rule-thick"></div>'
		. $logoHtml
		. '<div class="issue-header__line">n&deg;&thinsp;' . $meta['issueNum'] . '&ensp;&mdash;&ensp;' . htmlspecialchars($meta['issueTitle']) . '</div>'
		. '<div class="issue-header__rule"></div>'
		. '</div>';
}



/**
 * Shared CSS for the issue header block.
 */
function issueHeaderCss(): string
{
	return <<<CSS
.issue-header {
	margin-bottom: 1em;
}
.issue-header__rule-thick {
	border-bottom: 2pt solid #1a1a1a;
	margin-bottom: 0.2em;
}
.issue-header__rule {
	border-bottom: 0.5pt solid #999;
	margin-top: 0.2em;
}
.issue-header__journal {
	font-size: 14pt;
	font-weight: bold;
	letter-spacing: 0.1em;
	text-transform: uppercase;
	color: #1a1a1a;
	margin: 0.15em 0 0;
}
.issue-header__logo {
	margin: 0.2em 0 0;
}
.issue-header__line {
	font-size: 10pt;
	font-style: italic;
	color: #555;
	margin: 0.1em 0 0;
}
CSS;
}

/**
 * Generate PDF using mPDF — article template (title page, then content).
 */
function generatePdf(array $html, $page): string
{
	$mpdf = createMpdf();
	$meta = getPageMeta($page);
	registerHeadersFooters($mpdf, $meta);
	suppressHeadersFooters($mpdf);
	$mpdf->WriteHTML($html['page1']);
	activateHeadersFooters($mpdf, '', false);
	$mpdf->WriteHTML($html['body']);
	return $mpdf->Output('', 'S');
}

/**
 * Build the complete HTML document for the article PDF.
 */
function buildArticleHtml($page): array
{
	// Reset footnote collector to avoid stale state
	Footnotes::$all = [];

	// --- Metadata ---

	$authors = [];
	$authorsWithAffiliations = [];
	foreach ($page->authors()->toStructure() as $author) {
		$name = (string) $author->name();
		$authors[] = $name;
		$affiliation = $author->affiliation()->isNotEmpty()
			? '<span class="affiliation">' . $author->affiliation() . '</span>'
			: '';
		$authorsWithAffiliations[] = '<span class="author-name">' . $name . '</span>' . ($affiliation ? '<br>' . $affiliation : '');
	}

	$title = $page->formatted_title()->isNotEmpty()
		? $page->formatted_title()->smartypants()
		: $page->title()->smartypants();

	$subtitle = $page->subtitle()->isNotEmpty()
		? '<p class="title-page__subtitle">' . $page->subtitle() . '</p>'
		: '';

	// Issue info
	$issueNum = '';
	$issueTitle = '';
	if ($page->parent() && $page->parent()->template()->name() === 'issue') {
		$issue = $page->parent();
		$issueNum = $issue->num();
		$issueTitle = $issue->title();
	}

	// Cover image
	$coverHtml = '';
	$cover = $page->cover()->toFile();
	if ($cover) {
		$coverPath = str_replace('\\', '/', $cover->root());
		$coverHtml = '<div class="title-page__cover"><img src="' . $coverPath . '" alt="' . ($cover->alt_text()->isNotEmpty() ? $cover->alt_text()->esc() : '') . '"></div>';
	}

	// Publication date
	$pubDate = '';
	if (method_exists($page, 'fmt_date')) {
		$pubDate = $page->fmt_date();
	}

	// Article URL
	$articleUrl = $page->url();

	// --- Abstracts ---

	$abstractFr = $page->abstract_fr()->isNotEmpty()
		? (string) $page->abstract_fr()
		: '';

	$abstractEn = $page->abstract_en()->isNotEmpty()
		? (string) $page->abstract_en()
		: '';

	$keywordsFr = $page->keywords_fr()->isNotEmpty()
		? '<p class="keywords"><strong>Mots-clés :</strong> ' . $page->keywords_fr() . '</p>'
		: '';

	$keywordsEn = $page->keywords_en()->isNotEmpty()
		? '<p class="keywords"><strong>Keywords:</strong> ' . $page->keywords_en() . '</p>'
		: '';

	// --- Body text with block-splitting ---

	$textBlocks = $page->text()->toBlocks()->collectFootnotes();
	$bodyHtml = buildBodyWithBlockSplitting($textBlocks, $page);

	// --- Bibliography ---

	$bibliography = '';
	if ($page->bibliography()->isNotEmpty()) {
		$bibliography = '<div class="bibliography"><h2>Bibliographie</h2>'
			. sanitizeForPdf($page->bibliography()->toBlocks()->collectFootnotes(), $page)
			. '</div>';
	}

	// --- Footnotes ---

	$footnotes = $page->footnotes() ?? '';

	// --- Authors list for title page ---

	$authorsListHtml = '';
	foreach ($authorsWithAffiliations as $a) {
		$authorsListHtml .= '<p class="title-page__author">' . $a . '</p>';
	}

	$css = issueHeaderCss() . buildPdfCss();
	$meta = getPageMeta($page);
	$issueHeaderHtml = buildIssueHeader($meta, $page);

	$rule = '<div class="rule"></div>';
	$ruleThick = '<div class="rule rule--thick"></div>';

	$page1 = <<<HTML
<style>{$css}</style>

<!-- PAGE 1: Title page -->
<div class="title-page">
	{$issueHeaderHtml}

	{$coverHtml}

	<h1 class="title-page__title">{$title}</h1>
	{$subtitle}

	{$rule}

	<div class="title-page__authors">
		{$authorsListHtml}
	</div>

	<div class="title-page__footer">
		{$ruleThick}
		<p class="title-page__date">{$pubDate}</p>
	</div>
</div>
HTML;

	$body = <<<HTML
<!-- Abstracts side by side -->
<columns column-count="2" column-gap="5" />
<div class="abstract">
	<h2>Résumé</h2>
	{$abstractFr}
	{$keywordsFr}
</div>
<columnbreak />
<div class="abstract">
	<h2>Abstract</h2>
	{$abstractEn}
	{$keywordsEn}
</div>
<columns column-count="1" />

<div style="margin-bottom: 2em;"></div>

{$bodyHtml}

<div class="footnotes">
	<h2>Notes</h2>
	{$footnotes}
</div>

{$bibliography}
HTML;

	return ['page1' => $page1, 'body' => $body];
}

/**
 * Build body HTML with block-splitting for full-width images/audio.
 * mPDF supports column-count via CSS, and column-span: all for breaking out.
 * We use explicit column sections to ensure images/audio go full-width.
 */
function buildBodyWithBlockSplitting($blocks, $page): string
{
	$html = '';
	$inColumns = false;

	foreach ($blocks as $block) {
		$type = $block->type();
		$isFullWidth = in_array($type, ['image', 'audio']);

		if ($isFullWidth) {
			if ($inColumns) {
				$html .= '</div><columns column-count="1" />';
				$inColumns = false;
			}

			if ($type === 'image') {
				$html .= renderImageBlock($block, $page);
			} elseif ($type === 'audio') {
				$html .= renderAudioBlock($block, $page);
			}
		} else {
			if (!$inColumns) {
				$html .= '<columns column-count="2" column-gap="5" /><div class="content">';
				$inColumns = true;
			}

			$blockHtml = sanitizeForPdf($block, $page);
			$html .= $blockHtml;
		}
	}

	if ($inColumns) {
		$html .= '</div><columns column-count="1" />';
	}

	return $html;
}

/**
 * Render an image block at full width.
 */
function renderImageBlock($block, $page): string
{
	$content = $block->toArray()['content'] ?? [];

	$src = '';
	$alt = '';
	$caption = '';

	if (!empty($content['image'])) {
		$images = Yaml::decode($content['image']);
		if (!empty($images)) {
			$filename = is_array($images) ? $images[0] : $images;
			$file = $page->file($filename);
			if ($file) {
				$src = str_replace('\\', '/', $file->root());
				$alt = $file->alt_text()->isNotEmpty() ? $file->alt_text()->esc() : '';
			}
		}
	}

	if (!empty($content['alt'])) {
		$alt = $content['alt'];
	}

	if (!empty($content['caption'])) {
		$caption = $content['caption'];
	}

	if (!$src) {
		return '<div class="full-width">' . sanitizeForPdf($block, $page) . '</div>';
	}

	$captionText = $caption ?: $alt;
	$captionHtml = $captionText ? '<p class="image-caption">' . $captionText . '</p>' : '';

	return <<<HTML
<div class="full-width image-block">
	<img src="{$src}" alt="{$alt}">
	{$captionHtml}
</div>
HTML;
}

/**
 * Render an audio block as a styled placeholder with clickable URL.
 */
function renderAudioBlock($block, $page): string
{
	$content = $block->toArray()['content'] ?? [];

	$url = '';
	$caption = '';

	if (!empty($content['url'])) {
		$url = $content['url'];
	}

	if (!empty($content['caption'])) {
		$caption = $content['caption'];
	}

	if (!$url && !empty($content['source'])) {
		$sources = Yaml::decode($content['source']);
		if (!empty($sources)) {
			$filename = is_array($sources) ? $sources[0] : $sources;
			$file = $page->file($filename);
			if ($file) {
				$url = $file->url();
			}
		}
	}

	$urlHtml = $url ? '<a href="' . $url . '">' . $url . '</a>' : '';
	$captionHtml = $caption ? '<p class="audio-block__caption">' . $caption . '</p>' : '';

	return <<<HTML
<div class="full-width audio-block">
	<p class="audio-block__icon">&#9835; Fichier audio</p>
	<p class="audio-block__url">{$urlHtml}</p>
	{$captionHtml}
</div>
HTML;
}

/**
 * Sanitize HTML content for PDF rendering.
 */
function sanitizeForPdf($html, $page): string
{
	$html = (string) $html;

	// Remove video/iframe blocks and literal "___" separators
	$html = preg_replace('/<p>\s*_{2,}\s*<\/p>/s', '', $html);
	$html = preg_replace('/<figure class="video-block">.*?<\/figure>/s', '', $html);
	$html = preg_replace('/<iframe[^>]*>.*?<\/iframe>/s', '', $html);

	// Replace audio figure blocks with styled placeholders
	$html = preg_replace_callback(
		'/<figure class="audio-block">(.*?)<\/figure>/s',
		function ($matches) {
			$inner = $matches[1];
			$url = '';
			$caption = '';

			if (preg_match('/src="([^"]*)"/', $inner, $srcMatch)) {
				$url = $srcMatch[1];
			}
			if (preg_match('/<figcaption>(.*?)<\/figcaption>/s', $inner, $capMatch)) {
				$caption = $capMatch[1];
			}

			$urlHtml = $url ? '<a href="' . $url . '">' . $url . '</a>' : '';
			$captionHtml = $caption ? '<p class="audio-block__caption">' . $caption . '</p>' : '';

			return '<div class="audio-block"><p class="audio-block__icon">&#9835; Fichier audio</p><p class="audio-block__url">' . $urlHtml . '</p>' . $captionHtml . '</div>';
		},
		$html
	);

	// Build a map of filename => absolute path for all page files
	$fileMap = [];
	foreach ($page->files() as $file) {
		$fileMap[$file->filename()] = str_replace('\\', '/', $file->root());
	}

	// Replace src URLs with absolute file paths
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

/**
 * Build the CSS for the PDF document.
 */
function buildPdfCss(): string
{
	return <<<CSS
/* ===================================================
   POST-SCRIPTUM — Article PDF Stylesheet
   =================================================== */

/* --- Base typography --- */
body {
	font-family: 'Mercure', 'EB Garamond', 'Times New Roman', Times, serif;
	font-size: 10pt;
	line-height: 1.45;
	color: #1a1a1a;
}

/* --- Rules --- */
.rule {
	border-bottom: 0.5pt solid #999;
	margin: 0.8em 0;
}
.rule--thick {
	border-bottom-width: 2pt;
	border-bottom-color: #1a1a1a;
}

/* ===================================================
   TITLE PAGE
   =================================================== */
.title-page {
	padding-top: 1cm;
}

/* Cover image */
.title-page__cover {
	margin: 1em auto;
	text-align: center;
}
.title-page__cover img {
	max-width: 85%;
	max-height: 11cm;
}

/* Article title: left-aligned, large */
.title-page__title {
	font-size: 22pt;
	font-weight: bold;
	line-height: 1.2;
	margin-top: 0.6em;
	margin-bottom: 0.15em;
	text-align: left;
	color: #000;
}

.title-page__subtitle {
	font-size: 13pt;
	font-style: italic;
	color: #444;
	text-align: left;
	margin: 0 0 0.3em;
}

/* Authors block */
.title-page__authors {
	margin: 0.5em 0;
}
.title-page__author {
	font-size: 11pt;
	margin-bottom: 0.1em;
	line-height: 1.35;
	text-align: left;
}
.title-page__author .affiliation {
	font-size: 9pt;
	font-style: italic;
	color: #666;
}

/* Footer */
.title-page__footer {
	margin-top: 2em;
}
.title-page__date {
	font-size: 9pt;
	color: #666;
	text-align: left;
	margin-top: 0.3em;
}

/* ===================================================
   ABSTRACTS
   =================================================== */
.abstracts {
	margin-bottom: 1.5em;
}

.abstract {
	font-size: 8.5pt;
	line-height: 1.35;
	margin: 0 0 0.5em 0;
	padding: 0.7em 1.2em 0.7em 1em;
	border-left: 1pt solid #1a1a1a;
	color: #333;
}

.abstract h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.4em 0;
	color: #1a1a1a;
}

.abstract p {
	margin: 0 0 0.4em 0;
}

.keywords {
	font-size: 8.5pt;
	margin: 0 0 1.2em 1em;
	color: #555;
	font-style: italic;
}

/* ===================================================
   TWO-COLUMN CONTENT
   =================================================== */
.content {
	text-align: left;
}

.content h2 {
	font-size: 12pt;
	font-weight: bold;
	margin-top: 1.4em;
	margin-bottom: 0.5em;
	letter-spacing: 0.02em;
	border-bottom: 0.5pt solid #ddd;
	padding-bottom: 0.2em;
}

.content h3 {
	font-size: 10.5pt;
	font-weight: bold;
	font-style: italic;
	margin-top: 1.1em;
	margin-bottom: 0.3em;
}

.content p {
	margin: 0 0 0.5em 0;
	text-indent: 1.2em;
}

.content p:first-child,
.content h2 + p,
.content h3 + p {
	text-indent: 0;
}

.content blockquote {
	margin: 1em 0 1em 1.5em;
	padding: 0 0 0 1em;
	font-size: 9pt;
	line-height: 1.5;
	border-left: 0.5pt solid #bbb;
	color: #333;
}

.content blockquote p {
	text-indent: 0;
	margin-bottom: 0.3em;
}

/* Horizontal rule within content */
.content hr {
	border: none;
	border-top: 0.5pt solid #ccc;
	margin: 1.2em 2em;
}

/* ===================================================
   FULL-WIDTH BLOCKS (images, audio)
   =================================================== */
.full-width {
	width: 100%;
	margin: 1.2em 0;
}

.image-block {
	text-align: center;
	padding: 0.5em 0;
}

.image-block img {
	max-width: 90%;
	max-height: 18cm;
}

.image-caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin-top: 0.5em;
	text-align: center;
}

/* --- Audio placeholder --- */
.audio-block {
	border: 0.75pt solid #ccc;
	border-left: 1pt solid #999;
	padding: 0.7em 1em;
	margin: 1.2em 2em;
	background-color: #fafafa;
}

.audio-block__icon {
	font-size: 10pt;
	font-weight: bold;
	color: #555;
	margin: 0 0 0.2em 0;
}

.audio-block__url {
	font-size: 8pt;
	margin: 0 0 0.2em 0;
	word-wrap: break-word;
	color: #333;
}

.audio-block__url a {
	color: #333;
	text-decoration: underline;
}

.audio-block__caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin: 0;
}

/* ===================================================
   FOOTNOTES
   =================================================== */
.footnotes {
	margin-top: 2.5em;
	padding-top: 1em;
	font-size: 8.5pt;
	line-height: 1.5;
	border-top: 0.75pt solid #1a1a1a;
}

.footnotes h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.6em 0;
}

.footnote-list {
	list-style: none;
	padding-left: 0;
}

.footnote-list__footnote {
	margin-bottom: 0.25em;
	padding-left: 1.5em;
	text-indent: -1.5em;
}

.footnote-list__footnote-number {
	font-weight: bold;
	margin-right: 0.3em;
}

.footnote-list__backlink {
	text-decoration: none;
	color: #1a1a1a;
}

.footnote-ref {
	font-size: 0.6em;
	vertical-align: super;
	text-decoration: none;
	color: #1a1a1a;
	font-weight: bold;
}

/* ===================================================
   BIBLIOGRAPHY
   =================================================== */
.bibliography {
	margin-top: 2em;
	font-size: 9pt;
	line-height: 1.5;
}

.bibliography h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin-bottom: 0.6em;
	border-bottom: 0.5pt solid #ddd;
	padding-bottom: 0.3em;
}

.bibliography p,
.bibliography li {
	margin-bottom: 0.35em;
	padding-left: 2em;
	text-indent: -2em;
}

.bibliography ul {
	list-style: none;
	padding-left: 0;
}

/* ===================================================
   GENERAL
   =================================================== */
a {
	color: #1a1a1a;
	text-decoration: underline;
}

em {
	font-style: italic;
}

strong {
	font-weight: bold;
}

img {
	max-width: 100%;
}
CSS;
}

/* ===================================================================
   CREATION (Poetry) PDF — no title page, airy single-column layout
   =================================================================== */

/**
 * Generate PDF for a creation (poetry) page.
 */
function generateCreationPdf(array $html, $page): string
{
	$mpdf = createMpdf(['margin_left' => 30, 'margin_right' => 30, 'margin_top' => 25]);
	$meta = getPageMeta($page);
	registerHeadersFooters($mpdf, $meta, 'cr');
	suppressHeadersFooters($mpdf);
	$mpdf->WriteHTML($html['page1']);
	activateHeadersFooters($mpdf, 'cr', false);
	$mpdf->WriteHTML($html['body']);
	return $mpdf->Output('', 'S');
}

/**
 * Build HTML for a creation (poetry) page.
 * No title page — starts directly with author, title, then the poem.
 */
function buildCreationHtml($page): array
{
	Footnotes::$all = [];

	// Author(s)
	$authors = [];
	foreach ($page->authors()->toStructure() as $author) {
		$name = (string) $author->name();
		$affiliation = $author->affiliation()->isNotEmpty()
			? '<span class="cr-affiliation">' . $author->affiliation() . '</span>'
			: '';
		$authors[] = $name . ($affiliation ? '<br>' . $affiliation : '');
	}

	$title = $page->formatted_title()->isNotEmpty()
		? $page->formatted_title()->smartypants()
		: $page->title()->smartypants();

	$subtitle = $page->subtitle()->isNotEmpty()
		? '<p class="cr-subtitle">' . $page->subtitle() . '</p>'
		: '';

	$authorsHtml = '';
	foreach ($authors as $a) {
		$authorsHtml .= '<p class="cr-author">' . $a . '</p>';
	}

	// Body text — single column, preserve line breaks for poetry
	$textBlocks = $page->text()->toBlocks()->collectFootnotes();
	$bodyHtml = '';
	foreach ($textBlocks as $block) {
		$type = $block->type();
		if ($type === 'image') {
			$bodyHtml .= renderImageBlock($block, $page);
		} elseif ($type === 'audio') {
			$bodyHtml .= renderAudioBlock($block, $page);
		} elseif ($type === 'line') {
			$bodyHtml .= '<div class="cr-separator">*&ensp;&ensp;*&ensp;&ensp;*</div>';
		} else {
			$bodyHtml .= sanitizeForPdf($block, $page);
		}
	}

	// Footnotes
	$footnotes = $page->footnotes() ?? '';
	$footnotesHtml = '';
	if (!empty(Footnotes::$all)) {
		$footnotesHtml = '<div class="cr-footnotes"><h2>Notes</h2>' . $footnotes . '</div>';
	}

	// Bibliography
	$bibliography = '';
	if ($page->bibliography()->isNotEmpty()) {
		$bibliography = '<div class="cr-bibliography"><h2>Bibliographie</h2>'
			. sanitizeForPdf($page->bibliography()->toBlocks()->collectFootnotes(), $page)
			. '</div>';
	}

	$css = issueHeaderCss() . buildCreationCss();
	$meta = getPageMeta($page);
	$issueHeaderHtml = buildIssueHeader($meta, $page);

	$page1 = <<<HTML
<style>{$css}</style>

{$issueHeaderHtml}

<div class="cr-header">
	{$authorsHtml}
	<h1 class="cr-title">{$title}</h1>
	{$subtitle}
</div>
HTML;

	$body = <<<HTML
<div class="cr-body">
	{$bodyHtml}
</div>

{$footnotesHtml}
{$bibliography}
HTML;

	return ['page1' => $page1, 'body' => $body];
}

/**
 * CSS for creation (poetry) PDF.
 */
function buildCreationCss(): string
{
	return <<<CSS
body {
	font-family: 'Mercure', 'EB Garamond', 'Times New Roman', Times, serif;
	font-size: 11pt;
	line-height: 1.3;
	color: #1a1a1a;
}

/* --- Header block: author + title --- */
.cr-header {
	margin-bottom: 1.5em;
	text-align: center;
	padding-bottom: 1em;
	border-bottom: 0.5pt solid #ccc;
}

.cr-author {
	font-size: 11pt;
	margin-bottom: 0.1em;
	letter-spacing: 0.03em;
}

.cr-affiliation {
	font-size: 9pt;
	font-style: italic;
	color: #666;
}

.cr-title {
	font-size: 16pt;
	font-weight: normal;
	font-style: italic;
	line-height: 1.25;
	margin: 0.6em 0 0.1em;
}

.cr-subtitle {
	font-size: 11pt;
	font-style: italic;
	color: #555;
	margin: 0;
}

/* --- Section separator (between poems) --- */
.cr-separator {
	text-align: center;
	margin: 1.8em 0;
	line-height: 1;
	color: #999;
	font-size: 10pt;
}

/* --- Poetry body --- */
.cr-body {
	margin: 0 auto;
}

.cr-body p {
	margin: 0 0 0.7em 0;
	text-align: left;
}

.cr-body blockquote {
	margin: 1.2em 2em;
	font-style: italic;
	color: #333;
}

.cr-body h2 {
	font-size: 13pt;
	font-weight: normal;
	font-style: italic;
	text-align: center;
	margin: 2em 0 1em;
}

.cr-body h3 {
	font-size: 11pt;
	font-weight: normal;
	font-style: italic;
	text-align: center;
	margin: 1.5em 0 0.8em;
}

/* Images */
.full-width {
	text-align: center;
	margin: 2em 0;
}

.image-block img {
	max-width: 90%;
	max-height: 18cm;
}

.image-caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin-top: 0.5em;
	text-align: center;
}

/* Audio */
.audio-block {
	border: 0.5pt solid #ccc;
	border-left: 1pt solid #999;
	padding: 0.7em 1em;
	margin: 1.5em 2em;
	background-color: #fafafa;
}

.audio-block__icon {
	font-size: 10pt;
	font-weight: bold;
	color: #555;
	margin: 0 0 0.2em 0;
}

.audio-block__url {
	font-size: 8pt;
	margin: 0;
	word-wrap: break-word;
}

.audio-block__url a {
	color: #333;
	text-decoration: underline;
}

.audio-block__caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin: 0.2em 0 0;
}

/* --- Footnotes --- */
.cr-footnotes {
	margin-top: 3em;
	padding-top: 1em;
	font-size: 8.5pt;
	line-height: 1.4;
	border-top: 0.5pt solid #1a1a1a;
}

.cr-footnotes h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.5em 0;
}

.footnote-list {
	list-style: none;
	padding-left: 0;
}

.footnote-list__footnote {
	margin-bottom: 0.25em;
	padding-left: 1.5em;
	text-indent: -1.5em;
}

.footnote-list__footnote-number {
	font-weight: bold;
	margin-right: 0.3em;
}

.footnote-list__backlink {
	text-decoration: none;
	color: #1a1a1a;
}

.footnote-ref {
	font-size: 0.6em;
	vertical-align: super;
	text-decoration: none;
	color: #1a1a1a;
	font-weight: bold;
}

/* --- Bibliography --- */
.cr-bibliography {
	margin-top: 2em;
	font-size: 9pt;
	line-height: 1.4;
}

.cr-bibliography h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin-bottom: 0.5em;
}

.cr-bibliography p,
.cr-bibliography li {
	margin-bottom: 0.3em;
	padding-left: 2em;
	text-indent: -2em;
}

.cr-bibliography ul {
	list-style: none;
	padding-left: 0;
}

/* --- General --- */
a {
	color: #1a1a1a;
	text-decoration: underline;
}

em {
	font-style: italic;
}

strong {
	font-weight: bold;
}

img {
	max-width: 100%;
}
CSS;
}

/* ===================================================================
   INTERVIEW PDF — dialogue-driven layout with speaker attribution
   =================================================================== */

/**
 * Generate PDF for an interview page.
 */
function generateInterviewPdf(array $html, $page): string
{
	$mpdf = createMpdf(['margin_top' => 20]);
	$meta = getPageMeta($page);
	registerHeadersFooters($mpdf, $meta, 'int');
	suppressHeadersFooters($mpdf);
	$mpdf->WriteHTML($html['page1']);
	activateHeadersFooters($mpdf, 'int', false);
	$mpdf->WriteHTML($html['body']);
	return $mpdf->Output('', 'S');
}

/**
 * Build HTML for an interview page.
 */
function buildInterviewHtml($page): array
{
	Footnotes::$all = [];

	// Authors
	$authors = [];
	$authorsWithAffiliations = [];
	foreach ($page->authors()->toStructure() as $author) {
		$name = (string) $author->name();
		$authors[] = $name;
		$affiliation = $author->affiliation()->isNotEmpty()
			? '<span class="int-affiliation">' . $author->affiliation() . '</span>'
			: '';
		$authorsWithAffiliations[] = '<span>' . $name . '</span>' . ($affiliation ? '<br>' . $affiliation : '');
	}

	$title = $page->formatted_title()->isNotEmpty()
		? $page->formatted_title()->smartypants()
		: $page->title()->smartypants();

	$subtitle = $page->subtitle()->isNotEmpty()
		? '<p class="int-subtitle">' . $page->subtitle() . '</p>'
		: '';

	// Issue info
	$issueNum = '';
	$issueTitle = '';
	if ($page->parent() && $page->parent()->template()->name() === 'issue') {
		$issue = $page->parent();
		$issueNum = $issue->num();
		$issueTitle = $issue->title();
	}

	// Publication date
	$pubDate = '';
	if (method_exists($page, 'fmt_date')) {
		$pubDate = $page->fmt_date();
	}

	// Authors list for title page
	$authorsListHtml = '';
	foreach ($authorsWithAffiliations as $a) {
		$authorsListHtml .= '<p class="int-tp-author">' . $a . '</p>';
	}

	// Abstracts
	$abstractFr = $page->abstract_fr()->isNotEmpty()
		? (string) $page->abstract_fr() : '';

	$abstractEn = $page->abstract_en()->isNotEmpty()
		? (string) $page->abstract_en() : '';

	$keywordsFr = $page->keywords_fr()->isNotEmpty()
		? '<p class="int-keywords"><strong>Mots-clés :</strong> ' . $page->keywords_fr() . '</p>'
		: '';

	$keywordsEn = $page->keywords_en()->isNotEmpty()
		? '<p class="int-keywords"><strong>Keywords:</strong> ' . $page->keywords_en() . '</p>'
		: '';

	// Body — render quote blocks as dialogue
	$textBlocks = $page->text()->toBlocks()->collectFootnotes();
	$bodyHtml = buildInterviewBody($textBlocks, $page);

	// Footnotes
	$footnotes = $page->footnotes() ?? '';
	$footnotesHtml = '';
	if (!empty(Footnotes::$all)) {
		$footnotesHtml = '<div class="int-footnotes"><h2>Notes</h2>' . $footnotes . '</div>';
	}

	// Bibliography
	$bibliography = '';
	if ($page->bibliography()->isNotEmpty()) {
		$bibliography = '<div class="int-bibliography"><h2>Bibliographie</h2>'
			. sanitizeForPdf($page->bibliography()->toBlocks()->collectFootnotes(), $page)
			. '</div>';
	}

	$rule = '<div class="rule"></div>';
	$css = issueHeaderCss() . buildInterviewCss();
	$meta = getPageMeta($page);
	$issueHeaderHtml = buildIssueHeader($meta, $page);

	$page1 = <<<HTML
<style>{$css}</style>

{$issueHeaderHtml}

<div class="int-header">
	{$authorsListHtml}
	<h1 class="int-title">{$title}</h1>
	{$subtitle}
</div>

{$rule}
HTML;

	$body = <<<HTML
{$bodyHtml}

{$footnotesHtml}
{$bibliography}
HTML;

	return ['page1' => $page1, 'body' => $body];
}

/**
 * Build interview body — quote blocks become dialogue exchanges,
 * other blocks render normally.
 */
function buildInterviewBody($blocks, $page): string
{
	$html = '';

	foreach ($blocks as $block) {
		$type = $block->type();

		if ($type === 'quote') {
			$content = $block->toArray()['content'] ?? [];
			$text = $content['text'] ?? '';
			$citation = $content['citation'] ?? '';

			// Collect footnotes from quote text
			$text = Footnotes::collect($text);
			$text = sanitizeForPdf($text, $page);

			$speakerHtml = $citation
				? '<p class="int-speaker">' . htmlspecialchars($citation) . '</p>'
				: '';

			$html .= '<div class="int-exchange">'
				. $speakerHtml
				. '<div class="int-speech">' . $text . '</div>'
				. '</div>';
		} elseif ($type === 'image') {
			$html .= renderImageBlock($block, $page);
		} elseif ($type === 'audio') {
			$html .= renderAudioBlock($block, $page);
		} elseif ($type === 'line') {
			$html .= '<div class="int-separator">*&ensp;&ensp;*&ensp;&ensp;*</div>';
		} else {
			$html .= '<div class="int-text">' . sanitizeForPdf($block, $page) . '</div>';
		}
	}

	return $html;
}

/**
 * CSS for interview PDF.
 */
function buildInterviewCss(): string
{
	return <<<CSS
body {
	font-family: 'Mercure', 'EB Garamond', 'Times New Roman', Times, serif;
	font-size: 10pt;
	line-height: 1.45;
	color: #1a1a1a;
}

/* --- Rules --- */
.rule {
	border-bottom: 0.5pt solid #999;
	margin: 0.8em 0;
}
.rule--thick {
	border-bottom-width: 2pt;
	border-bottom-color: #1a1a1a;
}

/* ===================================================
   HEADER BLOCK
   =================================================== */
.int-header {
	margin-bottom: 0.5em;
}

.int-tp-author {
	font-size: 11pt;
	margin-bottom: 0.1em;
	line-height: 1.35;
}

.int-affiliation {
	font-size: 9pt;
	font-style: italic;
	color: #666;
}

.int-title {
	font-size: 18pt;
	font-weight: bold;
	line-height: 1.2;
	margin: 0.5em 0 0.1em;
}

.int-subtitle {
	font-size: 12pt;
	font-style: italic;
	color: #444;
	margin: 0 0 0.2em;
}

/* ===================================================
   ABSTRACTS
   =================================================== */
.int-abstract {
	font-size: 8.5pt;
	line-height: 1.35;
	margin: 0 0 0.5em 0;
	padding: 0.7em 1.2em 0.7em 1em;
	border-left: 1pt solid #1a1a1a;
	color: #333;
}

.int-abstract h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.4em 0;
	color: #1a1a1a;
}

.int-abstract p {
	margin: 0 0 0.4em 0;
}

.int-keywords {
	font-size: 8.5pt;
	margin: 0.5em 0 0 0;
	color: #555;
	font-style: italic;
}

/* ===================================================
   DIALOGUE
   =================================================== */
.int-exchange {
	margin-bottom: 1em;
}

.int-speaker {
	font-weight: bold;
	font-size: 8.5pt;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	color: #1a1a1a;
	margin: 0.3em 0 0.2em 0;
}

.int-speech {
	margin: 0;
	padding-left: 0.8em;
	border-left: 0.5pt solid #ddd;
}

.int-speech p {
	margin: 0 0 0.5em 0;
}

/* Separator between sections */
.int-separator {
	text-align: center;
	margin: 1.5em 0;
	color: #999;
	font-size: 10pt;
}

/* Non-quote text blocks */
.int-text {
	margin-bottom: 0.8em;
}

.int-text p {
	margin: 0 0 0.5em 0;
}

/* ===================================================
   IMAGES & AUDIO
   =================================================== */
.full-width {
	text-align: center;
	margin: 1.2em 0;
}

.image-block img {
	max-width: 90%;
	max-height: 18cm;
}

.image-caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin-top: 0.5em;
	text-align: center;
}

.audio-block {
	border: 0.5pt solid #ccc;
	border-left: 1pt solid #999;
	padding: 0.7em 1em;
	margin: 1.5em 2em;
	background-color: #fafafa;
}

.audio-block__icon {
	font-size: 10pt;
	font-weight: bold;
	color: #555;
	margin: 0 0 0.2em 0;
}

.audio-block__url {
	font-size: 8pt;
	margin: 0;
	word-wrap: break-word;
}

.audio-block__url a {
	color: #333;
	text-decoration: underline;
}

.audio-block__caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin: 0.2em 0 0;
}

/* ===================================================
   FOOTNOTES
   =================================================== */
.int-footnotes {
	margin-top: 2.5em;
	padding-top: 1em;
	font-size: 8.5pt;
	line-height: 1.4;
	border-top: 0.75pt solid #1a1a1a;
}

.int-footnotes h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.5em 0;
}

.footnote-list {
	list-style: none;
	padding-left: 0;
}

.footnote-list__footnote {
	margin-bottom: 0.25em;
	padding-left: 1.5em;
	text-indent: -1.5em;
}

.footnote-list__footnote-number {
	font-weight: bold;
	margin-right: 0.3em;
}

.footnote-list__backlink {
	text-decoration: none;
	color: #1a1a1a;
}

.footnote-ref {
	font-size: 0.6em;
	vertical-align: super;
	text-decoration: none;
	color: #1a1a1a;
	font-weight: bold;
}

/* ===================================================
   BIBLIOGRAPHY
   =================================================== */
.int-bibliography {
	margin-top: 2em;
	font-size: 9pt;
	line-height: 1.4;
}

.int-bibliography h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin-bottom: 0.5em;
	border-bottom: 0.5pt solid #ddd;
	padding-bottom: 0.3em;
}

.int-bibliography p,
.int-bibliography li {
	margin-bottom: 0.3em;
	padding-left: 2em;
	text-indent: -2em;
}

.int-bibliography ul {
	list-style: none;
	padding-left: 0;
}

/* ===================================================
   GENERAL
   =================================================== */
a {
	color: #1a1a1a;
	text-decoration: underline;
}

em {
	font-style: italic;
}

strong {
	font-weight: bold;
}

img {
	max-width: 100%;
}
CSS;
}

/* ===================================================================
   REVIEW (Compte rendu) PDF — single-column, compact book review
   =================================================================== */

/**
 * Generate PDF for a review (compte rendu) page.
 */
function generateReviewPdf(array $html, $page): string
{
	$mpdf = createMpdf(['margin_left' => 25, 'margin_right' => 25]);
	$meta = getPageMeta($page);
	registerHeadersFooters($mpdf, $meta, 'rev');
	suppressHeadersFooters($mpdf);

	// Write page 1 (header block), then activate headers for page 2+
	$mpdf->WriteHTML($html['page1']);
	activateHeadersFooters($mpdf, 'rev', false);
	$mpdf->WriteHTML($html['body']);

	return $mpdf->Output('', 'S');
}

/**
 * Build HTML for a review (compte rendu) page.
 */
function buildReviewHtml($page): array
{
	Footnotes::$all = [];

	// Authors
	$authors = [];
	$authorsWithAffiliations = [];
	foreach ($page->authors()->toStructure() as $author) {
		$name = (string) $author->name();
		$authors[] = $name;
		$affiliation = $author->affiliation()->isNotEmpty()
			? '<span style="font-size: 9pt; font-style: italic; color: #666;">' . $author->affiliation() . '</span>'
			: '';
		$authorsWithAffiliations[] = '<span style="font-size: 11pt;">' . $name . '</span>' . ($affiliation ? '<br>' . $affiliation : '');
	}

	$title = $page->formatted_title()->isNotEmpty()
		? $page->formatted_title()->smartypants()
		: $page->title()->smartypants();

	$subtitle = $page->subtitle()->isNotEmpty()
		? '<p class="rev-subtitle">' . $page->subtitle() . '</p>'
		: '';

	// Issue info
	$issueNum = '';
	$issueTitle = '';
	if ($page->parent() && $page->parent()->template()->name() === 'issue') {
		$issue = $page->parent();
		$issueNum = $issue->num();
		$issueTitle = $issue->title();
	}

	$pubDate = '';
	if (method_exists($page, 'fmt_date')) {
		$pubDate = $page->fmt_date();
	}

	$authorsListHtml = '';
	foreach ($authorsWithAffiliations as $a) {
		$authorsListHtml .= '<div style="font-size: 11pt; margin: 0 0 0.3em; line-height: 1.4;">' . $a . '</div>';
	}

	// Cover image of the reviewed book
	$coverHtml = '';
	$cover = $page->cover()->toFile();
	if ($cover) {
		$coverPath = str_replace('\\', '/', $cover->root());
		$coverHtml = '<div class="rev-cover"><img src="' . $coverPath . '" alt="' . ($cover->alt_text()->isNotEmpty() ? $cover->alt_text()->esc() : '') . '" style="height: 8cm; width: auto;"></div>';
	}

	// Abstracts
	$abstractFr = $page->abstract_fr()->isNotEmpty()
		? (string) $page->abstract_fr() : '';

	$abstractEn = $page->abstract_en()->isNotEmpty()
		? (string) $page->abstract_en() : '';

	$keywordsFr = $page->keywords_fr()->isNotEmpty()
		? '<p class="rev-keywords"><strong>Mots-clés :</strong> ' . $page->keywords_fr() . '</p>'
		: '';

	$keywordsEn = $page->keywords_en()->isNotEmpty()
		? '<p class="rev-keywords"><strong>Keywords:</strong> ' . $page->keywords_en() . '</p>'
		: '';

	// Body — single column, no block splitting needed
	$textBlocks = $page->text()->toBlocks()->collectFootnotes();
	$bodyHtml = '';
	foreach ($textBlocks as $block) {
		$type = $block->type();
		if ($type === 'image') {
			$bodyHtml .= renderImageBlock($block, $page);
		} elseif ($type === 'audio') {
			$bodyHtml .= renderAudioBlock($block, $page);
		} else {
			$bodyHtml .= sanitizeForPdf($block, $page);
		}
	}

	// Footnotes
	$footnotes = $page->footnotes() ?? '';
	$footnotesHtml = '';
	if (!empty(Footnotes::$all)) {
		$footnotesHtml = '<div class="rev-footnotes"><h2>Notes</h2>' . $footnotes . '</div>';
	}

	// Bibliography
	$bibliography = '';
	if ($page->bibliography()->isNotEmpty()) {
		$bibliography = '<div class="rev-bibliography"><h2>Bibliographie</h2>'
			. sanitizeForPdf($page->bibliography()->toBlocks()->collectFootnotes(), $page)
			. '</div>';
	}

	$rule = '<div class="rule"></div>';
	$css = issueHeaderCss() . buildReviewCss();
	$meta = getPageMeta($page);
	$issueHeaderHtml = buildIssueHeader($meta, $page);

	$page1 = <<<HTML
<style>{$css}</style>

{$issueHeaderHtml}

<!-- Header -->
<div class="rev-header">
	<div class="rev-tp-label">Compte rendu</div>
	<table class="rev-tp-main" width="100%"><tr>
		<td class="rev-tp-left" width="72%" valign="top">
			<h1 class="rev-tp-title">{$title}</h1>
			{$subtitle}
			<div style="margin-top: 1em;">
				{$authorsListHtml}
			</div>
			<div style="font-size: 10pt; color: #555; margin-top: 0.8em;">{$pubDate}</div>
		</td>
		<td class="rev-tp-right" width="25%" valign="top">
			{$coverHtml}
		</td>
	</tr></table>
</div>
{$rule}
HTML;

	$body = <<<HTML
<div class="rev-body">
	{$bodyHtml}
</div>

{$footnotesHtml}
{$bibliography}
HTML;

	return ['page1' => $page1, 'body' => $body];
}

/**
 * CSS for review (compte rendu) PDF.
 */
function buildReviewCss(): string
{
	return <<<CSS
body {
	font-family: 'Mercure', 'EB Garamond', 'Times New Roman', Times, serif;
	font-size: 10pt;
	line-height: 1.45;
	color: #1a1a1a;
}

/* --- Rules --- */
.rule {
	border-bottom: 0.5pt solid #999;
	margin: 0.8em 0;
}
.rule--thick {
	border-bottom-width: 2pt;
	border-bottom-color: #1a1a1a;
}

/* ===================================================
   TITLE PAGE
   =================================================== */
.rev-title-page {
	padding-top: 1cm;
}

.rev-tp-journal {
	font-size: 20pt;
	font-weight: bold;
	letter-spacing: 0.12em;
	text-transform: uppercase;
	text-align: left;
	margin: 0.2em 0 0;
}

.rev-tp-issue {
	font-size: 11pt;
	font-style: italic;
	color: #555;
	text-align: left;
	margin: 0.2em 0 0.2em;
}

.rev-header {
	margin-bottom: 0.5em;
}

.rev-tp-label {
	font-size: 9pt;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: #888;
	margin: 0 0 0.6em;
}

.rev-tp-main {
	border-collapse: collapse;
}

.rev-tp-left {
	padding-right: 1.5em;
}

.rev-tp-right {
	padding-left: 0.5em;
	text-align: right;
}

.rev-tp-title {
	font-size: 16pt;
	font-weight: bold;
	line-height: 1.25;
	margin: 0 0 0.3em;
}

.rev-subtitle {
	font-size: 11pt;
	font-style: italic;
	color: #444;
	margin: 0 0 0.5em;
}

.rev-cover {
	margin: 0;
}

.rev-cover img {
	width: 2.5cm;
	height: auto;
}

.rev-tp-author {
	font-size: 12pt;
	margin: 0 0 0.1em;
	line-height: 1.35;
}

.rev-tp-author-line {
	margin: 0.3em 0 0;
}

.rev-affiliation {
	font-size: 9pt;
	font-style: italic;
	color: #666;
}

.rev-tp-date {
	font-size: 10pt;
	color: #555;
	margin-top: 0.3em;
}

/* ===================================================
   ABSTRACTS
   =================================================== */
.rev-abstract {
	font-size: 8.5pt;
	line-height: 1.35;
	margin: 0 0 0.5em 0;
	padding: 0.7em 1.2em 0.7em 1em;
	border-left: 1pt solid #1a1a1a;
	color: #333;
}

.rev-abstract h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.4em 0;
	color: #1a1a1a;
}

.rev-abstract p {
	margin: 0 0 0.4em 0;
}

.rev-keywords {
	font-size: 8.5pt;
	margin: 0.5em 0 0 0;
	color: #555;
	font-style: italic;
}

/* ===================================================
   BODY — single column
   =================================================== */
.rev-body {
	text-align: left;
}

.rev-body p {
	margin: 0 0 0.6em 0;
	text-indent: 1.2em;
}

.rev-body p:first-child {
	text-indent: 0;
}

.rev-body h2 {
	font-size: 12pt;
	font-weight: bold;
	margin-top: 1.4em;
	margin-bottom: 0.5em;
	border-bottom: 0.5pt solid #ddd;
	padding-bottom: 0.2em;
}

.rev-body h3 {
	font-size: 10.5pt;
	font-weight: bold;
	font-style: italic;
	margin-top: 1.1em;
	margin-bottom: 0.3em;
}

.rev-body blockquote {
	margin: 1em 0 1em 2em;
	padding: 0 0 0 1em;
	font-size: 9.5pt;
	line-height: 1.4;
	border-left: 0.5pt solid #bbb;
	color: #333;
}

.rev-body blockquote p {
	text-indent: 0;
	margin-bottom: 0.3em;
}

/* ===================================================
   IMAGES & AUDIO
   =================================================== */
.full-width {
	text-align: center;
	margin: 1.2em 0;
}

.image-block img {
	max-width: 90%;
	max-height: 18cm;
}

.image-caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin-top: 0.5em;
	text-align: center;
}

.audio-block {
	border: 0.5pt solid #ccc;
	border-left: 1pt solid #999;
	padding: 0.7em 1em;
	margin: 1.5em 2em;
	background-color: #fafafa;
}

.audio-block__icon {
	font-size: 10pt;
	font-weight: bold;
	color: #555;
	margin: 0 0 0.2em 0;
}

.audio-block__url {
	font-size: 8pt;
	margin: 0;
	word-wrap: break-word;
}

.audio-block__url a {
	color: #333;
	text-decoration: underline;
}

.audio-block__caption {
	font-size: 8.5pt;
	font-style: italic;
	color: #666;
	margin: 0.2em 0 0;
}

/* ===================================================
   FOOTNOTES
   =================================================== */
.rev-footnotes {
	margin-top: 2.5em;
	padding-top: 1em;
	font-size: 8.5pt;
	line-height: 1.4;
	border-top: 0.75pt solid #1a1a1a;
}

.rev-footnotes h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin: 0 0 0.5em 0;
}

.footnote-list {
	list-style: none;
	padding-left: 0;
}

.footnote-list__footnote {
	margin-bottom: 0.25em;
	padding-left: 1.5em;
	text-indent: -1.5em;
}

.footnote-list__footnote-number {
	font-weight: bold;
	margin-right: 0.3em;
}

.footnote-list__backlink {
	text-decoration: none;
	color: #1a1a1a;
}

.footnote-ref {
	font-size: 0.6em;
	vertical-align: super;
	text-decoration: none;
	color: #1a1a1a;
	font-weight: bold;
}

/* ===================================================
   BIBLIOGRAPHY
   =================================================== */
.rev-bibliography {
	margin-top: 2em;
	font-size: 9pt;
	line-height: 1.4;
}

.rev-bibliography h2 {
	font-size: 10pt;
	font-weight: bold;
	letter-spacing: 0.05em;
	text-transform: uppercase;
	margin-bottom: 0.5em;
	border-bottom: 0.5pt solid #ddd;
	padding-bottom: 0.3em;
}

.rev-bibliography p,
.rev-bibliography li {
	margin-bottom: 0.3em;
	padding-left: 2em;
	text-indent: -2em;
}

.rev-bibliography ul {
	list-style: none;
	padding-left: 0;
}

/* ===================================================
   GENERAL
   =================================================== */
a {
	color: #1a1a1a;
	text-decoration: underline;
}

em {
	font-style: italic;
}

strong {
	font-weight: bold;
}

img {
	max-width: 100%;
}
CSS;
}

