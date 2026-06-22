<?php
declare(strict_types=1);

function generate_product_image_assets(PDO $pdo, string $appUrl = '/exam', ?string $projectRoot = null): void
{
    $projectRoot ??= dirname(__DIR__);
    $outputDir = $projectRoot . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'products';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0777, true);
    }

    $stmt = $pdo->query(
        'SELECT p.slug, p.name, p.brand, p.model, p.interface_type, c.slug AS category_slug, c.name AS category_name
         FROM products p
         JOIN categories c ON c.id = p.category_id
         ORDER BY c.slug, p.name'
    );
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $update = $pdo->prepare('UPDATE products SET image_url = ?, gallery = JSON_ARRAY(?) WHERE slug = ?');

    foreach ($products as $product) {
        $svg = build_product_svg($product);
        $fileName = $product['slug'] . '.svg';
        $path = $outputDir . DIRECTORY_SEPARATOR . $fileName;
        file_put_contents($path, $svg);

        $url = rtrim($appUrl, '/') . '/assets/images/products/' . $fileName;
        $update->execute([$url, $url, $product['slug']]);
    }
}

function build_product_svg(array $product): string
{
    $category = (string) $product['category_slug'];
    $palette = product_palette($category);
    $shape = product_shape_svg($category);
    $name = svg_text((string) $product['name']);
    $brand = svg_text((string) $product['brand']);
    $model = svg_text((string) $product['model']);
    $interface = svg_text((string) $product['interface_type']);
    $categoryName = svg_text((string) $product['category_name']);
    $initials = svg_text(product_initials((string) $product['brand']));

    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="900" height="650" viewBox="0 0 900 650" role="img" aria-label="{$name} product image">
  <defs>
    <linearGradient id="bg" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$palette['bg1']}"/>
      <stop offset="100%" stop-color="{$palette['bg2']}"/>
    </linearGradient>
    <linearGradient id="device" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="{$palette['device1']}"/>
      <stop offset="100%" stop-color="{$palette['device2']}"/>
    </linearGradient>
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="150%">
      <feDropShadow dx="0" dy="22" stdDeviation="24" flood-color="#0f172a" flood-opacity="0.22"/>
    </filter>
  </defs>
  <rect width="900" height="650" rx="44" fill="url(#bg)"/>
  <circle cx="745" cy="95" r="110" fill="#ffffff" opacity="0.16"/>
  <circle cx="90" cy="570" r="150" fill="#ffffff" opacity="0.12"/>
  <rect x="58" y="54" width="172" height="42" rx="21" fill="#ffffff" opacity="0.94"/>
  <text x="86" y="82" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="800" fill="#1e293b">{$categoryName}</text>
  <g transform="translate(542 58)">
    <rect width="270" height="74" rx="24" fill="#ffffff" opacity="0.92"/>
    <circle cx="42" cy="37" r="22" fill="{$palette['device1']}"/>
    <text x="42" y="44" text-anchor="middle" font-family="Inter, Arial, sans-serif" font-size="16" font-weight="900" fill="#ffffff">{$initials}</text>
    <text x="78" y="34" font-family="Inter, Arial, sans-serif" font-size="18" font-weight="900" fill="#0f172a">{$brand}</text>
    <text x="78" y="57" font-family="Inter, Arial, sans-serif" font-size="15" font-weight="700" fill="#64748b">{$model}</text>
  </g>
  <g filter="url(#shadow)">
    {$shape}
  </g>
  <g transform="translate(64 468)">
    <rect width="772" height="120" rx="30" fill="#ffffff" opacity="0.95"/>
    <text x="32" y="48" font-family="Inter, Arial, sans-serif" font-size="32" font-weight="900" fill="#0f172a">{$name}</text>
    <text x="32" y="84" font-family="Inter, Arial, sans-serif" font-size="20" font-weight="700" fill="#64748b">{$brand} {$model} | {$interface}</text>
  </g>
</svg>
SVG;
}

