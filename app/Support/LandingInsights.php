<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LandingInsights
{
    public static function posts(array $auth, array $content): array
    {
        $articles = self::connectArticles();

        if ($articles !== []) {
            return $articles;
        }

        return self::cmsFallbackPosts($auth, $content);
    }

    protected static function connectArticles(): array
    {
        try {
            $query = DB::table('connect_articles')
                ->where('status', 'Active');

            if (self::hasColumn('connect_articles', 'created_at')) {
                $query->orderByDesc('created_at');
            } else {
                $query->orderByDesc('connect_articles_id');
            }

            $rows = $query
                ->limit(12)
                ->get(['connect_articles_id', 'title', 'image', 'created_at']);

            if ($rows->isEmpty()) {
                return [];
            }

            return $rows->map(function ($row) {
                $imagePath = ltrim((string) ($row->image ?? ''), '/');

                return [
                    'id' => (int) $row->connect_articles_id,
                    'title' => (string) $row->title,
                    'date' => self::formatDate($row->created_at ?? null),
                    'image' => $imagePath !== '' ? $imagePath : '',
                    'url' => url('/users/connect/blog/' . $row->connect_articles_id),
                ];
            })->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected static function cmsFallbackPosts(array $auth, array $content): array
    {
        $connectUrl = LandingAuth::ctaConnect($auth);

        return collect($content['insights']['posts'] ?? [])
            ->map(function ($post) use ($connectUrl) {
                return [
                    'id' => null,
                    'title' => (string) ($post['title'] ?? ''),
                    'date' => (string) ($post['date'] ?? ''),
                    'image' => (string) ($post['image'] ?? ''),
                    'url' => $connectUrl,
                ];
            })
            ->filter(fn (array $post) => $post['title'] !== '')
            ->values()
            ->all();
    }

    public static function articleUrl(array $auth, array $post): string
    {
        if ($auth['logged_in'] && !empty($post['id'])) {
            return url('/users/connect/blog/' . $post['id']);
        }

        return LandingAuth::ctaConnect($auth);
    }

    protected static function formatDate(mixed $value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('M j, Y');
        } catch (\Throwable $e) {
            return '';
        }
    }

    protected static function hasColumn(string $table, string $column): bool
    {
        static $cache = [];

        $key = $table . '.' . $column;
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        try {
            $cache[$key] = DB::getSchemaBuilder()->hasColumn($table, $column);
        } catch (\Throwable $e) {
            $cache[$key] = false;
        }

        return $cache[$key];
    }
}
