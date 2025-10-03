from bs4 import BeautifulSoup
import json, os, re
from datetime import datetime

# ===== 1) 날짜 기반 경로 설정 =====
year = datetime.now().strftime("%Y")
month = datetime.now().strftime("%m")

SCRIPT_DIR = os.path.dirname(os.path.abspath(__file__)) 

base_folder = os.path.join(SCRIPT_DIR, year, month)
os.makedirs(base_folder, exist_ok=True)

# ===== 2) HTML 파일 목록 =====
html_files = [f for f in os.listdir(base_folder) if f.endswith(".html")]

if not html_files:
    print(f"❌ HTML 파일이 없습니다 → {base_folder}")
    exit()

print(f"✅ {year}/{month} 폴더에서 {len(html_files)}개의 파일 처리 시작...")

# ===== 3) 파일 반복 =====
for html_file in html_files:
    html_path = os.path.join(base_folder, html_file)

    with open(html_path, "r", encoding="utf-8") as f:
        html = f.read()

    soup = BeautifulSoup(html, "lxml")

    # ==============================
    # 🔹 1 액티비티
    # ==============================
    activities = []
    for card in soup.select("#s-DestinationExploreTtdActs .responsive-card-item"):
        # 이미지
        img_tag = card.select_one(".card-img img")
        image = ""
        if img_tag:
            image = img_tag.get("data-src") or ""

        # 카테고리 / 지역
        type_tag = card.select_one(".card-info-top .card-subText")
        if type_tag:
            # 공백/줄바꿈 제거 → 한 칸으로
            type_text = re.sub(r"\s+", " ", type_tag.get_text(strip=True))
        else:
            type_text = ""

        # 제목 / 링크
        title_tag = card.select_one(".card-info-top .card-title a")
        title = re.sub(r"\s+", " ", title_tag.get_text(strip=True)) if title_tag else ""
        link = title_tag.get("href") if title_tag else ""

        # 평점
        score_tag = card.select_one(".review-star")
        score = score_tag.get_text(strip=True).replace("★", "").strip() if score_tag else ""

        # 후기 수
        review_tag = card.select_one(".review-booked")
        reviews = ""
        if review_tag:
            m = re.search(r"\(([\d,]+)\)", review_tag.get_text())
            if m:
                reviews = m.group(1).replace(",", "")

        # 가격
        price_tag = card.select_one(".sell-price .price-number")
        price = price_tag.get_text(strip=True) if price_tag else ""

        activities.append({
            "title": title,
            "link": link,
            "image": image,
            "type": type_text,
            "score": score,
            "reviews": reviews,
            "price": price
        })

    # ==============================
    # 🔹 2 가볼만한곳
    # ==============================
    attractions = []
    for card in soup.select("#s-DestinationTopPois .responsive-card-item .poi-card"):
        # 이미지
        img_tag = card.select_one(".poi-card-top .poi-card-img")
        image = ""
        if img_tag:
            image = img_tag.get("data-src") or ""
        
        # 타입
        type_tag = card.select_one(".poi-card-type")
        type = type_tag.get_text(strip=True) if type_tag else ""

        # 제목/링크
        title_tag = card.select_one(".poi-card-info .poi-card-title a")
        title = re.sub(r"\s+", " ", title_tag.get_text(strip=True)) if title_tag else ""
        link = title_tag.get("href") if title_tag else ""

        # 평점
        score_tag = card.select_one(".poi-card-score .poi-score")
        score = score_tag.get_text(strip=True) if score_tag else ""

        # 설명
        desc_tag = card.select_one(".poi-card-desc")
        desc = re.sub(r"\s+", " ", desc_tag.get_text(separator=" ", strip=True)) if desc_tag else ""

        attractions.append({
            "title": title,
            "link": link,
            "image": image, 
            "type": type, 
            "score": score,
            "description": desc
        })

    # ==============================
    # 🔹 3 숙소
    # ==============================
    hotels = []
    for card in soup.select("#s-DestinationHotelActs .responsive-card-item"):
        img_tag = card.select_one(".card-img img")
        image = img_tag.get("data-src") if img_tag else ""

        title_tag = card.select_one("h3.card-title a")
        title = re.sub(r"\s+", " ", title_tag.get_text(strip=True)) if title_tag else ""
        link = title_tag.get("href") if title_tag else ""

        score_tag = card.select_one(".review-star")
        score = score_tag.get_text(strip=True).replace("★", "").strip() if score_tag else ""

        review_tag = card.select_one(".review-booked")
        reviews = ""
        if review_tag:
            match = re.search(r"\(([\d,]+)\)", review_tag.get_text())
            if match:
                reviews = match.group(1).replace(",", "")

        price_tag = card.select_one(".sell-price .price-number")
        price = price_tag.get_text(strip=True) if price_tag else "" 

        hotels.append({
            "title": title,
            "link": link,
            "image": image,  
            "score": score,
            "reviews": reviews,
            "price": price
        })

    # ==============================
    # 🔹 4 인기 순위 
    # ==============================
    popular = []

    wrapper = soup.select_one("#s-Recommended .internal-linking-wrapper-desktop")
    if wrapper:
        first_section = wrapper.select_one("div.internal-linking-section")
        if first_section:
            for li in first_section.select("ul.link-list-wrapper li"):
                rank_tag = li.select_one(".link-index")
                title_tag = li.select_one("a")

                rank = rank_tag.get_text(strip=True) if rank_tag else ""
                title = re.sub(r"\s+", " ", title_tag.get_text(strip=True)) if title_tag else ""
                link = title_tag.get("href") if title_tag else ""

                popular.append({
                    "rank": rank,
                    "title": title,
                    "link": link
                })

    # ==============================
    # 🔹 JSON 저장
    # ==============================
    json_name = f"{os.path.splitext(html_file)[0]}.json"
    output_path = os.path.join(base_folder, json_name)

    data = {
        "액티비티": activities,
        "가볼만한곳": attractions,
        "숙소": hotels,
        "인기순위": popular
    }

    with open(output_path, "w", encoding="utf-8") as f:
        json.dump(data, f, ensure_ascii=False, indent=2)

    print(f"✅ 변환 완료 → {json_name}")

