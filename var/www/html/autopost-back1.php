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

// 소개글 먼저 추가
$content .= '
    <div class="hotel-summary" style="margin-bottom:40px; padding:20px; background:#f9f9f9; border-radius:8px;">
        <h3>🌟 '.$selected_city.' 호텔 TOP10</h3>
        <p>'.$selected_city.'에서 인기 있는 숙소들을 엄선했습니다. 
        여행을 계획 중이라면 아래 목록을 참고하여 합리적인 가격과 편리한 위치의 호텔을 확인해보세요.</p>
        <p>최신 후기와 가격을 보려면 각 호텔의 <strong>💰 가격 확인해보기</strong> 버튼을 클릭하세요.</p>
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
