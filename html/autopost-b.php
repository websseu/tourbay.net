<?php
require_once( 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// ========================
// 1) 도시 목록 정의
// ========================
$cities = [
    "발리" => "bali",
    "반둥" => "bandung",
    "방콕" => "bangkok",
    "보라카이" => "boracay",
    "부산" => "busan",
    "세부" => "cebu",
    "치앙마이" => "chiang",
    "다낭" => "da",
    "후쿠오카" => "fukuoka",
    "하노이" => "hanoi",
    "핫야이" => "hat",
    "호치민" => "ho",
    "호이안" => "hoi",
    "홍콩" => "hong",
    "후아힌" => "hua",
    "화롄" => "hualien",
    "이포" => "ipoh",
    "자카르타" => "jakarta",
    "제주도" => "jeju",
    "조호바루" => "johor",
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
// 2) 랜덤 도시 선택
// ========================
$keys = array_keys($cities);
$random_key = $keys[array_rand($keys)]; 
$city_label = $random_key;
$city_slug  = $cities[$random_key];

echo "선택된 도시: {$city_label} ({$city_slug})<br>";

// ========================
//  3) JSON 데이터 가져오기 (최대 10회 재시도)
// ========================
$json_url = "https://websseu.github.io/data_hotel_korea/CityTop10/2025-09-24/{$city_slug}.json";
$max_attempts = 10;       // 최대 시도 횟수
$attempt      = 0;        // 현재 시도 횟수
$data         = null;     // 결과 저장

while ( $attempt < $max_attempts ) {
    $attempt++;
    echo "JSON 불러오기 시도: {$attempt}회<br>";

    $response = wp_remote_get( $json_url );

    // 네트워크 에러 체크
    if ( is_wp_error( $response ) ) {
        echo "에러 발생: " . $response->get_error_message() . "<br>";
        sleep(1); // 1초 대기 후 재시도 (필요에 따라 조정)
        continue;
    }

    // JSON 파싱
    $data = json_decode( wp_remote_retrieve_body( $response ), true );

    // 데이터가 존재하면 루프 종료
    if ( !empty( $data ) ) {
        echo "JSON 데이터 로드 성공<br>";
        break;
    } else {
        echo "JSON 데이터 비어있음, 재시도 중...<br>";
        sleep(1); // 1초 대기 후 재시도
    }
}

// 최대 시도 후에도 실패하면 종료
if ( empty( $data ) ) {
    die("JSON 데이터를 10회 시도 후에도 불러오지 못했습니다.");
}

// ========================
// 4) 제목 패턴 준비
// ========================
$title_patterns = [
    "{$city_label} 가성비 호텔 TOP10",
    "{$city_label} 최고의 휴양지 베스트 10",
    "{$city_label} 인기 호텔 랭킹 10",
    "{$city_label} 여행 필수 숙소 TOP10",
    "{$city_label} 가족여행 추천 호텔 10선",
    "{$city_label} 로맨틱 감성 호텔 BEST10",
    "{$city_label} 인기 급상승 호텔 TOP10",
    "{$city_label} 여행객이 뽑은 호텔 BEST10",
    "{$city_label} 현지인이 추천하는 숙소 10곳",
    "{$city_label} 최고의 뷰를 자랑하는 호텔 10곳"
];

// 랜덤으로 제목 패턴 선택
$random_title = $title_patterns[array_rand($title_patterns)];

// ========================
// 5) 글 내용 (호텔 리스트)
// ========================
$content = "";
$content .= '
<style>
.hotel-box {
  margin-bottom: 50px;
  border-bottom: 1px solid #ddd;
  padding-bottom: 30px;
}
.hotel-photos {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-template-rows: repeat(2, 300px);
  gap: 8px;
  overflow: hidden;
}
.hotel-photos div {
  background-size: cover;
  background-position: center;
  width: 100%;
  height: 300px;
}
.hotel-info {
  padding: 15px 10px;
  font-family: Arial, sans-serif;
}
.hotel-name {
  font-size: 1.4rem;
  font-weight: bold;
  margin-bottom: 8px;
  color: #222;
  text-align: center;
}
.hotel-meta {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 6px;
  text-align: center;
}
.hotel-price-btn {
  display: inline-block;
  background-color: #0073e6;
  color: #fff;
  text-decoration: none;
  padding: 8px 20px;
  border-radius: 4px;
  font-weight: bold;
  text-align: center;
  margin: 12px auto;
}
.hotel-price-btn:hover {
  background-color: #005bb5;
}
.hotel-detail {
  color: #666;
  line-height: 1.5;
  text-align: justify;
  overflow: hidden;
}
</style>
';

foreach ($data as $hotel) {
    $name      = esc_html($hotel['name'] ?? '');
    $rating    = esc_html($hotel['rating'] ?? '');
    $reviews   = esc_html($hotel['reviews'] ?? '');
    $area      = esc_html($hotel['area'] ?? '');
    $area_clean = preg_replace('/\s*[-–]\s*지도에서 위치 보기$/u', '', $area_raw);
    $area = esc_html($area_clean);
    $detail    = esc_html($hotel['detail'] ?? '');
    $price     = esc_html($hotel['price'] ?? '');
    $hotel_url = esc_url($hotel['hotel_url'] ?? '#');
    $gallery   = $hotel['gallery'] ?? [];

    // 첫 4개의 이미지만 사용
    $photos = array_slice($gallery, 0, 4);

    // 이미지 박스
    $photoHtml = '<div class="hotel-photos">';
    foreach ($photos as $img) {
        $photoHtml .= "<div style=\"background-image:url('{$img}');\"></div>";
    }
    $photoHtml .= '</div>';

    // 정보 박스
    $infoHtml = '
    <div class="hotel-info">
        <div class="hotel-name">' . $name . '</div>
        <div class="hotel-meta">⭐ 평점: ' . $rating . ' | ' . $reviews . '</div>
        <div class="hotel-meta">📍 위치: ' . $area . '</div>
        <div style="text-align:center;">
            <a class="hotel-price-btn" href="' . $hotel_url . '" target="_blank" rel="noopener noreferrer">💰 가격 바로 확인하기</a>
        </div>
        <div class="hotel-detail">' . $detail . '</div>
    </div>';

    // 전체 박스
    $content .= '
    <div class="hotel-box">
        ' . $photoHtml . '
        ' . $infoHtml . '
    </div>';
}

// ========================
// 6) 새 글 등록
// ========================
$new_post = array(
    'post_title'   => $random_title,
    'post_content' => $content,
    'post_status'  => 'publish',
    'post_author'  => 1,
    'post_type'    => 'post'
);

$post_id = wp_insert_post( $new_post );

// ========================
// 7) 카테고리 및 대표 이미지
// ========================
if ( $post_id ) {
    echo "포스트 작성됨: {$post_id}<br>";

    // 예: city 카테고리 ID가 6이라고 가정
    wp_set_post_terms( $post_id, array(54), 'category' );
    echo "카테고리 설정 완료<br>";

    // 첫 번째 호텔의 첫 번째 이미지 → 대표 이미지로 설정
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
