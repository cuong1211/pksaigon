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
        $this->siteName = 'Phòng Khám Phụ Sản Thu Hiền';
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
        return $this->title ? $this->title . ' - ' . $this->siteName : $this->siteName . ' - Chăm sóc sức khỏe phụ nữ chuyên nghiệp';
    }

    public function getDescription()
    {
        return $this->description ?: 'Phòng Khám Phụ Sản Thu Hiền - Chuyên khoa sản phụ khoa với đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại tại Quận 5, TP.HCM. Khám thai, điều trị phụ khoa, tư vấn sức khỏe sinh sản.';
    }

    public function getKeywords()
    {
        return $this->keywords ?: 'phòng khám phụ sản thu hiền, phụ khoa sài gòn, khám thai, điều trị phụ khoa, bác sĩ phụ sản, sức khỏe phụ nữ, khám phụ khoa quận 5, phòng khám chuyên khoa';
    }

    public function getImage()
    {
        return $this->image ?: asset('frontend/images/favicon.jpg');
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
        $html .= '<meta name="description" content="' . htmlspecialchars($this->getDescription()) . '">' . "\n";
        $html .= '<meta name="keywords" content="' . htmlspecialchars($this->getKeywords()) . '">' . "\n";
        $html .= '<link rel="canonical" href="' . $this->getCanonicalUrl() . '">' . "\n";

        // Open Graph tags
        $html .= '<meta property="og:title" content="' . htmlspecialchars($this->getTitle()) . '">' . "\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($this->getDescription()) . '">' . "\n";
        $html .= '<meta property="og:image" content="' . $this->getImage() . '">' . "\n";
        $html .= '<meta property="og:url" content="' . $this->getCanonicalUrl() . '">' . "\n";
        $html .= '<meta property="og:type" content="' . $this->type . '">' . "\n";
        $html .= '<meta property="og:site_name" content="' . $this->siteName . '">' . "\n";
        $html .= '<meta property="og:locale" content="vi_VN">' . "\n";

        // Twitter Card tags
        $html .= '<meta name="twitter:card" content="summary_large_image">' . "\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($this->getTitle()) . '">' . "\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars($this->getDescription()) . '">' . "\n";
        $html .= '<meta name="twitter:image" content="' . $this->getImage() . '">' . "\n";

        // Thêm meta tags cho địa phương và y tế
        $html .= '<meta name="geo.region" content="VN-SG">' . "\n";
        $html .= '<meta name="geo.placename" content="Hồ Chí Minh">' . "\n";
        $html .= '<meta name="geo.position" content="10.762622;106.660172">' . "\n";
        $html .= '<meta name="ICBM" content="10.762622, 106.660172">' . "\n";

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
                    'name' => 'Phòng Khám Phụ Sản Thu Hiền',
                    'alternateName' => 'Thu Hiền Clinic',
                    'url' => url('/'),
                    'logo' => asset('frontend/images/favicon.jpg'),
                    'image' => asset('frontend/images/favicon.jpg'),
                    'description' => 'Phòng khám chuyên khoa sản phụ khoa với đội ngũ bác sĩ giàu kinh nghiệm, trang thiết bị hiện đại tại Quận 5, TP.HCM',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'streetAddress' => '65 Hùng Vương, Phường 4',
                        'addressLocality' => 'Quận 5',
                        'addressRegion' => 'TP. Hồ Chí Minh',
                        'postalCode' => '700000',
                        'addressCountry' => 'VN'
                    ],
                    'geo' => [
                        '@type' => 'GeoCoordinates',
                        'latitude' => '10.762622',
                        'longitude' => '106.660172'
                    ],
                    'telephone' => ['+84384518881', '+84988669292'],
                    'email' => 'info@phongkhamthuhien.com',
                    'openingHours' => [
                        'Mo-Su 07:00-19:00'
                    ],
                    'openingHoursSpecification' => [
                        [
                            '@type' => 'OpeningHoursSpecification',
                            'dayOfWeek' => [
                                'Monday',
                                'Tuesday',
                                'Wednesday',
                                'Thursday',
                                'Friday',
                                'Saturday',
                                'Sunday'
                            ],
                            'opens' => '07:00',
                            'closes' => '19:00'
                        ]
                    ],
                    'medicalSpecialty' => [
                        'Obstetrics and Gynecology',
                        'Reproductive Health',
                        'Women\'s Health'
                    ],
                    'serviceType' => [
                        'Khám thai định kỳ',
                        'Siêu âm thai',
                        'Điều trị viêm nhiễm phụ khoa',
                        'Tư vấn kế hoạch hóa gia đình',
                        'Khám sức khỏe sinh sản'
                    ],
                    'paymentAccepted' => ['Cash', 'Credit Card', 'Bank Transfer'],
                    'currenciesAccepted' => 'VND',
                    'priceRange' => '100000-2000000',
                    'areaServed' => [
                        'Quận 5',
                        'Quận 1',
                        'Quận 3',
                        'Quận 4',
                        'Quận 6',
                        'Quận 8',
                        'Quận 10',
                        'Quận 11',
                        'TP. Hồ Chí Minh'
                    ]
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
                        'name' => 'Phòng Khám Phụ Sản Thu Hiền',
                        'url' => url('/')
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => 'Phòng Khám Phụ Sản Thu Hiền',
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => asset('frontend/images/favicon.jpg'),
                            'width' => 400,
                            'height' => 400
                        ]
                    ],
                    'datePublished' => $data['published_at'] ?? now()->toISOString(),
                    'dateModified' => $data['updated_at'] ?? now()->toISOString(),
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => $this->getCanonicalUrl()
                    ],
                    'articleSection' => 'Sức khỏe phụ nữ',
                    'keywords' => $this->getKeywords()
                ];
                break;

            case 'Service':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'MedicalProcedure',
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? '',
                    'image' => $data['image'] ?? $this->getImage(),
                    'provider' => [
                        '@type' => 'MedicalClinic',
                        'name' => 'Phòng Khám Phụ Sản Thu Hiền',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => '65 Hùng Vương, Phường 4',
                            'addressLocality' => 'Quận 5',
                            'addressRegion' => 'TP. Hồ Chí Minh',
                            'addressCountry' => 'VN'
                        ]
                    ],
                    'bodyLocation' => 'Reproductive system',
                    'preparation' => 'Tư vấn với bác sĩ trước khi thực hiện',
                    'followup' => 'Theo dõi định kỳ theo chỉ định của bác sĩ'
                ];

                if (isset($data['price']) && $data['price'] > 0) {
                    $schema['offers'] = [
                        '@type' => 'Offer',
                        'price' => $data['price'],
                        'priceCurrency' => 'VND',
                        'availability' => 'https://schema.org/InStock'
                    ];
                }
                break;

            case 'Product':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => $data['name'] ?? '',
                    'description' => $data['description'] ?? '',
                    'image' => $data['image'] ?? $this->getImage(),
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => $data['brand'] ?? 'Thu Hiền'
                    ],
                    'category' => 'Thực phẩm chức năng',
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => $data['price'] ?? 0,
                        'priceCurrency' => 'VND',
                        'availability' => 'https://schema.org/InStock',
                        'seller' => [
                            '@type' => 'Organization',
                            'name' => 'Phòng Khám Phụ Sản Thu Hiền'
                        ]
                    ]
                ];
                break;

            case 'FAQ':
                $schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => $data['questions'] ?? []
                ];
                break;
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Tạo breadcrumb schema
     */
    public function generateBreadcrumbSchema($items)
    {
        $listItems = [];
        foreach ($items as $index => $item) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url']
            ];
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_UNICODE) . '</script>';
    }
}
