<?php
// JobTeaserImporter.php

class JobTeaserImporter
{
    private PDO $db;
    private string $file;

    public function __construct(string $host, string $username, string $password, string $databaseName, string $file)
    {
        $this->file = $file;
        
        /* connect to DB */
        try {
            $this->db = new PDO('mysql:host=' . $host . ';dbname=' . $databaseName, $username, $password);
        } catch (Exception $e) {
            die('DB error: ' . $e->getMessage() . "\n");
        }
    }

    public function importJobs(): int
    {
        $json = file_get_contents($this->file);
        $data = json_decode($json, true);

        if (!$data) {
            die('Error parsing JSON file.');
        }

        $offers = $data['offers'];
        $count = 0;

        foreach ($offers as $offer) {
            $stmt = $this->db->prepare('INSERT INTO job (reference, title, description, url, company_name, publication) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([
                $offer['reference'],
                $offer['title'],
                $offer['description'],
                $data['offerUrlPrefix'] . $offer['urlPath'],
                $offer['companyname'],
                date('Y-m-d H:i:s', strtotime($offer['publishedDate']))
            ]);

            $count++;
        }

        return $count;
    }
}
?>