function product_palette(string $category): array
{
    $palettes = [
        'keyboard' => ['#eef2ff', '#cffafe', '#4f46e5', '#06b6d4'],
        'mouse' => ['#f8fafc', '#e0f2fe', '#334155', '#06b6d4'],
        'microphone' => ['#f5f3ff', '#fee2e2', '#7c3aed', '#ef4444'],
        'speaker' => ['#ecfeff', '#f0fdf4', '#0891b2', '#16a34a'],
        'scanner' => ['#f8fafc', '#ede9fe', '#475569', '#8b5cf6'],
        'monitor' => ['#eff6ff', '#e0e7ff', '#2563eb', '#4f46e5'],
        'printer' => ['#f8fafc', '#fef3c7', '#475569', '#f59e0b'],
        'projector' => ['#eef2ff', '#dcfce7', '#4338ca', '#22c55e'],
        'camera' => ['#f1f5f9', '#dbeafe', '#0f172a', '#3b82f6'],
        'joystick' => ['#fff7ed', '#e0f2fe', '#ea580c', '#0284c7'],
        'network-adapter' => ['#ecfdf5', '#e0f2fe', '#059669', '#0891b2'],
        'accessories' => ['#f8fafc', '#fce7f3', '#64748b', '#db2777'],
    ];
    $selected = $palettes[$category] ?? $palettes['accessories'];

    return [
        'bg1' => $selected[0],
        'bg2' => $selected[1],
        'device1' => $selected[2],
        'device2' => $selected[3],
    ];
}