print(f"\n🎉 전체 {len(html_files)}개 파일 처리 완료 → {base_folder}")


# 2025년 10월
# 도쿄 https://www.klook.com/ko/destination/c28-tokyo/
# 쿄토 https://www.klook.com/ko/destination/c30-kyoto/
# 오사카 https://www.klook.com/ko/destination/c29-osaka/
# 홍콩 https://www.klook.com/ko/destination/c2-hong-kong/
# 마카오 https://www.klook.com/ko/destination/c3-macau/
# 타이베이 https://www.klook.com/ko/destination/c19-taipei/
# 서울 https://www.klook.com/ko/destination/c13-seoul/
# 경기도 https://www.klook.com/ko/destination/c157-gyeonggi-do/
# 강원도 https://www.klook.com/ko/destination/c156-gangwon-do/
# 부산 https://www.klook.com/ko/destination/c46-busan/
# 인천 https://www.klook.com/ko/destination/c158-incheon/
# 베이징 https://www.klook.com/ko/destination/c57-beijing/
# 상하이 https://www.klook.com/ko/destination/c59-shanghai/
# 싱가포르 https://www.klook.com/ko/destination/c6-singapore/
# 호치민 https://www.klook.com/ko/destination/c33-ho-chi-minh-city/
# 하노이 https://www.klook.com/ko/destination/c34-hanoi/
# 방콕 https://www.klook.com/ko/destination/c4-bangkok/
# 파타야 https://www.klook.com/ko/destination/c17-pattaya/
# 쿠알라품푸르 https://www.klook.com/ko/destination/c49-kuala-lumpur/
# 로스엔젤레스 https://www.klook.com/ko/destination/c124-los-angeles/
# 뉴옥 https://www.klook.com/ko/destination/c93-new-york/
# 샌프란시스코 https://www.klook.com/ko/destination/c129-san-francisco/
# 라스베가스 https://www.klook.com/ko/destination/c136-las-vegas/
# 파리 https://www.klook.com/ko/destination/c107-paris/
# 런던 https://www.klook.com/ko/destination/c106-london/
# 바르셀로나 https://www.klook.com/ko/destination/c108-barcelona/
# 로마 https://www.klook.com/ko/destination/c92-rome/
# 베를린 https://www.klook.com/ko/destination/c103-berlin/
# 암스테르담 https://www.klook.com/ko/destination/c90-amsterdam/
# 두바이 https://www.klook.com/ko/destination/c78-dubai/
# 아부다비 https://www.klook.com/ko/destination/c131-abu-dhabi/
# 시드니 https://www.klook.com/ko/destination/c68-sydney/
# 멜버른 https://www.klook.com/ko/destination/c69-melbourne/