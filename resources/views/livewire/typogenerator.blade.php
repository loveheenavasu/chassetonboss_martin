<div>
    <div class="w-full">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="GET">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="domain">
                    Domain
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="domain" type="text" placeholder="Domain" name="domain" requried>
            </div>
            <div class="flex items-center justify-between">
                <input class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="submit" value="Submit" />
            </div>
        </form>
    </div>
</div>
<?php

if(isset($_GET['submit']) && isset($_GET['domain']) && $_GET['domain'] != ''){

    $domain = trim($_GET['domain']);
    function calculatePercentages($domain) {
        // Set base percentages
        $percentages = [
            'Missing letter' => 5,
            'Doubled letter' => 4,
            'Transposition' => 3,
            'Substitution' => 3,
            'Vowel swap' => 2
        ];

        // Adjust percentages based on length
        $length = strlen($domain);
        if($length < 10) {
            $percentages['Missing letter'] += 2;
            $percentages['Doubled letter'] += 1;
        } else if($length >= 15) {
            $percentages['Transposition'] += 1;
            $percentages['Substitution'] += 1;
        }

        return $percentages;
    }

    // Get typo percentages
    $percentages = calculatePercentages($domain);

    // Typo patterns
    $patterns = [
        'Missing letter' => '/(\w)([a-z])\2(\w)/',
        'Doubled letter' => '/(\w)([a-z])(\w)\2\3/',
        'Transposition' => '/(\w)([a-z])([a-z])(\w)/',
        'Substitution' => '/(\w)([a-z])(\w)/',
        'Vowel swap' => '/(\w)([aeiouy])(\w)/' 
    ];

    // Generate typo variations
    $variations = [];

    foreach ($patterns as $type => $pattern) {

        preg_match_all($pattern, $domain, $matches);
    
        foreach ($matches[2] as $match) {
        
        $typo = preg_replace($pattern, '${1}' . $match . '${3}', $domain);
        
        if ($typo != $domain) {
            
            $variation = [
            'domain' => $typo,
            'type' => $type,
            'percent' => $percentages[$type]
            ];
            
            $variations[] = $variation; 
        }
    
        }
    
    }
    usort($variations, function($a, $b) {
        return $b['percent'] - $a['percent'];
    });
  
    // Output top 20 typo variations
    $variations = array_slice($variations, 0, 20);
    echo "Top 20 typo variations for $domain:\n\n".'<br>'; 

    echo "| Typo Variation | Likelihood % |\n".'<br>';
    echo "|-------------|------------|\n".'<br>';

    foreach ($variations as $variation) {
        echo "| {$variation['domain']} | {$variation['percent']}% |\n".'<br>';
    }
}