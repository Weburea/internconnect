<?php

declare(strict_types=1);

namespace App\Services;

/**
 * UniversityService
 * 
 * Provides worldwide university search with caching, resilient timeout handling,
 * and an extensive local fallback dataset for offline or high-latency environments.
 */
class UniversityService
{
    private const API_BASE_URL = 'http://universities.hipolabs.com/search';
    private const CACHE_TTL_SECONDS = 86400; // 24 hours
    private const HTTP_TIMEOUT_SECONDS = 0.8;

    /**
     * Search universities by name or query string.
     * Instant local matching first, with cached external fallback.
     *
     * @param string $query
     * @param int $limit
     * @return array
     */
    public static function search(string $query, int $limit = 20): array
    {
        $cleanQuery = trim($query);
        if (strlen($cleanQuery) < 2) {
            return [];
        }

        // 1. Fast-path: Check local comprehensive dataset immediately (< 1ms response)
        $localMatches = self::filterFallbackDataset($cleanQuery);
        if (!empty($localMatches)) {
            return array_slice($localMatches, 0, $limit);
        }

        // 2. Check local file cache for previous external queries
        $cacheFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ic_univ_' . md5(strtolower($cleanQuery)) . '.json';
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < self::CACHE_TTL_SECONDS)) {
            $cachedContent = file_get_contents($cacheFile);
            if ($cachedContent !== false) {
                $decoded = json_decode($cachedContent, true);
                if (is_array($decoded) && !empty($decoded)) {
                    return array_slice($decoded, 0, $limit);
                }
            }
        }

        // 3. Query external Hipolabs Universities API with quick timeout
        $results = self::fetchFromExternalApi($cleanQuery);

        // 4. Cache valid results
        if (!empty($results)) {
            @file_put_contents($cacheFile, json_encode($results, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
            return array_slice($results, 0, $limit);
        }

        return [];
    }

    /**
     * Fetch from Hipolabs API with strict timeout
     */
    private static function fetchFromExternalApi(string $query): array
    {
        $url = self::API_BASE_URL . '?name=' . urlencode($query);
        
        $context = stream_context_create([
            'http' => [
                'method'          => 'GET',
                'timeout'         => self::HTTP_TIMEOUT_SECONDS,
                'ignore_errors'   => true,
                'header'          => "User-Agent: InternConnect/1.0\r\nAccept: application/json\r\n",
            ],
            'ssl' => [
                'verify_peer'     => false,
                'verify_peer_name'=> false,
            ]
        ]);

        try {
            $response = @file_get_contents($url, false, $context);
            if ($response !== false) {
                $data = json_decode($response, true);
                if (is_array($data) && !empty($data)) {
                    $formatted = [];
                    foreach ($data as $item) {
                        if (isset($item['name'])) {
                            $formatted[] = [
                                'name'         => (string)$item['name'],
                                'country'      => (string)($item['country'] ?? ''),
                                'state'        => (string)($item['state-province'] ?? ''),
                                'domains'      => (array)($item['domains'] ?? []),
                                'web_pages'    => (array)($item['web_pages'] ?? []),
                            ];
                        }
                    }
                    return $formatted;
                }
            }
        } catch (\Throwable $e) {
            // Quietly proceed
        }

        return [];
    }

    /**
     * Search within comprehensive local dataset (matching name, state, country, domain, and aliases)
     */
    private static function filterFallbackDataset(string $query): array
    {
        $dataset = self::getLocalFallbackDataset();
        $matches = [];
        $lowerQuery = strtolower($query);

        // Acronym mappings to full names for instant recognition
        $acronyms = [
            'unilag'   => 'University of Lagos',
            'lasu'     => 'Lagos State University',
            'pau'      => 'Pan-Atlantic University',
            'cu'       => 'Covenant University',
            'babcock'  => 'Babcock University',
            'oau'      => 'Obafemi Awolowo University',
            'ui'       => 'University of Ibadan',
            'abu'      => 'Ahmadu Bello University',
            'unn'      => 'University of Nigeria, Nsukka',
            'uniben'   => 'University of Benin',
            'futa'     => 'Federal University of Technology, Akure',
            'futminna' => 'Federal University of Technology, Minna',
            'futo'     => 'Federal University of Technology, Owerri',
            'unilorin' => 'University of Ilorin',
            'uniport'  => 'University of Port Harcourt',
            'rsu'      => 'Rivers State University',
            'uniabuja' => 'University of Abuja',
            'nile'     => 'Nile University of Nigeria',
            'baze'     => 'Baze University',
            'aun'      => 'American University of Nigeria',
            'buk'      => 'Bayero University Kano',
            'unijos'   => 'University of Jos',
            'unical'   => 'University of Calabar',
            'uniuyo'   => 'University of Uyo',
            'funaab'   => 'Federal University of Agriculture, Abeokuta',
            'run'      => 'Redeemer\'s University',
            'abuad'    => 'Afe Babalola University',
            'eksu'     => 'Ekiti State University',
            'uniosun'  => 'Osun State University',
            'oou'      => 'Olabisi Onabanjo University',
            'unizik'   => 'Nnamdi Azikiwe University',
            'lautech'  => 'Ladoke Akintola University of Technology',
            'yabatech' => 'Yaba College of Technology',
            'mit'      => 'Massachusetts Institute of Technology',
            'ucl'      => 'University College London',
            'lse'      => 'London School of Economics',
            'kcl'      => 'King\'s College London',
            'uct'      => 'University of Cape Town',
            'wits'     => 'University of the Witwatersrand',
            'knust'    => 'Kwame Nkrumah University of Science and Technology',
            'nus'      => 'National University of Singapore',
            'ntu'      => 'Nanyang Technological University',
        ];

        $targetAliasName = $acronyms[$lowerQuery] ?? null;

        foreach ($dataset as $univ) {
            $name = strtolower($univ['name']);
            $country = strtolower($univ['country'] ?? '');
            $state = strtolower($univ['state'] ?? '');
            $domain = !empty($univ['domains'][0]) ? strtolower($univ['domains'][0]) : '';

            if (
                ($targetAliasName && strcasecmp($univ['name'], $targetAliasName) === 0) ||
                strpos($name, $lowerQuery) !== false ||
                strpos($domain, $lowerQuery) !== false ||
                strpos($country, $lowerQuery) !== false ||
                strpos($state, $lowerQuery) !== false
            ) {
                $matches[] = $univ;
            }
        }

        return $matches;
    }

    /**
     * Comprehensive local fallback dataset
     */
    public static function getLocalFallbackDataset(): array
    {
        return [
            // Nigeria - Federal & State & Leading Private Universities
            ['name' => 'University of Lagos', 'country' => 'Nigeria', 'state' => 'Lagos', 'domains' => ['unilag.edu.ng']],
            ['name' => 'Lagos State University', 'country' => 'Nigeria', 'state' => 'Lagos', 'domains' => ['lasu.edu.ng']],
            ['name' => 'Pan-Atlantic University', 'country' => 'Nigeria', 'state' => 'Lagos', 'domains' => ['pau.edu.ng']],
            ['name' => 'Covenant University', 'country' => 'Nigeria', 'state' => 'Ogun', 'domains' => ['covenantuniversity.edu.ng']],
            ['name' => 'Babcock University', 'country' => 'Nigeria', 'state' => 'Ogun', 'domains' => ['babcock.edu.ng']],
            ['name' => 'Obafemi Awolowo University', 'country' => 'Nigeria', 'state' => 'Osun', 'domains' => ['oauife.edu.ng']],
            ['name' => 'University of Ibadan', 'country' => 'Nigeria', 'state' => 'Oyo', 'domains' => ['ui.edu.ng']],
            ['name' => 'Ahmadu Bello University', 'country' => 'Nigeria', 'state' => 'Kaduna', 'domains' => ['abu.edu.ng']],
            ['name' => 'University of Nigeria, Nsukka', 'country' => 'Nigeria', 'state' => 'Enugu', 'domains' => ['unn.edu.ng']],
            ['name' => 'University of Benin', 'country' => 'Nigeria', 'state' => 'Edo', 'domains' => ['uniben.edu.ng']],
            ['name' => 'Federal University of Technology, Akure', 'country' => 'Nigeria', 'state' => 'Ondo', 'domains' => ['futa.edu.ng']],
            ['name' => 'Federal University of Technology, Minna', 'country' => 'Nigeria', 'state' => 'Niger', 'domains' => ['futminna.edu.ng']],
            ['name' => 'Federal University of Technology, Owerri', 'country' => 'Nigeria', 'state' => 'Imo', 'domains' => ['futo.edu.ng']],
            ['name' => 'University of Ilorin', 'country' => 'Nigeria', 'state' => 'Kwara', 'domains' => ['unilorin.edu.ng']],
            ['name' => 'Landmark University', 'country' => 'Nigeria', 'state' => 'Kwara', 'domains' => ['lmu.edu.ng']],
            ['name' => 'University of Port Harcourt', 'country' => 'Nigeria', 'state' => 'Rivers', 'domains' => ['uniport.edu.ng']],
            ['name' => 'Rivers State University', 'country' => 'Nigeria', 'state' => 'Rivers', 'domains' => ['rsu.edu.ng']],
            ['name' => 'University of Abuja', 'country' => 'Nigeria', 'state' => 'Abuja FCT', 'domains' => ['uniabuja.edu.ng']],
            ['name' => 'Nile University of Nigeria', 'country' => 'Nigeria', 'state' => 'Abuja FCT', 'domains' => ['nileuniversity.edu.ng']],
            ['name' => 'Baze University', 'country' => 'Nigeria', 'state' => 'Abuja FCT', 'domains' => ['bazeuniversity.edu.ng']],
            ['name' => 'American University of Nigeria', 'country' => 'Nigeria', 'state' => 'Adamawa', 'domains' => ['aun.edu.ng']],
            ['name' => 'Bayero University Kano', 'country' => 'Nigeria', 'state' => 'Kano', 'domains' => ['buk.edu.ng']],
            ['name' => 'University of Jos', 'country' => 'Nigeria', 'state' => 'Plateau', 'domains' => ['unijos.edu.ng']],
            ['name' => 'University of Calabar', 'country' => 'Nigeria', 'state' => 'Cross River', 'domains' => ['unical.edu.ng']],
            ['name' => 'University of Uyo', 'country' => 'Nigeria', 'state' => 'Akwa Ibom', 'domains' => ['uniuyo.edu.ng']],
            ['name' => 'Federal University of Agriculture, Abeokuta', 'country' => 'Nigeria', 'state' => 'Ogun', 'domains' => ['funaab.edu.ng']],
            ['name' => 'Redeemer\'s University', 'country' => 'Nigeria', 'state' => 'Osun', 'domains' => ['run.edu.ng']],
            ['name' => 'Bowen University', 'country' => 'Nigeria', 'state' => 'Osun', 'domains' => ['bowen.edu.ng']],
            ['name' => 'Afe Babalola University', 'country' => 'Nigeria', 'state' => 'Ekiti', 'domains' => ['abuad.edu.ng']],
            ['name' => 'Ekiti State University', 'country' => 'Nigeria', 'state' => 'Ekiti', 'domains' => ['eksu.edu.ng']],
            ['name' => 'Osun State University', 'country' => 'Nigeria', 'state' => 'Osun', 'domains' => ['uniosun.edu.ng']],
            ['name' => 'Olabisi Onabanjo University', 'country' => 'Nigeria', 'state' => 'Ogun', 'domains' => ['oouagoiwoye.edu.ng']],
            ['name' => 'Nnamdi Azikiwe University', 'country' => 'Nigeria', 'state' => 'Anambra', 'domains' => ['unizik.edu.ng']],
            ['name' => 'Ladoke Akintola University of Technology', 'country' => 'Nigeria', 'state' => 'Oyo', 'domains' => ['lautech.edu.ng']],
            ['name' => 'Lead City University', 'country' => 'Nigeria', 'state' => 'Oyo', 'domains' => ['lcu.edu.ng']],
            ['name' => 'Yaba College of Technology', 'country' => 'Nigeria', 'state' => 'Lagos', 'domains' => ['yabatech.edu.ng']],

            // Africa - Top Universities
            ['name' => 'University of Cape Town', 'country' => 'South Africa', 'state' => 'Western Cape', 'domains' => ['uct.ac.za']],
            ['name' => 'University of the Witwatersrand', 'country' => 'South Africa', 'state' => 'Gauteng', 'domains' => ['wits.ac.za']],
            ['name' => 'Stellenbosch University', 'country' => 'South Africa', 'state' => 'Western Cape', 'domains' => ['sun.ac.za']],
            ['name' => 'University of Ghana', 'country' => 'Ghana', 'state' => 'Accra', 'domains' => ['ug.edu.gh']],
            ['name' => 'Kwame Nkrumah University of Science and Technology', 'country' => 'Ghana', 'state' => 'Kumasi', 'domains' => ['knust.edu.gh']],
            ['name' => 'Ashesi University', 'country' => 'Ghana', 'state' => 'Berekuso', 'domains' => ['ashesi.edu.gh']],
            ['name' => 'University of Nairobi', 'country' => 'Kenya', 'state' => 'Nairobi', 'domains' => ['uonbi.ac.ke']],
            ['name' => 'Strathmore University', 'country' => 'Kenya', 'state' => 'Nairobi', 'domains' => ['strathmore.edu']],
            ['name' => 'Makerere University', 'country' => 'Uganda', 'state' => 'Kampala', 'domains' => ['mak.ac.ug']],
            ['name' => 'Cairo University', 'country' => 'Egypt', 'state' => 'Giza', 'domains' => ['cu.edu.eg']],
            ['name' => 'American University in Cairo', 'country' => 'Egypt', 'state' => 'Cairo', 'domains' => ['aucegypt.edu']],

            // United Kingdom
            ['name' => 'University of Oxford', 'country' => 'United Kingdom', 'state' => 'Oxfordshire', 'domains' => ['ox.ac.uk']],
            ['name' => 'University of Cambridge', 'country' => 'United Kingdom', 'state' => 'Cambridgeshire', 'domains' => ['cam.ac.uk']],
            ['name' => 'Imperial College London', 'country' => 'United Kingdom', 'state' => 'London', 'domains' => ['imperial.ac.uk']],
            ['name' => 'University College London', 'country' => 'United Kingdom', 'state' => 'London', 'domains' => ['ucl.ac.uk']],
            ['name' => 'London School of Economics', 'country' => 'United Kingdom', 'state' => 'London', 'domains' => ['lse.ac.uk']],
            ['name' => 'University of Edinburgh', 'country' => 'United Kingdom', 'state' => 'Scotland', 'domains' => ['ed.ac.uk']],
            ['name' => 'University of Manchester', 'country' => 'United Kingdom', 'state' => 'Manchester', 'domains' => ['manchester.ac.uk']],
            ['name' => 'King\'s College London', 'country' => 'United Kingdom', 'state' => 'London', 'domains' => ['kcl.ac.uk']],
            ['name' => 'University of Warwick', 'country' => 'United Kingdom', 'state' => 'Coventry', 'domains' => ['warwick.ac.uk']],
            ['name' => 'University of Bristol', 'country' => 'United Kingdom', 'state' => 'Bristol', 'domains' => ['bristol.ac.uk']],

            // United States & Canada
            ['name' => 'Harvard University', 'country' => 'United States', 'state' => 'Massachusetts', 'domains' => ['harvard.edu']],
            ['name' => 'Massachusetts Institute of Technology', 'country' => 'United States', 'state' => 'Massachusetts', 'domains' => ['mit.edu']],
            ['name' => 'Stanford University', 'country' => 'United States', 'state' => 'California', 'domains' => ['stanford.edu']],
            ['name' => 'University of California, Berkeley', 'country' => 'United States', 'state' => 'California', 'domains' => ['berkeley.edu']],
            ['name' => 'Columbia University', 'country' => 'United States', 'state' => 'New York', 'domains' => ['columbia.edu']],
            ['name' => 'Princeton University', 'country' => 'United States', 'state' => 'New Jersey', 'domains' => ['princeton.edu']],
            ['name' => 'Yale University', 'country' => 'United States', 'state' => 'Connecticut', 'domains' => ['yale.edu']],
            ['name' => 'Cornell University', 'country' => 'United States', 'state' => 'New York', 'domains' => ['cornell.edu']],
            ['name' => 'University of Toronto', 'country' => 'Canada', 'state' => 'Ontario', 'domains' => ['utoronto.ca']],
            ['name' => 'McGill University', 'country' => 'Canada', 'state' => 'Quebec', 'domains' => ['mcgill.ca']],
            ['name' => 'University of British Columbia', 'country' => 'Canada', 'state' => 'British Columbia', 'domains' => ['ubc.ca']],
            ['name' => 'University of Waterloo', 'country' => 'Canada', 'state' => 'Ontario', 'domains' => ['uwaterloo.ca']],

            // Europe & Asia & Australia
            ['name' => 'ETH Zurich', 'country' => 'Switzerland', 'state' => 'Zurich', 'domains' => ['ethz.ch']],
            ['name' => 'Technical University of Munich', 'country' => 'Germany', 'state' => 'Bavaria', 'domains' => ['tum.de']],
            ['name' => 'National University of Singapore', 'country' => 'Singapore', 'state' => 'Singapore', 'domains' => ['nus.edu.sg']],
            ['name' => 'Nanyang Technological University', 'country' => 'Singapore', 'state' => 'Singapore', 'domains' => ['ntu.edu.sg']],
            ['name' => 'University of Melbourne', 'country' => 'Australia', 'state' => 'Victoria', 'domains' => ['unimelb.edu.au']],
            ['name' => 'University of Sydney', 'country' => 'Australia', 'state' => 'New South Wales', 'domains' => ['sydney.edu.au']],
        ];
    }
}
