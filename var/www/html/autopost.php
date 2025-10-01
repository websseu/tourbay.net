<?php
require_once( 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// ========================
// 도시 목록
// ========================
$cities = [
    "발리" => "bali",
    "반둥" => "bandung",
    "방콕" => "bangkok",
    "보라카이" => "boracay",
    "부산" => "busan",
    "세부" => "cebu",
    "치앙마이" => "chiang",
    "다낭" => "da-nang",
    "후쿠오카" => "fukuoka",
    "하노이" => "hanoi",
    "핫야이" => "hat-yai",
    "호치민" => "ho-chi-minh",
    "호이안" => "hoi-an",
    "홍콩" => "hong-kong",
    "후아힌" => "hua",
    "화롄" => "hualien",
    "이포" => "ipoh",
    "자카르타" => "jakarta",
    "제주도" => "jeju-island",
    "조호바루" => "johor-bahru",
    "가오슝" => "kaohsiung",
    "코타키나발루" => "kota",
    "끄라비" => "krabi",
    "쿠알라룸푸르" => "kuala",
    "쿠안탄" => "kuantan",
    "교토" => "kyoto",
    "마카오" => "macau",
    "말라카" => "malacca",
    "마닐라" => "manila",
    "나고야" => "nagoya",
    "나트랑" => "nha",
    "오키나와" => "okinawa",
    "오사카" => "osaka",
    "파타야" => "pattaya",
    "페낭" => "penang",
    "푸켓" => "phuket",
    "삿포로" => "sapporo",
    "서울" => "seoul",
    "상하이" => "shanghai",
    "싱가포르" => "singapore",
    "수라바야" => "surabaya",
    "타이중" => "taichung",
    "타이난" => "tainan",
    "타이베이" => "taipei",
    "도쿄/동경" => "tokyo",
    "이란" => "yilan",
    "족자카르타" => "yogyakarta",
    "런던" => "london",
    "파리" => "paris"
];

// ========================
// 도시 무작위 선택 (최대 10번 시도)
// ========================
$city_keys = array_keys($cities);
$max_attempts = 10;
$selected_city = null;
$data = null;

for ($i = 0; $i < $max_attempts; $i++) {
    $city_kr = $city_keys[array_rand($city_keys)];
    $city_en = $cities[$city_kr];

    $json_url = "https://websseu.github.io/data_hotel_korea/CityTop10/2025-09-24/{$city_en}.json";
    echo "[$i] 시도 → {$city_kr} ({$city_en}) : {$json_url}<br>";

    $response = wp_remote_get($json_url);
    if ( is_wp_error($response) ) {
        echo "❌ JSON 가져오기 실패: " . $response->get_error_message() . "<br>";
        continue;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if ( !empty($data) ) {
        $selected_city = $city_kr;
        echo "✅ 데이터 발견! → {$selected_city}<br>";
        break;
    } else {
        echo "⚠️ 데이터 없음, 다른 도시로 시도...<br>";
    }
}

if (!$selected_city) {
    die("❌ 10번 시도했지만 데이터가 있는 도시를 찾지 못했습니다.");
}

// ========================
// 제목 스타일 후보
// ========================
$title_patterns = [
    // ── 도시명이 앞에 오는 패턴 ──
    "{$city_kr} 가성비 호텔 TOP10",
    "{$city_kr} 추천 호텔 BEST10",
    "{$city_kr} 인기 호텔 TOP10",
    "{$city_kr} 가족 여행 추천 숙소 10곳",
    "{$city_kr} 커플 여행자를 위한 호텔 BEST10",
    "{$city_kr} 여행자들이 좋아하는 호텔 TOP10",
    "{$city_kr} 가성비 최고 숙소 모음",
    "{$city_kr} 최신 인기 호텔 10곳",
    "{$city_kr} 최고의 후기 평점 호텔 TOP10",
    "{$city_kr} 여행 필수 숙소 BEST10",
    "가성비 좋은 {$city_kr} 호텔 TOP10",
    "여행자 추천 {$city_kr} 호텔 BEST10",
    "편안한 숙소, 바로 {$city_kr} 호텔 TOP10",
    "럭셔리 여행의 중심, {$city_kr} 호텔 추천 10곳",
    "인기 급상승 중인 {$city_kr} 숙소 BEST10",
    "조식이 맛있는 {$city_kr} 호텔 TOP10",
    "수영장이 멋진 {$city_kr} 호텔 BEST10",
    "쇼핑하기 좋은 {$city_kr} 호텔 추천 10곳",
    "뷰가 예쁜 {$city_kr} 호텔 TOP10",
    "교통 편리한 {$city_kr} 숙소 BEST10",
    "가족 여행자를 위한 추천 호텔 {$city_kr}",
    "저렴하지만 만족도 높은 호텔 {$city_kr}",
    "뷰 맛집으로 유명한 호텔 {$city_kr}",
    "편리한 위치의 인기 숙소 {$city_kr}",
    "연인과 떠나기 좋은 로맨틱 호텔 {$city_kr}",
    "신혼여행 추천 럭셔리 호텔 {$city_kr}",
    "공항 근처 편리한 숙소 {$city_kr}",
    "아이와 함께하기 좋은 가족 호텔 {$city_kr}",
    "출장객이 선호하는 비즈니스 호텔 {$city_kr}",
    "예약률 높은 인기 숙소 {$city_kr}",
    "가성비로 선택하는 {$city_kr} 숙소 TOP10",
    "편안하고 깔끔한 {$city_kr} 호텔 추천 10곳",
    "청결도 높은 {$city_kr} 인기 호텔 BEST10",
    "힐링이 필요한 당신을 위한 {$city_kr} 호텔 TOP10",
    "아이 동반 여행에 적합한 {$city_kr} 숙소 BEST10",
    "조용하고 프라이빗한 {$city_kr} 호텔 10곳",
    "도심 속 힐링 여행, {$city_kr} 추천 호텔 TOP10",
    "로맨틱 데이트 여행, {$city_kr} 인기 호텔 BEST10",
    "럭셔리 경험을 위한 {$city_kr} 5성급 호텔 TOP10",
    "자연경관이 아름다운 {$city_kr} 숙소 추천 10곳",
    "여행객이 뽑은 인기 호텔 리스트 {$city_kr}편",
    "리뷰 평점 높은 추천 호텔 {$city_kr} TOP10",
    "쇼핑·관광에 최적화된 숙소 {$city_kr} BEST10",
    "도시 여행자 필수 체크인 호텔 {$city_kr}편",
    "비즈니스와 휴식을 모두 챙긴 호텔 {$city_kr} TOP10",
    "편리한 교통과 최고의 후기 {$city_kr} 호텔 BEST10",
    "공항과 가까운 편리한 숙소 {$city_kr} 추천 10곳",
    "올해 인기 급상승 호텔 {$city_kr} TOP10",
    "첫 여행자를 위한 추천 숙소 {$city_kr} BEST10",
    "가족·커플 모두 만족한 호텔 {$city_kr} 추천 리스트"
];


// 무작위로 제목 선택
$post_title = $title_patterns[array_rand($title_patterns)];

// ========================
// 글 내용 만들기
// ========================
$content = "";
$summary_list = [];

// ========================
// 소개글 문장 배열
// ========================
$opening_sentences = [
    "안녕하세요! {$selected_city} 호텔 TOP10을 선정해 보았습니다. 😊",
    "안녕하세요! 이번 글에서는 {$selected_city}의 인기 호텔 TOP10을 소개해 드리려고 합니다.",
    "반갑습니다! 여행을 계획 중이신 분들을 위해 {$selected_city} 호텔 TOP10 리스트를 준비했습니다.",
    "안녕하세요! {$selected_city}에서 머물기 좋은 호텔을 TOP10으로 정리해 보았어요.",
    "여행 준비 중이신가요? 이번 글에서는 {$selected_city}의 최고 평점 호텔 TOP10을 꼽아 봤습니다."
];

$explanation_sentences = [
    "평점과 이용 후기, 그리고 다양한 여행 사이트의 랭킹 데이터를 참고하여 객관적으로 호텔을 선정했습니다.",
    "수많은 여행자들의 실제 후기와 평점을 바탕으로 엄선한 결과입니다.",
    "호텔을 직접 비교하며 가장 인기 있고 만족도가 높은 곳들만 모아 보았습니다.",
    "후기 수와 평점뿐 아니라 위치와 편의성까지 고려해 여행자들이 선호하는 호텔을 골랐습니다.",
    "데이터와 리뷰를 함께 분석하여 신뢰할 수 있는 호텔 리스트를 만들었습니다."
];

$intro_paragraphs = [
    "여행의 시작은 언제나 설렘으로 가득 차 있습니다. {$selected_city}는 그런 설렘을 더욱 크게 만들어주는 도시죠. 이번 글에서는 {$selected_city}를 방문할 때 꼭 머물러야 할 편안하고 매력적인 호텔들을 소개합니다.",
    "낯선 도시를 여행할 때 가장 중요한 것은 편안한 쉼터입니다. {$selected_city}는 다양한 매력을 가진 도시지만, 올바른 숙소를 선택해야 여행이 더욱 즐겁습니다. 오늘은 {$selected_city}에서 믿을 수 있는 숙소들을 모아봤습니다.",
    "여행의 만족도를 좌우하는 요소 중 하나는 숙소입니다. {$selected_city}는 전통과 현대가 공존하는 도시로, 어디에 머무느냐에 따라 여행의 경험이 달라집니다. 이 글에서는 여행자들이 사랑하는 최고의 호텔을 추천합니다.",
    "처음 {$selected_city}를 찾는 분이라면 어디에 숙소를 정할지 고민이 많을 겁니다. 걱정하지 마세요. 이번 글에서는 여행 초보자부터 경험자까지 모두 만족할 만한 호텔들을 엄선해 소개해드립니다.",
    "{$selected_city}의 거리를 거닐다 보면 도시의 생동감과 따뜻함을 동시에 느낄 수 있습니다. 여행 중 하루의 피로를 풀어줄 숙소는 그만큼 중요하죠. 여러분의 완벽한 여행을 위해 가장 추천할 만한 호텔들을 모았습니다.",
    "여행을 계획할 때 숙소 선택은 가장 중요한 고민 중 하나입니다. {$selected_city}는 다채로운 매력을 가진 도시인 만큼 다양한 스타일의 호텔이 있습니다. 이 글을 통해 당신의 여행 스타일에 맞는 숙소를 찾아보세요.",
    "편안한 숙소에서 시작되는 여행은 더욱 특별합니다. {$selected_city}의 거리와 풍경을 즐긴 뒤 따뜻하게 맞아줄 호텔을 찾고 있다면, 이번 글이 도움이 될 것입니다.",
    "한 번 방문하면 잊기 어려운 매력을 지닌 도시, {$selected_city}. 하지만 제대로 된 숙소를 찾지 못한다면 그 매력을 온전히 느끼기 어렵죠. 이 글은 그런 걱정을 덜어드리기 위해 준비했습니다.",
    "{$selected_city}는 계절마다 다른 매력을 보여주는 여행지입니다. 그 매력을 오롯이 즐기려면 편안한 휴식이 필수입니다. 이번 추천 호텔 리스트로 여행의 질을 한층 높여보세요.",
    "낯선 도시에서 편안함을 찾는 것은 생각보다 중요합니다. {$selected_city}의 생동감 있는 거리와 조화를 이루는 편안한 호텔들을 이번 글에서 소개합니다.",
    "여행지의 첫인상은 공항에서가 아니라 숙소에서 시작됩니다. {$selected_city}의 호텔들은 여행자들에게 따뜻한 환영과 편안한 휴식을 선사합니다. 이 글은 그런 곳들을 안내합니다.",
    "{$selected_city}는 오래 머물수록 더 많은 매력을 발견하게 되는 도시입니다. 그런 만큼 편안하고 믿을 수 있는 숙소 선택이 중요합니다. 이번 추천 목록이 여러분의 여행을 더 풍요롭게 만들어 줄 것입니다.",
    "숙소는 단순한 잠자리 이상의 의미를 지닙니다. 특히 {$selected_city}처럼 활기찬 도시를 여행할 때는 더욱 그렇습니다. 이 글에서 추천하는 호텔들은 여러분의 하루를 더욱 특별하게 만들어줄 것입니다.",
    "새로운 도시를 여행할 때는 하루의 끝을 편안히 보낼 수 있는 숙소가 꼭 필요합니다. {$selected_city}의 호텔 중에서도 여행자들이 극찬한 곳들을 모아 소개합니다.",
    "{$selected_city}는 누구나 한 번쯤 가보고 싶어 하는 인기 여행지입니다. 아름다운 도시의 풍경과 맛있는 음식도 중요하지만, 숙소가 편해야 진정한 여행의 즐거움을 느낄 수 있죠.",
    "낮에는 활기찬 거리 탐방, 밤에는 편안한 휴식. 이것이야말로 진정한 여행의 완성입니다. 이번 글에서는 {$selected_city}에서 그 두 가지를 모두 만족시켜 줄 호텔을 추천합니다.",
    "{$selected_city}는 밤이 되면 또 다른 얼굴을 보여주는 도시입니다. 여행의 추억을 더욱 빛내기 위해서는 좋은 숙소가 필요하죠. 이 글이 그 길잡이가 될 것입니다.",
    "첫 여행을 준비하는 설렘과 약간의 긴장감, 그 모든 감정이 {$selected_city}에서는 특별해집니다. 그 설렘을 이어갈 수 있는 호텔들을 추천해 드립니다.",
    "여행은 잠시 머무는 숙소에서도 그 도시의 이야기를 느낄 수 있을 때 더욱 풍요로워집니다. {$selected_city}의 호텔들은 그 자체로도 하나의 경험이 될 것입니다.",
    "{$selected_city}를 제대로 느끼고 싶다면 여행 내내 편안함을 보장해 줄 호텔이 필요합니다. 이번 글은 그런 호텔들을 찾는 데 도움을 드리고자 합니다.",
    "짧은 여행일수록 숙소의 선택이 더 중요합니다. {$selected_city}의 짧지만 강렬한 추억을 위해 최고의 호텔들을 엄선했습니다.",
    "여행 중 호텔은 단순히 머무는 공간이 아니라 여행의 일부입니다. {$selected_city}의 여행을 더욱 특별하게 만들어줄 숙소들을 지금 소개합니다.",
    "숙소를 고르는 일은 여행 계획의 가장 중요한 부분 중 하나입니다. 이번 글에서는 {$selected_city}에서 수많은 여행자들에게 사랑받은 호텔들을 소개합니다.",
    "여행은 장소보다도 그곳에서 경험한 순간들이 오래 남습니다. {$selected_city}의 호텔에서 보낸 편안한 밤들이 여러분의 추억을 더욱 따뜻하게 해줄 것입니다.",
    "{$selected_city}를 여행하는 이유는 사람마다 다르지만, 편안한 숙소의 필요성은 모두 같습니다. 이번 글이 그 해답이 되어줄 것입니다.",
    "도시의 밤 풍경이 아름답기로 유명한 {$selected_city}. 그 매력을 제대로 느끼려면 숙소 선택이 중요합니다. 이 글에서 그 고민을 덜어드립니다.",
    "{$selected_city}의 매력은 다채롭지만, 올바른 숙소가 없다면 그 매력을 온전히 즐기기 어렵습니다. 이번 글은 그런 걱정을 덜어드리기 위해 준비했습니다.",
    "여행의 마지막은 늘 숙소에서의 휴식으로 마무리됩니다. {$selected_city}의 편안한 호텔에서 하루를 마무리한다면 여행의 기억이 더욱 따뜻해질 것입니다.",
    "{$selected_city}는 낮과 밤 모두 매력적인 도시입니다. 그 하루의 시작과 끝을 책임질 호텔을 찾고 계시다면, 이번 추천이 도움이 될 것입니다.",
    "여행은 새로운 경험과 익숙함이 어우러질 때 더욱 즐겁습니다. {$selected_city}의 호텔에서 그런 경험을 누려보세요.",
    "낯선 도시의 첫날밤은 조금 특별합니다. {$selected_city}의 따뜻한 호텔들이 그 특별함을 더해줄 것입니다.",
    "{$selected_city}는 여행자의 기대를 저버리지 않는 도시입니다. 그 기대를 완성시켜줄 숙소들을 이번 글에서 만나보세요.",
    "편안함과 모험은 훌륭한 여행의 두 축입니다. {$selected_city}의 호텔들은 그 균형을 잘 맞춰 여행을 더욱 즐겁게 만들어줍니다.",
    "밤마다 다른 얼굴을 보여주는 {$selected_city}의 매력을 즐기려면 호텔의 위치와 분위기도 중요합니다. 이번 글은 그런 점을 고려해 추천 리스트를 만들었습니다.",
    "여행의 첫 인상과 마지막 인상 모두 숙소에서 시작되고 끝납니다. {$selected_city}의 최고의 호텔에서 그 특별한 기억을 만들어 보세요.",
    "낯선 곳에서 하루를 마무리하는 일이 설레면서도 중요합니다. {$selected_city}의 믿을 수 있는 호텔들이 여러분의 여정을 편안히 지켜줄 것입니다.",
    "여행의 질은 편안한 숙소가 보장될 때 한층 높아집니다. 이번 글에서는 {$selected_city}의 여행을 더 특별하게 만들어줄 호텔을 추천합니다.",
    "짧은 여행이라도 제대로 된 숙소에 머문다면 훨씬 더 오래 기억에 남습니다. {$selected_city}의 호텔들이 그런 추억을 만들어드립니다.",
    "여행 중에는 하루를 마무리하는 공간이 더욱 중요합니다. {$selected_city}의 따뜻하고 세련된 호텔들을 추천합니다.",
    "{$selected_city}의 풍경과 문화를 즐기다 보면 하루가 금세 지나갑니다. 그런 하루를 편안히 마무리할 호텔이 필요합니다. 이번 글이 도움이 될 것입니다.",
    "{$selected_city}는 여행자들에게 언제나 새로운 영감을 주는 도시입니다. 그 영감을 이어갈 수 있는 편안한 숙소들을 이번에 소개합니다.",
    "{$selected_city}의 매력을 제대로 느끼려면 도시 탐험과 휴식을 균형 있게 즐길 수 있어야 합니다. 그 중심에는 좋은 호텔이 있습니다.",
    "{$selected_city}의 도심 속 호텔은 여행의 편리함과 휴식을 동시에 제공합니다. 이번 글은 그런 호텔들을 모아 소개합니다.",
    "여행은 잠시 머무는 숙소에서도 큰 차이를 만듭니다. {$selected_city}의 훌륭한 호텔에서 편안함과 즐거움을 모두 누려보세요.",
    "{$selected_city}의 거리를 걷다 보면 하루가 길면서도 짧게 느껴집니다. 그 하루를 마무리할 따뜻한 호텔이 필요합니다.",
    "{$selected_city}의 밤은 여행자들에게 또 다른 추억을 선사합니다. 그 추억을 이어갈 숙소를 찾고 있다면 이 글이 도움이 될 것입니다.",
    "처음 {$selected_city}를 방문하는 여행자에게 숙소는 더 큰 의미를 가집니다. 이번 추천 리스트가 그 의미를 완성시켜 줄 것입니다.",
    "여행의 하이라이트는 종종 숙소에서 만들어지기도 합니다. {$selected_city}의 호텔에서 특별한 하루를 경험해 보세요.",
    "{$selected_city}의 활기찬 거리와 고즈넉한 풍경은 여행의 매력을 배가시킵니다. 그런 하루를 마무리할 편안한 호텔들을 추천합니다."
];

// 랜덤 문장
$opening = $opening_sentences[array_rand($opening_sentences)];
$explanation = $explanation_sentences[array_rand($explanation_sentences)];
$intro_body = $intro_paragraphs[array_rand($intro_paragraphs)];

$intro_text = $opening . " " . $explanation . " " . $intro_body;

$content .= '
    <div class="hotel-summary" style="margin-bottom:40px; padding:20px; background:#f9f9f9; border-radius:8px;">
        <h3>🌟 '.$selected_city.' 호텔 TOP10</h3>
        <p>'. nl2br($intro_text) .'</p>
    </div>
';

// 중간글
foreach ($data as $index => $hotel) {
    $rank    = $index + 1;
    $name    = esc_html($hotel['name'] ?? '');
    $detail  = esc_html($hotel['detail'] ?? '');
    $url     = esc_url($hotel['hotel_url'] ?? '#');
    $gallery = $hotel['gallery'] ?? [];
    $area    = esc_html($hotel['area'] ?? '');
    $rating  = esc_html($hotel['rating'] ?? '');
    $reviews = esc_html($hotel['reviews'] ?? '');

    // 마무리 용
    $rating_raw = $hotel['rating'] ?? '0';
    $rating_num = (float)preg_replace('/[^\d\.]/','',$rating_raw);

    $reviews_raw = $hotel['reviews'] ?? '0';
    $reviews_num = (int)preg_replace('/[^\d]/','',$reviews_raw);

    // 마무리 요약에 넣을 데이터 저장
    $summary_list[] = [
        'name'    => $name,
        'url'     => $url,
        'rating'  => $rating_num,
        'reviews' => $reviews_num,
    ];

    // 이미지 5장 변수로 추출
    $main_image1 = !empty($gallery[0]) ? esc_url($gallery[0]) : '';
    $main_image2 = !empty($gallery[1]) ? esc_url($gallery[1]) : '';
    $main_image3 = !empty($gallery[2]) ? esc_url($gallery[2]) : '';
    $main_image4 = !empty($gallery[3]) ? esc_url($gallery[3]) : '';
    $main_image5 = !empty($gallery[4]) ? esc_url($gallery[4]) : '';

    // HTML 출력
    $content .= '
        <div class="hotel-box">
            <h2>'.$rank.'. '.$name.'</h2>

            <div>
                <a class="hotel-gallery" href="'.$url.'" target="_blank" rel="noopener">
                    <div class="main-image">
                        '.($main_image1 ? '<img src="'.$main_image1.'" alt="'.esc_attr($name).'"/>' : '<p>이미지가 없습니다</p>').'
                    </div>
                    <div class="thumbs">
                        '.($main_image2 ? '<img src="'.$main_image2.'" alt="'.esc_attr($name).'"/>' : '').''.($main_image3 ? '<img src="'.$main_image3.'" alt="'.esc_attr($name).'"/>' : '').''.($main_image4 ? '<img src="'.$main_image4.'" alt="'.esc_attr($name).'"/>' : '').''.($main_image5 ? '<img src="'.$main_image5.'" alt="'.esc_attr($name).'"/>' : '').'
                    </div>
                </a>
            </div>

            <div class="hotel-info">
                '.($area ? '<p>📍 지역: <a href="'.$url.'" target="_blank" rel="noopener">'.$area.'</a></p>' : '').'
                '.($rating  ? '<p>⭐ 평점: '.$rating.'</p>' : '').'
                '.($reviews ? '<p>💬 리뷰: '.$reviews.'</p>' : '').'
            </div>

            <p class="hotel-detail">'.$detail.'</p>   

            <a class="hotel-btn" href="'.$url.'" target="_blank" rel="noopener">💰 가격 확인해보기</a>
        </div>
    ';
}

// 마무리 글
$content .= '
    <div class="hotel-summary-list">
        <h3>🏆 '.$post_title.' 요약 정리</h3>
        <ol>';
            foreach ($summary_list as $item) {
                $rating_text = $item['rating'] ? ' ('.$item['rating'].'/'.$item['reviews'].')' : '';
       
                $content .= '
                    <li>
                        <strong><a href="'.$item['url'].'" target="_blank" rel="noopener">'.$item['name'].'</a></strong><span>(⭐ '.number_format($item['rating'],1).' / '.number_format($item['reviews']).')</span>
                    </li>';
            }

            $content .= '
        </ol>
        <p>
            * 위 호텔들은 평점과 리뷰 수를 함께 고려한 ❤️ 추천지수를 기준으로 선정되었습니다. 💰 버튼을 눌러 최신 가격과 예약 가능 여부를 확인하세요.
        </p>
    </div>
';

// ========================
// 새 글 생성
// ========================
$new_post = array(
    'post_title'   => $post_title,
    'post_content' => $content,
    'post_status'  => 'publish',
    'post_author'  => 1,
    'post_type'    => 'post',
    'post_name'    => sanitize_title($post_title),
);

$post_id = wp_insert_post( $new_post );


if ( $post_id ) {
    echo "포스트 작성됨: {$post_id}<br>";

    // city 카테고리 ID가 31이라 가정
    wp_set_post_terms( $post_id, array(3), 'category' );
    echo "카테고리 설정 완료<br>";

    // JSON에서 첫 번째 호텔의 이미지 추출
    $random_index = rand(0, min(5, count($data) - 1));
    $first_image_url = $data[$random_index]['gallery'][0] ?? '';    

    if ( $first_image_url ) {
        $image_id = media_sideload_image( $first_image_url, $post_id, null, 'id' );

        if ( !is_wp_error( $image_id ) ) {
            set_post_thumbnail( $post_id, $image_id );
            echo "대표 이미지 설정 완료 (첨부파일 ID: {$image_id})";
        } else {
            echo "이미지 업로드 실패: " . $image_id->get_error_message();
        }
    } else {
        echo "대표 이미지 URL이 없습니다.";
    }
} else {
    echo "포스트 등록 실패";
}
?>
