<?php
namespace App\Console\Commands;

use App\Models\Advertiser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchAndStoreLogo extends Command
{
    protected $signature = "app-fetch-and-store-logo";
    protected $description = "Fetch and store logo for an advertiser";

    public function handle()
    {
        $advertisers = Advertiser::where("is_fetchable_logo", 0)
        ->orderByDesc("created_at")
        ->take(100)
        ->get();


    foreach ($advertisers as $advertiser) {
        $logoUrl = $advertiser->fetch_logo_url;

        if (empty($logoUrl)) {
            $this->error("Advertiser ID {$advertiser->id} has no logo URL. Skipping.");
            $advertiser->update([
                "is_fetchable_logo" => 3,
                "fetch_logo_error" => "Logo URL is missing."
            ]);
            continue;
        }

        try {
            $sanitizedFileName = $this->sanitizeFileName($advertiser->name, $advertiser->url);
            $fileExists = false;
            $extensions = ["jpg", "jpeg", "png", "gif", "svg", "webp", "bmp", "tiff"];

            foreach ($extensions as $extension) {
                if (Storage::exists("public/logos/{$sanitizedFileName}.{$extension}")) {
                    $fileExists = true;
                    break;
                }
            }

            if ($fileExists) {
                $this->info("Logo already exists for Advertiser ID {$advertiser->id}, skipping fetching.");
                $advertiser->update([
                    "is_fetchable_logo" => 1,
                    "logo" => "logos/{$sanitizedFileName}.{$extension}",
                    "fetch_logo_error" => null
                ]);
                continue;
            }

            $response = Http::timeout(10)->get($logoUrl);

            if ($response->successful()) {
                $imageData = $response->body();
                $extension = $this->getExtensionFromImageData($imageData);

                if (!$extension) {
                    throw new \Exception("Unsupported image type.");
                }

                $fileName = "public/logos/{$sanitizedFileName}.{$extension}";
                Storage::put($fileName, $imageData);

                $advertiser->update([
                    "is_fetchable_logo" => 1,
                    "logo" => str_replace("public/", '', $fileName),
                    "fetch_logo_error" => null
                ]);

                $this->info("Logo stored successfully for Advertiser ID {$advertiser->id}.");
            } else {
                throw new \Exception("Failed to fetch logo from URL.");
            }
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "timed out")) {
                $this->error("Connection timed out while fetching logo for Advertiser ID {$advertiser->id}. Error: " . $e->getMessage());
            } else {
                $advertiser->update([
                    "is_fetchable_logo" => 3,
                    "fetch_logo_error" => $e->getMessage()
                ]);
            }

            $this->error("Error fetching logo for Advertiser ID {$advertiser->id}: " . $e->getMessage());
        }
    }

    return 0;
}

    private function getExtensionFromImageData($imageData)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->buffer($imageData);

        switch ($mimeType) {
            case "image/jpeg":
                return "jpg";
            case "image/png":
                return "png";
            case "image/gif":
                return "gif";
            case "image/svg+xml":
                return "svg";
            case "image/webp":
                return "webp";
            case "image/bmp":
                return "bmp";
            case "image/tiff":
                return "tiff";
            default:
                return null;
        }
    }

    private function sanitizeFileName($name, $url)
    {
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '', $name);

        if (empty($sanitized)) {
            $url = trim($url);
            $url = filter_var($url, FILTER_SANITIZE_URL);
            if (!preg_match('/^https?:\/\//', $url)) {
                $url = "http://" . $url;
            }

            $parsedUrl = parse_url($url);
            $domain = $parsedUrl["host"] ?? null;
            $domain = preg_replace('/^www\./', '', $domain);
            $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '', $domain);
        }

        return strtolower($sanitized);
    }
}
