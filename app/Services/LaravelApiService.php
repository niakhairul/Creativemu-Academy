<?php

namespace App\Services;

class LaravelApiService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        // Mengambil URL dan API Key dari file .env dengan env() agar lebih aman
        $this->apiUrl = rtrim(env('LARAVEL_API_URL', ''), "'\" ");
        $this->apiKey = trim(env('LARAVEL_API_KEY', ''), "'\" ");
        
        // Pastikan baseURI berakhiran slash agar Guzzle/CI4 tidak menghilangkan segmen terakhir (/api)
        if (!empty($this->apiUrl) && substr($this->apiUrl, -1) !== '/') {
            $this->apiUrl .= '/';
        }
        
        // Memanggil HTTP Client bawaan CI4
        $this->client = \Config\Services::curlrequest([
            'baseURI' => $this->apiUrl,
        ]);
    }

    /**
     * Fungsi utama untuk melakukan request ke API Laravel
     */
    public function request($method, $endpoint, $data = [])
    {
        // Menyiapkan Header wajib dan batasan timeout agar tidak hang/loading lama
        $options = [
            'timeout'         => 3, // Maksimal 3 detik agar page tidak hang jika server luar lambat
            'connect_timeout' => 2, // Maksimal 2 detik untuk koneksi
            'headers'         => [
                'X-API-KEY'    => $this->apiKey,
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'http_errors'     => false // Mencegah error bawaan CI4 agar response 404/422/500 dari Laravel tetap bisa dibaca
        ];

        // Jika ada data yang mau dikirim (biasanya untuk POST)
        if ($method === 'POST' || $method === 'PUT') {
            $options['json'] = $data;
        } 
        // Jika ada parameter query (biasanya untuk GET)
        else if ($method === 'GET' && !empty($data)) {
            $options['query'] = $data;
        }

        try {
            // Mengeksekusi request ke endpoint (misalnya: /classes)
            $response = $this->client->request($method, $endpoint, $options);
            
            // Mengubah hasil JSON dari Laravel menjadi Array PHP
            $decoded = json_decode($response->getBody(), true);
            return is_array($decoded) ? $decoded : [];
        } catch (\Throwable $e) {
            log_message('warning', "LaravelApiService request [{$method} {$endpoint}] failed: " . $e->getMessage());
            return [];
        }
    }
}
