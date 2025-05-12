<?php
function generatePostShareLinks($title, $description, $imageURL, $pageURL, $faviconURL)
{
    $encodedTitle = urlencode($title);
    $encodedDescription = urlencode($description);
    $encodedImageURL = urlencode($imageURL);
    $encodedPageURL = urlencode($pageURL);
    $encodedFaviconURL = urlencode($faviconURL);

    // Facebook
    $facebookLink = "https://www.facebook.com/sharer/sharer.php?u={$encodedPageURL}";

    // Instagram (Note: Instagram does not provide a direct share link like other platforms)
    $instagramLink = "https://www.instagram.com/";

    // WhatsApp
    $whatsappLink = "https://wa.me/?text={$encodedTitle}%0A{$encodedPageURL}";

    // LinkedIn
    $linkedinLink = "https://www.linkedin.com/shareArticle?url={$encodedPageURL}&title={$encodedTitle}&summary={$encodedDescription}&source={$encodedFaviconURL}";

    // Telegram
    $telegramLink = "https://t.me/share/url?url={$encodedPageURL}&text={$encodedTitle}";

    // Twitter
    $twitterLink = "https://twitter.com/intent/tweet?url={$encodedPageURL}&text={$encodedTitle}";

    $social_share = ["facebookUrl" => $facebookLink, "instagramUrl" => $instagramLink, "whatsappUrl" => $whatsappLink, "linkedinUrl" => $linkedinLink, "telegramUrl" => $telegramLink, "twitterUrl" => $twitterLink];

    return $social_share;
}

function generateUrl($txt)
{
    $txt = substr($txt, 0, 54);
    $txt = preg_replace('~[^\\pL\d]+~u', '-', $txt);
    $txt = trim($txt, '-');
    $txt = iconv('utf-8', 'us-ascii//TRANSLIT', $txt);
    $txt = strtolower($txt);
    $txt = preg_replace('~[^-\w]+~', '', $txt);
    if (empty($txt)) {
        return 'n-a';
    }
    return $txt;
}

function urlToTitle($title)
{   
    $txt = explode('-',$title);
    $url = str_replace('-', ' ', strtolower($title));
    @$url = $txt[0]." ".$txt[1];
    return $url;
}

function cleanUrl($url)
{
    $url = str_replace(' ','-',strtolower($url));
    return $url;
}

function note($text) {
    // Remove any HTML tags
    $text = strip_tags($text);
    
    // Truncate to 200 characters
    if (strlen($text) > 200) {
        $text = substr($text, 0, 197) . '...';
    }
    
    // Convert special characters to HTML entities
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    return $text;
}