function product_shape_svg(string $category): string
{
    return match ($category) {
        'keyboard' => '<g transform="translate(180 214)"><rect x="0" y="0" width="540" height="180" rx="34" fill="url(#device)"/><g fill="#ffffff" opacity="0.88"><rect x="38" y="42" width="54" height="34" rx="8"/><rect x="108" y="42" width="54" height="34" rx="8"/><rect x="178" y="42" width="54" height="34" rx="8"/><rect x="248" y="42" width="54" height="34" rx="8"/><rect x="318" y="42" width="54" height="34" rx="8"/><rect x="388" y="42" width="54" height="34" rx="8"/><rect x="458" y="42" width="44" height="34" rx="8"/><rect x="38" y="94" width="64" height="34" rx="8"/><rect x="118" y="94" width="64" height="34" rx="8"/><rect x="198" y="94" width="180" height="34" rx="8"/><rect x="394" y="94" width="48" height="34" rx="8"/><rect x="458" y="94" width="44" height="34" rx="8"/></g></g>',
        'mouse' => '<g transform="translate(318 168)"><path d="M132 0c92 0 142 74 142 190 0 108-50 178-142 178S-10 298-10 190C-10 74 40 0 132 0Z" fill="url(#device)"/><path d="M132 12v122" stroke="#fff" stroke-width="10" opacity=".85"/><rect x="116" y="48" width="32" height="70" rx="16" fill="#fff" opacity=".9"/></g>',
        'microphone' => '<g transform="translate(360 128)"><rect x="62" y="0" width="116" height="230" rx="58" fill="url(#device)"/><rect x="88" y="34" width="64" height="128" rx="32" fill="#fff" opacity=".2"/><path d="M32 150c0 92 176 92 176 0" fill="none" stroke="#0f172a" stroke-width="22" opacity=".2"/><path d="M120 242v82M62 324h116" stroke="url(#device)" stroke-width="24" stroke-linecap="round"/></g>',
        'speaker' => '<g transform="translate(230 170)"><rect x="0" y="0" width="170" height="250" rx="32" fill="url(#device)"/><rect x="300" y="0" width="170" height="250" rx="32" fill="url(#device)"/><circle cx="85" cy="82" r="38" fill="#fff" opacity=".28"/><circle cx="85" cy="174" r="52" fill="#fff" opacity=".42"/><circle cx="385" cy="82" r="38" fill="#fff" opacity=".28"/><circle cx="385" cy="174" r="52" fill="#fff" opacity=".42"/></g>',
        'scanner' => '<g transform="translate(190 206)"><rect x="0" y="95" width="520" height="145" rx="30" fill="url(#device)"/><path d="M82 0h356l68 110H14L82 0Z" fill="#fff" opacity=".9"/><path d="M110 34h300l34 54H76l34-54Z" fill="url(#device)" opacity=".72"/><rect x="60" y="146" width="400" height="28" rx="14" fill="#fff" opacity=".36"/></g>',
        'monitor' => '<g transform="translate(190 136)"><rect x="0" y="0" width="520" height="300" rx="34" fill="#0f172a"/><rect x="28" y="28" width="464" height="226" rx="20" fill="url(#device)"/><path d="M232 300h56v60h90v34H142v-34h90v-60Z" fill="#334155"/></g>',
        'printer' => '<g transform="translate(212 160)"><rect x="40" y="82" width="430" height="230" rx="34" fill="url(#device)"/><rect x="96" y="0" width="318" height="128" rx="18" fill="#fff" opacity=".92"/><rect x="94" y="204" width="322" height="112" rx="18" fill="#fff" opacity=".92"/><rect x="122" y="226" width="266" height="16" rx="8" fill="#94a3b8"/><rect x="122" y="258" width="220" height="16" rx="8" fill="#94a3b8"/></g>',
        'projector' => '<g transform="translate(190 202)"><rect x="0" y="0" width="430" height="190" rx="38" fill="url(#device)"/><circle cx="318" cy="95" r="62" fill="#fff" opacity=".9"/><circle cx="318" cy="95" r="36" fill="#0f172a" opacity=".32"/><rect x="52" y="52" width="150" height="28" rx="14" fill="#fff" opacity=".32"/><rect x="52" y="104" width="104" height="24" rx="12" fill="#fff" opacity=".24"/><path d="M464 78l190-64v162L464 112Z" fill="#fff" opacity=".42"/></g>',
        'camera' => '<g transform="translate(204 174)"><rect x="0" y="70" width="492" height="250" rx="48" fill="url(#device)"/><rect x="70" y="0" width="160" height="92" rx="26" fill="url(#device)"/><circle cx="276" cy="194" r="84" fill="#fff" opacity=".95"/><circle cx="276" cy="194" r="52" fill="#0f172a" opacity=".38"/><circle cx="82" cy="130" r="24" fill="#fff" opacity=".42"/></g>',
        'joystick' => '<g transform="translate(242 132)"><rect x="92" y="210" width="306" height="142" rx="58" fill="url(#device)"/><path d="M244 48c58 0 98 42 98 100s-40 100-98 100-98-42-98-100 40-100 98-100Z" fill="url(#device)"/><rect x="214" y="0" width="60" height="124" rx="30" fill="#0f172a" opacity=".78"/><circle cx="170" cy="280" r="24" fill="#fff" opacity=".86"/><circle cx="314" cy="280" r="24" fill="#fff" opacity=".86"/></g>',
        'network-adapter' => '<g transform="translate(188 176)"><rect x="0" y="82" width="270" height="170" rx="34" fill="url(#device)"/><rect x="270" y="124" width="190" height="86" rx="18" fill="#0f172a" opacity=".72"/><path d="M496 66c64 48 64 202 0 250M540 20c98 76 98 314 0 390" fill="none" stroke="url(#device)" stroke-width="28" stroke-linecap="round" opacity=".65"/><rect x="58" y="128" width="136" height="32" rx="16" fill="#fff" opacity=".36"/></g>',
        default => '<g transform="translate(236 162)"><rect x="0" y="118" width="430" height="210" rx="44" fill="url(#device)"/><rect x="70" y="0" width="290" height="168" rx="36" fill="#fff" opacity=".9"/><rect x="110" y="38" width="210" height="28" rx="14" fill="url(#device)" opacity=".7"/><rect x="110" y="90" width="150" height="28" rx="14" fill="url(#device)" opacity=".48"/></g>',
    };
}

function product_initials(string $brand): string
{
    $words = preg_split('/\s+/', trim($brand)) ?: [];
    $letters = '';
    foreach ($words as $word) {
        if ($word !== '') {
            $letters .= strtoupper(substr($word, 0, 1));
        }
        if (strlen($letters) >= 2) {
            break;
        }
    }

    return $letters !== '' ? $letters : 'P';
}

function svg_text(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    require_once __DIR__ . '/../config/database.php';
    generate_product_image_assets(Database::connection());
    echo "Product image assets generated.\n";
}
