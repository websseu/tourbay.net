<?php
require_once( 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// ===== 랜덤 대기 (0~4시간) =====
$delay = rand(0, 4 * 3600); // 0~14400초
echo "⏳ 실행 지연: " . round($delay / 60) . "분 후 시작...\n";
sleep($delay);

// ========================
// 🔗 링크프라이스 제휴 기본 URL
// ========================
$affiliate_base = "https://linkmoa.kr/click.php?m=klook&a=A100698993&l=9999&l_cd1=3&l_cd2=0&tu=";


// ========================
// 0) 순차 실행용 도시 리스트
// ======================== 
$cities = [
    "도쿄"      => ["url" => "https://www.klook.com/ko/destination/c28-tokyo/", "slug" => "tokyo"],
    "쿄토"      => ["url" => "https://www.klook.com/ko/destination/c30-kyoto/", "slug" => "kyoto"],
    "오사카"    => ["url" => "https://www.klook.com/ko/destination/c29-osaka/", "slug" => "osaka"],
    "홍콩"      => ["url" => "https://www.klook.com/ko/destination/c2-hong-kong/", "slug" => "hong-kong"],
    "마카오"    => ["url" => "https://www.klook.com/ko/destination/c3-macau/", "slug" => "macau"],
    "타이베이"  => ["url" => "https://www.klook.com/ko/destination/c19-taipei/", "slug" => "taipei"],
    "서울"      => ["url" => "https://www.klook.com/ko/destination/c13-seoul/", "slug" => "seoul"],
    "경기도"    => ["url" => "https://www.klook.com/ko/destination/c157-gyeonggi-do/", "slug" => "gyeonggi-do"],
    "강원도"    => ["url" => "https://www.klook.com/ko/destination/c156-gangwon-do/", "slug" => "gangwon-do"],
    "부산"      => ["url" => "https://www.klook.com/ko/destination/c46-busan/", "slug" => "busan"],
    "인천"      => ["url" => "https://www.klook.com/ko/destination/c158-incheon/", "slug" => "incheon"],
    "베이징"    => ["url" => "https://www.klook.com/ko/destination/c57-beijing/", "slug" => "beijing"],
    "상하이"    => ["url" => "https://www.klook.com/ko/destination/c59-shanghai/", "slug" => "shanghai"],
    "싱가포르"  => ["url" => "https://www.klook.com/ko/destination/c6-singapore/", "slug" => "singapore"],
    "호치민"    => ["url" => "https://www.klook.com/ko/destination/c33-ho-chi-minh-city/", "slug" => "ho-chi-minh-city"],
    "하노이"    => ["url" => "https://www.klook.com/ko/destination/c34-hanoi/", "slug" => "hanoi"],
    "방콕"      => ["url" => "https://www.klook.com/ko/destination/c4-bangkok/", "slug" => "bangkok"],
    "파타야"    => ["url" => "https://www.klook.com/ko/destination/c17-pattaya/", "slug" => "pattaya"],
    "쿠알라룸푸르" => ["url" => "https://www.klook.com/ko/destination/c49-kuala-lumpur/", "slug" => "kuala-lumpur"],
    "로스엔젤레스" => ["url" => "https://www.klook.com/ko/destination/c124-los-angeles/", "slug" => "los-angeles"],
    "뉴욕"      => ["url" => "https://www.klook.com/ko/destination/c93-new-york/", "slug" => "new-york"],
    "샌프란시스코" => ["url" => "https://www.klook.com/ko/destination/c129-san-francisco/", "slug" => "san-francisco"],
    "라스베가스"  => ["url" => "https://www.klook.com/ko/destination/c136-las-vegas/", "slug" => "las-vegas"],
    "파리"      => ["url" => "https://www.klook.com/ko/destination/c107-paris/", "slug" => "paris"],
    "런던"      => ["url" => "https://www.klook.com/ko/destination/c106-london/", "slug" => "london"],
    "바르셀로나"  => ["url" => "https://www.klook.com/ko/destination/c108-barcelona/", "slug" => "barcelona"],
    "로마"      => ["url" => "https://www.klook.com/ko/destination/c92-rome/", "slug" => "rome"],
    "베를린"    => ["url" => "https://www.klook.com/ko/destination/c103-berlin/", "slug" => "berlin"],
    "암스테르담"  => ["url" => "https://www.klook.com/ko/destination/c90-amsterdam/", "slug" => "amsterdam"],
    "두바이"    => ["url" => "https://www.klook.com/ko/destination/c78-dubai/", "slug" => "dubai"],
    "아부다비"  => ["url" => "https://www.klook.com/ko/destination/c131-abu-dhabi/", "slug" => "abu-dhabi"],
    "시드니"    => ["url" => "https://www.klook.com/ko/destination/c68-sydney/", "slug" => "sydney"],
    "멜버른"    => ["url" => "https://www.klook.com/ko/destination/c69-melbourne/", "slug" => "melbourne"]
];

// ========================
// 순차 실행 상태 파일
// ========================
$index_file = __DIR__ . '/current_city_index.txt';
$current_index = file_exists($index_file) ? (int) file_get_contents($index_file) : 0;
$city_keys = array_keys($cities);
$total_cities = count($city_keys);

// 현재 실행할 도시
$current_city = $city_keys[$current_index];
$city_name = $current_city;
$city_slug = $cities[$current_city]['slug'];

// JSON URL
$today = date('Y/m');
$json_url = "https://websseu.github.io/tourbay.net/klook-city/{$today}/{$city_slug}.json";

// 다음 실행을 위해 인덱스 증가
$next_index = ($current_index + 1) % $total_cities;
file_put_contents($index_file, $next_index);

$response = file_get_contents($json_url);
if ($response === false) {
    die("❌ JSON을 불러오지 못했습니다.");
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("❌ JSON 디코딩 오류: " . json_last_error_msg());
}

// ========================
// 3) 제목 패턴 (랜덤)
// ========================
$intro_patterns = [
    "안녕하세요! ✈️ 오늘은 여행 덕후라면 꼭 가봐야 할 {CITY}를 소개합니다.  
    특히 이번 여행에서는 인기 순위 {TOPSPOT}로 꼽히는 명소들이 많아서 더욱 기대되실 거예요.  
    {CITY}의 거리마다 숨겨진 매력과 즐길 거리를 하나씩 알려드릴 테니 끝까지 함께해 주세요! 🌸",

    "안녕하세요! 여행의 설렘이 필요하신가요? 🥰 그럼 오늘은 전 세계 여행자들이 사랑하는 도시, {CITY}를 소개합니다.  
    특히 {TOPSPOT}에 오른 명소들은 이미 수많은 여행자가 인증한 필수 방문지랍니다.  
    지금부터 {CITY}에서 놓치면 아쉬울 액티비티와 힐링 포인트를 안내해 드릴게요! 💫",

    "안녕하세요! 새로운 여행지를 찾고 계신가요? ✈️ 이번 글에서는 단연 인기 {TOPSPOT}를 자랑하는 여행지, {CITY}를 다뤄봅니다.  
    걷기만 해도 설레는 거리와 독특한 액티비티, 그리고 현지의 숨은 맛집까지 모두 담았어요.  
    여행 가방을 미리 챙기고 싶은 분들께 강력 추천합니다. 🎒",

    "안녕하세요, 여행자 여러분! 🧳 오늘 소개할 도시는 바로 {CITY}입니다.  
    이곳은 최근 인기 급상승 중이며, 특히 {TOPSPOT}로 알려진 명소들이 사람들의 사랑을 듬뿍 받고 있어요.  
    이번 글에서는 즐길 거리, 먹거리, 쉬어 갈 숙소까지 알차게 안내해 드릴게요. 🏨✨",

    "안녕하세요! 여행 계획 세우고 계신가요? ✈️ 오늘은 최근 가장 주목받는 여행지 중 하나인 {CITY}를 소개합니다.  
    특히 {TOPSPOT}에 빛나는 스팟들은 이미 SNS에서도 핫플레이스로 입소문이 자자해요.  
    아름다운 풍경과 모험 가득한 액티비티를 모두 경험해 보세요! 🌟",

    "안녕하세요! 휴가를 어디서 보낼지 고민 중이신가요? 🌴 그렇다면 인기 순위 {TOPSPOT}의 여행지, {CITY}를 추천합니다.  
    이곳은 전통과 현대가 함께 어우러져서 언제나 새로운 매력을 발견할 수 있어요.  
    이번 글을 읽고 나면 바로 항공권을 검색하게 되실지도 몰라요! ✈️",

    "안녕하세요! 여행의 즐거움은 발견에서 시작됩니다! 🌏 오늘은 {CITY}의 숨은 매력과 인기 {TOPSPOT} 명소를 파헤쳐 보려고 해요.  
    친구나 연인과 함께 떠난다면 더 특별한 추억을 만들 수 있을 거예요.  
    준비되셨나요? 지금부터 {CITY} 여행 이야기를 시작해 볼게요! 💕",

    "안녕하세요! 여행 준비 중이라면 꼭 체크해야 할 도시, 바로 {CITY}! ✈️  
    이번 글에서는 여행자들 사이에서 인기 {TOPSPOT}로 꼽히는 액티비티와 명소를 소개합니다.  
    한 번 다녀오면 다시 찾고 싶어질 만큼 매력적인 스팟들이 가득해요. 🌸",

    "안녕하세요! 여행의 설레는 순간을 느끼고 싶으신가요? ✈️ 그럼 이번엔 {CITY}로 떠나 보세요.  
    특히 이번에 소개할 인기 {TOPSPOT} 명소들은 처음 방문하는 여행자에게도 강추합니다.  
    준비되셨다면 지금부터 함께 둘러봐요! 🧳",

    "안녕하세요! 여행자의 발길을 사로잡는 도시, {CITY}! 🏙  
    특히 이번 글에서 소개할 명소 중 일부는 인기 순위 {TOPSPOT}에 오른 곳들이에요.  
    특별한 여행을 계획 중이라면 놓치지 마세요. ✨",

    "안녕하세요! 🥰 오늘은 {CITY}의 여행 명소들을 모아봤어요.  
    특히 여행자들이 가장 많이 찾는 {TOPSPOT} 스팟들을 엄선해 알려드립니다.  
    이 글을 읽고 나면 여행 가방부터 챙기고 싶어질지도 몰라요. 🎒",

    "안녕하세요! 특별한 여행을 원하시나요? ✈️ 이번엔 인기 급상승 중인 {CITY}로 떠나보세요.  
    최근 여행자들이 뽑은 {TOPSPOT} 스팟들이 여러분을 기다리고 있답니다.  
    자연, 문화, 미식이 모두 어우러진 매력을 느껴보세요. 🌸",

    "안녕하세요! 여행은 새로운 경험의 시작! 🌏 이번 글에서는 {CITY}의 인기 {TOPSPOT} 명소를 소개합니다.  
    액티비티부터 힐링 스팟까지, 놓치면 아쉬운 곳들만 모았어요.  
    이번 여행의 버킷리스트를 함께 채워보세요. 📝",

    "안녕하세요! 여행 계획 중이라면 주목! ✨ 오늘은 매년 수많은 여행자가 찾는 도시, {CITY}를 소개합니다.  
    특히 {TOPSPOT}로 손꼽히는 명소들은 이미 전 세계 여행자들의 사랑을 받고 있어요.  
    새로운 모험을 꿈꾸고 있다면 이번 글이 도움이 될 거예요. 🛶",

    "안녕하세요! 오늘의 여행지는 바로 {CITY}! 🏙  
    현지 분위기를 제대로 느끼고 싶다면, 이번 글에서 소개하는 인기 {TOPSPOT} 스팟들을 꼭 확인하세요.  
    여유롭게 읽다 보면 어느새 여행 일정을 세우고 계실 거예요. 📅",

    "안녕하세요! 여행으로 행복을 찾고 싶으신가요? 🫶 이번엔 인기 {TOPSPOT} 명소가 가득한 {CITY}로 떠나보세요.  
    친구, 가족, 연인 누구와 함께여도 즐거운 시간을 보낼 수 있을 거예요.  
    이번 글에서 모든 포인트를 꼼꼼히 챙겨드립니다. 💫",

    "안녕하세요! 여행은 설레는 만남의 연속이에요. ✈️ 오늘은 그런 매력을 가득 담은 도시, {CITY}를 소개합니다.  
    특히 최근 떠오르는 인기 {TOPSPOT} 스팟들은 방문자들의 인생샷 명소로 유명해요.  
    함께 둘러보며 다음 여행의 꿈을 키워보세요. 📸",

    "안녕하세요! 모험심이 가득하다면 이번 여행지는 {CITY}가 제격이에요! 🌟  
    스릴 넘치는 액티비티부터 인기 {TOPSPOT} 명소까지 즐길 거리가 다양합니다.  
    함께하는 사람과 멋진 추억을 남겨보세요. 🧳",

    "안녕하세요! 여행지 고민은 이제 그만! 🥳 이번 글에서는 인기 {TOPSPOT} 명소로 이름난 {CITY}의 스팟들을 소개합니다.  
    여행의 시작부터 끝까지 알찬 팁을 담았으니 놓치지 마세요. ✈️",

    "안녕하세요! 여행할 때 가장 중요한 건 어디를 가느냐죠. 🌏  
    오늘은 인기 {TOPSPOT} 스팟이 가득한 {CITY}로 여러분을 안내합니다.  
    설레는 모험과 힐링이 함께하는 여정을 지금부터 시작해 볼까요? 💕"
];

if (!empty($data['인기순위'])) {
    $top_items = array_slice($data['인기순위'], 0, 10);
    $top_titles = array_column($top_items, 'title');
    $top_spot = implode(', ', $top_titles);
} else {
    $top_spot = !empty($data['가볼만한곳'][0]['title']) ? $data['가볼만한곳'][0]['title'] : "인기 명소";
}

$intro_text = str_replace(
    ["{CITY}", "{TOPSPOT}"],
    [$city_name, $top_spot],
    $intro_patterns[array_rand($intro_patterns)]
);

$title_patterns = [
    "🌟 {CITY} 여행 가이드 - 필수 방문 명소 & 추천 코스 TOP 10 ✈️",
    "✈️ {CITY} 자유여행 완벽 준비 - 초보자를 위한 필수 명소 리스트 🌸",
    "🏆 {CITY} 인기 관광지 모음 - 현지인이 강력 추천하는 여행 코스 🗺",
    "🌏 {CITY} 여행의 모든 것 - 명소·맛집·액티비티 BEST 20 🍜",
    "🚶‍♀️ {CITY} 도보 여행 가이드 - 골목골목 숨어 있는 핫플레이스 🏙",
    "📸 인생샷 명소가 가득한 {CITY} 여행지 추천 BEST 15 🌈",
    "🍜 미식가를 위한 {CITY} 여행 - 꼭 먹어야 할 현지 맛집 리스트 🥢",
    "🏨 힐링과 휴식을 동시에 - {CITY} 최고의 호텔 & 숙소 추천 🛎️",
    "💕 연인과 함께하는 로맨틱 {CITY} 데이트 여행 코스 💫",
    "🧳 혼자 떠나는 {CITY} 여행 - 자유롭게 즐기는 인기 명소 🌏",
    "🎒 가족여행 필수 코스 - 아이와 함께 즐기는 {CITY} 여행 BEST 👨‍👩‍👧‍👦",
    "🚤 모험 가득! {CITY} 액티비티 & 익스트림 체험 가이드 🌊",
    "🌅 밤이 더 아름다운 도시 - {CITY} 야경 명소 BEST 12 🌌",
    "🏞 자연 속 힐링 스팟 - {CITY}의 공원·호수·산책 코스 추천 🌳",
    "📍 짧은 여행도 알차게 - 주말 당일치기 {CITY} 여행 가이드 🚉",
    "✨ 첫 방문자를 위한 {CITY} 초보 여행 가이드 - 교통·명소·팁 📚",
    "🛍 쇼핑과 즐거움이 가득 - {CITY} 베스트 쇼핑 거리 & 몰 👜",
    "🚴‍♂️ 활동적인 여행자 필수 - {CITY} 스포츠·레저 액티비티 추천 🚵‍♀️",
    "🥂 특별한 날을 위한 {CITY} 럭셔리 여행 & 고급 호텔 추천 💎",
    "🌺 계절마다 아름다운 풍경 - 봄·여름·가을·겨울 {CITY} 여행 팁 🍁",
    "📸 SNS에서 핫한 {CITY} 여행 스팟 - 포토존 가이드 🌟",
    "💫 자유여행자를 위한 {CITY} 대중교통·투어 팁 🚍",
    "🌎 배낭여행자 추천 - 저렴하게 즐기는 {CITY} 여행 BEST 💰",
    "🪴 감성 카페 & 예쁜 거리 산책 - {CITY} 로컬 핫플 탐방 ☕",
    "🏖 해변과 도시가 공존하는 {CITY} 휴양 여행 코스 추천 🌴",
    "🏙 도심 속 힐링 명소 - {CITY} 숨은 보석 같은 스팟 15곳 💫",
    "📷 첫 방문 필수! {CITY} 대표 랜드마크 & 포토 스팟 🌟",
    "🚗 렌터카로 떠나는 {CITY} 근교 드라이브 여행 추천 🚙",
    "🥳 친구와 함께 떠나는 즐거운 {CITY} 여행 BEST 코스 🎉",
    "🧳 재방문 여행자도 반하는 {CITY} 숨은 명소 & 액티비티 💕",
    "🎢 아찔하고 짜릿한 즐거움 - {CITY} 테마파크 & 놀이공원 🎠",
    "🚣‍♂️ 액티비티 천국! {CITY} 물놀이·요트·카약 체험 가이드 🚤",
    "📖 역사와 문화가 숨 쉬는 {CITY} 전통 여행지 & 유적지 탐방 🏯",
    "🌆 밤의 낭만을 만끽하는 {CITY} 로맨틱 야경 투어 ✨",
    "🛎 여행의 완성 - {CITY} 고급 리조트 & 부티크 호텔 추천 🏨",
    "🍧 여름 여행자 필수! 시원하게 즐기는 {CITY} 베스트 코스 🌞",
    "🚶‍♂️ 걸어서 즐기는 감성 {CITY} 골목 여행 가이드 🏘️",
    "🌄 주말 힐링을 위한 {CITY} 근교 자연 여행 & 드라이브 코스 🌿",
    "📦 가족 여행 필수 정보 - 아이들과 함께 즐기는 {CITY} 명소 👶",
    "🔥 인기 폭발! SNS에서 가장 많이 언급된 {CITY} 명소 TOP 10 🌟",
    "🗺 여행 고수들이 추천하는 {CITY} 로컬 맛집 & 숨은 명소 🍲",
    "💫 특별한 추억을 위한 {CITY} 커플 포토 여행 스팟 💕",
    "🌸 계절별 여행 테마 - 봄꽃·여름바다·가을단풍·겨울눈 {CITY} ❄️",
    "🚍 대중교통으로 쉽게 떠나는 {CITY} 자유여행 코스 🚉",
    "🏝 자연과 액티비티가 함께하는 완벽한 휴양지 {CITY} 🌺",
    "🏞 도심에서 벗어난 힐링 스팟 - {CITY} 근교 여행 추천 🌲",
    "📸 한 번쯤 가야 할 {CITY} 여행 버킷리스트 명소 20곳 🏆",
    "🚶‍♀️ 하루만에 즐기는 알짜배기 {CITY} 당일치기 여행 💼",
    "🪞 감성 카페·거리·포토존까지! {CITY} 힙 플레이스 투어 🎈",
    "🥂 고급스러운 휴가를 원하는 이들을 위한 {CITY} 럭셔리 여행 ✈️"
];
$selected_pattern = $title_patterns[array_rand($title_patterns)];
$post_title = str_replace("{CITY}", $city_name, $selected_pattern);

$activity_titles = [
    "🚀 신나게 즐겨봐요! ✈ {CITY} 액티비티 🌟",
    "🎢 놓치면 아쉬운! {CITY} 인기 액티비티 💫",
    "🎉 즐거움이 가득한 {CITY} 액티비티 모음",
    "🛶 모험 가득! {CITY}에서 해볼 거리 🏝",
    "✨ 여행의 재미는 바로 이거! {CITY} 액티비티"
];

$place_titles = [
    "🌸 한 번쯤 꼭 가봐야 할 {CITY} 명소들 🌟",
    "🗼 여행의 필수 코스! {CITY} 가볼 만한 곳 ✨",
    "🏞 마음이 설레는 {CITY}의 스팟들 💕",
    "🌟 놓치면 아쉬운 {CITY} 인기 명소 모음 🎈",
    "🥰 여행자들이 반한 {CITY} 베스트 스팟!"
];

$hotel_titles = [
    "🏨 편안한 휴식을 위한 {CITY} 숙소 추천 🛏️",
    "🛎 여행의 완성은 숙소! {CITY}에서 머물 곳 ✨",
    "🌙 밤이 더 아름다운 {CITY}의 인기 호텔 💕",
    "🏝 편안함과 뷰를 동시에! {CITY} 숙소 BEST 🏆",
    "🛏️ 지친 여행자들을 위한 {CITY} 휴식 공간"
];

$activity_heading = str_replace("{CITY}", $city_name, $activity_titles[array_rand($activity_titles)]);
$place_heading    = str_replace("{CITY}", $city_name, $place_titles[array_rand($place_titles)]);
$hotel_heading    = str_replace("{CITY}", $city_name, $hotel_titles[array_rand($hotel_titles)]);

// ========================
// 4) 본문 생성
// ========================
$content  = "<p class='ga-intro'>{$intro_text}</p>";

// ▶ 액티비티
if (!empty($data['액티비티'])) {
    $content .= "<h2 class='ga-title'>{$activity_heading}</h2>";
    foreach ($data['액티비티'] as $act) {
        $img    = esc_url($act['image'] ?? '');
        $title  = esc_html($act['title'] ?? '');
        $orig   = $act['link'] ?? '#';
        $link   = $affiliate_base . urlencode($orig); 
        $type   = esc_html($act['type'] ?? '');
        $score  = esc_html($act['score'] ?? '');
        $reviews= esc_html($act['reviews'] ?? '');
        $price  = esc_html($act['price'] ?? '');

        $content .= "
        <div class='ga-box'>
            <div>
                <a href='{$link}' target='_blank'><img src='{$img}' alt='{$title}'></a>
            </div>
            <div>
                <strong><a href='{$link}' target='_blank'>{$title}</a></strong><br>
                <span>{$type}</span><br>
                <span>평점: {$score} (후기: {$reviews})</span><br>
                <span>가격: {$price}</span>
            </div>
        </div>";
    }
}

// ▶ 가볼만한 곳
if (!empty($data['가볼만한곳'])) {
    $content .= "<h2 class='ga-title'>{$place_heading}</h2>";
    foreach ($data['가볼만한곳'] as $place) {
        $img    = esc_url($place['image'] ?? '');
        $title  = esc_html($place['title'] ?? '');
        $orig   = $place['link'] ?? '#';
        $link   = $affiliate_base . urlencode($orig); 
        $type   = esc_html($place['type'] ?? '');
        $score  = esc_html($place['score'] ?? '');
        $desc   = esc_html($place['description'] ?? '');

        $content .= "
        <div class='ga-box ga-border'>
            <div>
                <a href='{$link}' target='_blank'><img src='{$img}' alt='{$title}'></a>
            </div>
            <div>
                <strong><a href='{$link}' target='_blank'>{$title}</a></strong><br>
                <span>{$type}</span><br>
                <span>평점: {$score}</span><br>
                <a href='{$link}' target='_blank' class='ga-btn'>더 자세한 정보는 👉</a>
            </div>
        </div>
        <p class='ga-desc'> 🫵 {$desc}</p>";
    }
}

// ▶ 숙소
if (!empty($data['숙소'])) {
    $content .= "<h2 class='ga-title'>{$hotel_heading}</h2>";
    foreach ($data['숙소'] as $hotel) {
        $img     = esc_url($hotel['image'] ?? '');
        $title   = esc_html($hotel['title'] ?? '');
        $orig    = $hotel['link'] ?? '#';
        $link    = $affiliate_base . urlencode($orig);
        $score   = esc_html($hotel['score'] ?? '');
        $reviews = esc_html($hotel['reviews'] ?? '');
        $price   = esc_html($hotel['price'] ?? '');

        $content .= "
        <div class='ga-box'>
            <div>
                <a href='{$link}' target='_blank'><img src='{$img}' alt='{$title}'></a>
            </div>
            <div style='flex:1;'>
                <strong><a href='{$link}' target='_blank'>{$title}</a></strong><br>
                <span>평점: {$score} (후기: {$reviews})</span><br>
                <span>가격: {$price}</span>
                <a href='{$link}' target='_blank' class='ga-btn'>자세히 보기</a>
            </div>
        </div>";
    }
}

// ========================
// 5) 새 글 생성
// ========================
$new_post = [
    'post_title'   => $post_title,
    'post_content' => $content,
    'post_status'  => 'publish',
    'post_author'  => 1,
    'post_type'    => 'post',
    'post_name'    => sanitize_title($post_title),
];

$post_id = wp_insert_post($new_post);

if ($post_id) {
    wp_set_post_terms($post_id, [13], 'category');

    // 대표 이미지 설정
    $image_candidates = [];
    foreach (['가볼만한곳','숙소','액티비티'] as $key) {
        if (!empty($data[$key])) {
            foreach ($data[$key] as $item) {
                if (!empty($item['image'])) $image_candidates[] = $item['image'];
            }
        }
    }

    if (!empty($image_candidates)) {
        $thumb_url = $image_candidates[array_rand($image_candidates)];
        $image_id = media_sideload_image($thumb_url, $post_id, null, 'id');
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
        } else {
            echo "❌ 이미지 업로드 실패: ".$image_id->get_error_message()."<br>";
        }
    } else {
        echo "⚠️ 대표 이미지를 찾지 못했습니다.<br>";
    }
    $today_date = date('Y-m-d');
    echo "✅ 포스트 작성 완료 {$today_date} {$city_name} 출력완료<br>";

} else {
    echo "❌ 포스트 등록 실패";
}
?>
