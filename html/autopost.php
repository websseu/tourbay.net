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

    // ── 도시명이 중간에 오는 패턴 ──
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

    // ── 도시명이 끝에 오는 패턴 ──
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

    // ── 여행 목적별 / 키워드 다양화 ──
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

    // ── 기타 SEO 친화적인 패턴 ──
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

foreach ($data as $index => $hotel) {
    $rank = $index + 1; 
    $name    = esc_html($hotel['name'] ?? '');
    $detail  = esc_html($hotel['detail'] ?? '');
    $url     = esc_url($hotel['hotel_url'] ?? '#');
    $gallery = $hotel['gallery'] ?? [];
    $rating  = esc_html($hotel['rating'] ?? '');
    $reviews = esc_html($hotel['reviews'] ?? '');
    $area    = esc_html($hotel['area'] ?? '');

    // 메인 이미지 1장 (없으면 빈칸)
    $main_img = esc_url($gallery[0] ?? '');

    // 썸네일 4장 (2~5번째 이미지)
    $thumbs = array_slice($gallery, 1, 4);

    // 호텔 박스
    $content .= '<div class="hotel-box" style="width:100%;">';

    // 제목
    $content .= '<h2 style="margin:50px 0 10px 0;font-size:1.5rem;">
        '.$rank.'. <a href="' . $url . '" target="_blank" rel="noopener" style="color:#333;text-decoration:none;">' . $name . '</a>
    </h2>';

    // 갤러리 영역
    $content .= '<div class="hotel-gallery"> <a href="'.$url.'" target="_blank" rel="noopener" style="display:block;">';

    // 메인 이미지
    if ($main_img) {
        $content .= '
            <div class="g1" style="
                background-image:url(\''.$main_img.'\');
                background-size:cover;
                background-position:center;
                height:400px;
                margin-bottom:5px;
                border-radius:6px;
                transition:transform .3s;
            "></div>
        ';
    }

    // 썸네일 이미지 
    $content .= '<div class="g2" style="display:flex; gap:5px;">';
    foreach ($thumbs as $thumb) {
        $thumb_url = esc_url($thumb);
        $content .= '
            <div style="
                width:100%;
                height:100px;
                background-image:url(\''.$thumb_url.'\');
                background-size:cover;
                background-position:center;
                border-radius:4px;
                transition:transform .3s;
            "></div>
        ';
    }
    $content .= '</div>'; // .g2
    $content .= '</a></div>'; // .hotel-gallery

    
    // 지역 / 평점 / 리뷰
    $content .= '<p style="font-size:0.9rem; color:#666; margin-bottom:5px; margin-top:10px">
        지역: <a href="'.$url.'" target="_blank" rel="noopener" style="color:#0077cc; text-decoration:none;">'.$area.'</a>
    </p>';
    if ($rating)  $content .= '<p style="font-size:0.9rem; color:#444; margin-bottom:5px;">평점: '.$rating.'</p>';
    if ($reviews) $content .= '<p style="font-size:0.9rem; color:#444; margin-bottom:5px;">'.$reviews.'</p>';

    // 디테일
    $content .= '<p style="font-size:1rem;line-height:1.6;color:#555;">' . $detail . '</p>';

    // 가격 확인 버튼
    $content .= '<div style="margin-top:10px; margin-bottom:30px;">
        <a href="'.$url.'" target="_blank" rel="noopener"
        style="
            display:inline-block;
            width: 100%;
            background:#0077cc;
            color:#fff;
            padding:10px 20px;
            font-size:1rem;
            font-weight:bold;
            text-decoration:none;
            border-radius:6px;
            transition:background .3s;
            text-align:center;
        "
        onmouseover="this.style.background=\'#005fa3\'"
        onmouseout="this.style.background=\'#0077cc\'">💰 가격 확인해보기</a>
    </div>';

    $content .= '</div>'; // .hotel-box
}

// ========================
// 새 글 생성
// ========================
$new_post = array(
    'post_title'   => $post_title,
    'post_content' => $content,
    'post_status'  => 'publish',
    'post_author'  => 1,
    'post_type'    => 'post'
);

$post_id = wp_insert_post( $new_post );


if ( $post_id ) {
    echo "포스트 작성됨: {$post_id}<br>";

    // city 카테고리 ID가 31이라 가정
    wp_set_post_terms( $post_id, array(3), 'category' );
    echo "카테고리 설정 완료<br>";

    // JSON에서 첫 번째 호텔의 첫 번째 이미지 추출
    $first_image_url = $data[0]['gallery'][0] ?? '';
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
