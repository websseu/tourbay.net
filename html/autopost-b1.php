<?php
require_once( 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

// JSON URL
$json_url = "https://websseu.github.io/data_hotel_korea/CityTop10/2025-09-29/hualien.json";

// JSON 데이터 가져오기
$response = wp_remote_get( $json_url );
if ( is_wp_error( $response ) ) {
    die("❌ JSON 데이터를 불러오지 못했습니다: " . $response->get_error_message());
}
$data = json_decode( wp_remote_retrieve_body( $response ), true );
if ( empty( $data ) ) {
    die("❌ JSON 데이터가 비어 있습니다.");
}

// ========================
// 글 내용 만들기
// ========================
$content = "";

foreach ( $data as $index => $hotel ) {
    if ( $index >= 10 ) break; // TOP10만 출력

    $name     = esc_html( $hotel['name'] ?? '' );
    $detail   = esc_html( $hotel['detail'] ?? '' );
    $area     = esc_html( $hotel['area'] ?? '' );
    $rating   = esc_html( $hotel['rating'] ?? '' );
    $reviews  = esc_html( $hotel['reviews'] ?? '' );
    $price    = esc_html( $hotel['price'] ?? '' );
    $hotelUrl = esc_url( $hotel['hotel_url'] ?? '' );

    // 이미지 4장만 출력
    $images_html = "";
    if ( !empty($hotel['gallery']) ) {
        $images_html .= '<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:10px;">';
        for ($i=0; $i<4; $i++) {
            if ( !empty($hotel['gallery'][$i]) ) {
                $img_url = esc_url($hotel['gallery'][$i]);
                $images_html .= "<div><img src='{$img_url}' alt='{$name}' style='width:100%;height:auto;border-radius:6px;'></div>";
            }
        }
        $images_html .= '</div>';
    }

    // 호텔 정보
    $info_html = "
        <h3 style='margin:10px 0; font-size:1.4rem;'>{$index+1}. <a href='{$hotelUrl}' target='_blank' rel='noopener'>{$name}</a></h3>
        <p style='margin:4px 0;'>{$detail}</p>
        <ul style='margin:4px 0 12px 16px; font-size:0.95rem; line-height:1.5;'>
            <li>📍 위치: {$area}</li>
            <li>⭐ 평점: {$rating}</li>
            <li>💬 후기: {$reviews}</li>
            <li>💰 가격(대략): {$price}</li>
        </ul>
        <hr style='margin:24px 0;border:none;border-bottom:1px solid #ddd;'>
    ";

    // 이미지 + 정보 합치기
    $content .= $images_html . $info_html;
}

// ========================
// 새 글 생성
// ========================
$new_post = array(
    'post_title'   => '반둥 가성비 호텔 TOP10',
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
