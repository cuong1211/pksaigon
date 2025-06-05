<?php

namespace App\Services;

class SEOHelper
{
    private $title;
    private $description;
    private $keywords;
    private $image;
    private $url;
    private $type = 'website';
    private $siteName;

    public function __construct()
    {
        $this->siteName = config('app.name', 'Phòng Khám Sài Gòn');
        $this->url = request()->url();
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setKeywords($keywords)
    {
        $this->keywords = is_array($keywords) ? implode(', ', $keywords) : $keywords;
        return $this;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getTitle()
    {
        return $this->title ? $this->title . ' - ' . $this->siteName : $this->siteName;
    }

    public function getDescription()
    {
        return $this->description ?: 'Phòng Khám Sài Gòn - Chăm sóc sức khỏe chuyên nghiệp';
    }

    public function getKeywords()
    {
        return $this->keywords ?: 'phòng khám, sức khỏe, bác sĩ, khám bệnh, sài gòn';
    }

    public function getImage()
    {
        return $this->image ?: asset('frontend/images/logo.png');
    }

    public function getCanonicalUrl()
    {
        return $this->url;
    }

    public function renderMeta()
    {
        $html = '';

        // Basic meta tags
        $html .= '<title>' . $this->getTitle() . '</title>' . "\n";
        $html .= '<meta name="description" content="' . $this->getDescription() . '">' . "\n";
        $html .= '<meta name="keywords" content="' . $this->getKeywords() . '">' . "\n";
        $html .= '<link rel="canonical" href="' . $this->getCanonicalUrl() . '">' . "\n";

        // Open Graph tags
        $html .= '<meta property="og:title" content="' . $this->getTitle() . '">' . "\n";
        $html .= '<meta property="og:description" content="' . $this->getDescription() . '">' . "\n";
        $html .= '<meta property="og:image" content="' . $this->getImage() . '">' . "\n";
        $html .= '<meta property="og:url" content="' . $this->getCanonicalUrl() . '">' . "\n";
        $html .= '<meta property="og:type" content="' . $this->type . '">' . "\n";
        $html .= '<meta property="og:site_name" content="' . $this->siteName . '">' . "\n";

        // Twitter Card tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . $this->getTitle() . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . $this->getDescription() . '">' . "\n";
        $html .= '<meta name="twitter:image" content="' . $this->getImage() . '">' . "\n";

        return $html;
    }

    public function generateSchema($type = 'Organization', $data = [])
    {
        $schema = [];

        switch ($type) {
            case 'Organization':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'MedicalClinic',
                    'name' => 'Phòng Khám Sài Gòn',
                    'url' => url('/'),
                    'logo' => asset('frontend/images/logo.png'),
                    'description' => 'Phòng khám chuyên khoa sản phụ khoa tại TP.HCM',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '65 Hùng Vương, Phường 4',
                        'addressLocality' => 'Quận 5',
                        'addressRegion' => 'TP. Hồ Chí Minh',
                        'addressCountry' => 'VN'
                    ],
                    'telephone' => '0384518881,0988669292',
                    'openingHours' => 'Mo-Su 08:00-17:00',
                    'medicalSpecialty' => 'Obstetrics and Gynecology'
                ];
                break;

            case 'Article':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'headline' => $data['title'] ?? $this->getTitle(),
                    'description' => $data['description'] ?? $this->getDescription(),
                    'image' => $data['image'] ?? $this->getImage(),
                    'author' => [
                        '@type' => 'Organization',
                        'name' => 'Phòng Khám Sài Gòn'
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'Phòng Khám Sài Gòn',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('frontend/images/logo.png')
                        ]
                    ],
                    'datePublished' => $data['published_at'] ?? now()->toISOString(),
                    'dateModified' => $data['updated_at'] ?? now()->toISOString()
                ];
                break;

            case 'Service':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'MedicalProcedure',
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? '',
                    'provider' => [
                        '@type' => 'MedicalClinic',
                        'name' => 'Phòng Khám Sài Gòn'
                    ]
                ];
                break;
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
