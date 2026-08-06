<?php

namespace App\Services;

use App\Models\PaperSubmission;

class PlagiarismService
{
    /**
     * Check the similarity of the given text against all existing paper submissions in the database.
     * Uses a basic Cosine Similarity algorithm based on term frequency.
     *
     * @param string $inputText
     * @return array Contains 'score' (percentage), 'matched_sources' (count), 'highest_match_title' (string|null)
     */
    public static function checkSimilarity(string $inputText): array
    {
        $localResult = self::checkLocalSimilarity($inputText);
        $crossrefResult = self::checkCrossrefSimilarity($inputText);
        
        $finalScore = max($localResult['score'], $crossrefResult['score']);
        
        if ($crossrefResult['score'] > $localResult['score']) {
            $highestMatchTitle = $crossrefResult['highest_match_title'];
        } else {
            $highestMatchTitle = $localResult['highest_match_title'];
        }
        
        return [
            'score' => $finalScore,
            'matched_sources' => $localResult['matched_sources'] + $crossrefResult['matched_sources'],
            'highest_match_title' => $highestMatchTitle,
        ];
    }

    private static function checkLocalSimilarity(string $inputText): array
    {
        if (empty(trim($inputText))) {
            return ['score' => 0, 'matched_sources' => 0, 'highest_match_title' => null];
        }

        $inputTokens = self::tokenizeAndCount($inputText);
        if (empty($inputTokens)) {
            return ['score' => 0, 'matched_sources' => 0, 'highest_match_title' => null];
        }

        $submissions = PaperSubmission::select('title', 'abstract')->get();
        
        $highestScore = 0;
        $highestMatchTitle = null;
        $matchedSources = 0;

        foreach ($submissions as $submission) {
            $comparisonText = $submission->title . ' ' . $submission->abstract;
            $comparisonTokens = self::tokenizeAndCount($comparisonText);
            
            if (empty($comparisonTokens)) continue;

            $score = self::calculateCosineSimilarity($inputTokens, $comparisonTokens);
            
            if ($score > 0.05) $matchedSources++;

            if ($score > $highestScore) {
                $highestScore = $score;
                $highestMatchTitle = $submission->title;
            }
        }

        return [
            'score' => (int) round($highestScore * 100),
            'matched_sources' => $matchedSources,
            'highest_match_title' => $highestMatchTitle,
        ];
    }

    private static function checkCrossrefSimilarity(string $inputText): array
    {
        $words = explode(' ', preg_replace('/\s+/', ' ', trim($inputText)));
        $chunk = implode(' ', array_slice($words, 0, 30));
        
        if (strlen($chunk) < 20) {
            return ['score' => 0, 'matched_sources' => 0, 'highest_match_title' => null];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(5)->get('https://api.crossref.org/works', [
                'query' => $chunk,
                'select' => 'title,abstract,score',
                'rows' => 3
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $items = $data['message']['items'] ?? [];
                
                if (count($items) > 0) {
                    $bestItem = $items[0];
                    $title = $bestItem['title'][0] ?? 'Unknown Title';
                    
                    $crossrefText = $title;
                    if (isset($bestItem['abstract'])) {
                        $crossrefText .= ' ' . strip_tags($bestItem['abstract']);
                    }
                    
                    $inputTokens = self::tokenizeAndCount($inputText);
                    $crossrefTokens = self::tokenizeAndCount($crossrefText);
                    
                    $similarity = self::calculateCosineSimilarity($inputTokens, $crossrefTokens);
                    $score = (int) round($similarity * 100);
                    
                    $apiScore = $bestItem['score'] ?? 0;
                    if ($apiScore > 40 && $score < 30) {
                        $score = (int) min(95, $apiScore); // Crossref score is not a percentage, but high means good match
                    }

                    if ($score > 10) {
                        return [
                            'score' => $score,
                            'matched_sources' => count($items),
                            'highest_match_title' => $title . ' [Global Crossref]'
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore API timeout/errors
        }

        return ['score' => 0, 'matched_sources' => 0, 'highest_match_title' => null];
    }

    /**
     * Tokenize text and count term frequencies, removing basic stop words.
     */
    private static function tokenizeAndCount(string $text): array
    {
        // Convert to lowercase and replace non-alphanumeric with space
        $text = strtolower(preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text));
        $words = explode(' ', $text);
        
        $stopWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'as', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'shall', 'should', 'can', 'could', 'may', 'might', 'must', 'that', 'which', 'who', 'whom', 'whose', 'this', 'these', 'those', 'it', 'its', 'they', 'their', 'them', 'we', 'our', 'us', 'you', 'your', 'yours', 'i', 'my', 'mine', 'he', 'his', 'him', 'she', 'her', 'hers', 'dan', 'atau', 'di', 'ke', 'dari', 'yang', 'untuk', 'dengan', 'dalam', 'pada', 'adalah', 'ini', 'itu', 'juga', 'akan', 'sebagai', 'tidak', 'bisa', 'oleh'];
        
        $frequencies = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 2 && !in_array($word, $stopWords)) {
                if (!isset($frequencies[$word])) {
                    $frequencies[$word] = 0;
                }
                $frequencies[$word]++;
            }
        }
        
        return $frequencies;
    }

    /**
     * Calculate cosine similarity between two frequency vectors.
     */
    private static function calculateCosineSimilarity(array $vec1, array $vec2): float
    {
        $dotProduct = 0;
        $magnitude1 = 0;
        $magnitude2 = 0;

        $allKeys = array_unique(array_merge(array_keys($vec1), array_keys($vec2)));

        foreach ($allKeys as $key) {
            $val1 = $vec1[$key] ?? 0;
            $val2 = $vec2[$key] ?? 0;

            $dotProduct += ($val1 * $val2);
        }

        foreach ($vec1 as $val) {
            $magnitude1 += ($val * $val);
        }

        foreach ($vec2 as $val) {
            $magnitude2 += ($val * $val);
        }

        $magnitude1 = sqrt($magnitude1);
        $magnitude2 = sqrt($magnitude2);

        if ($magnitude1 * $magnitude2 == 0) {
            return 0;
        }

        return $dotProduct / ($magnitude1 * $magnitude2);
    }
}
