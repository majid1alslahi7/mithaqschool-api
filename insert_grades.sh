#!/bin/bash

TOKEN="66|l5VDrynHVJ8QAp7aAd0c04TYvTPPQN1ImRx4cauU1779f61e"
URL="http://127.0.0.1:8000/api/v1/monthly-grades"

echo "================================"
echo "إدخال درجات الطالب 20260041"
echo "================================"

total=0
ok=0

for course in 1 2 3 4 5 6 7; do
    for month in 1 2 3; do
        total=$((total + 1))
        
        case $course in
            1)
                case $month in
                    1) w=35; h=18; o=15; a=20 ;;
                    2) w=32; h=17; o=16; a=19 ;;
                    3) w=38; h=19; o=18; a=20 ;;
                esac ;;
            2)
                case $month in
                    1) w=30; h=15; o=14; a=18 ;;
                    2) w=33; h=16; o=15; a=19 ;;
                    3) w=36; h=17; o=16; a=20 ;;
                esac ;;
            3)
                case $month in
                    1) w=28; h=14; o=13; a=17 ;;
                    2) w=31; h=15; o=14; a=18 ;;
                    3) w=34; h=16; o=15; a=19 ;;
                esac ;;
            4)
                case $month in
                    1) w=32; h=16; o=14; a=18 ;;
                    2) w=35; h=17; o=15; a=19 ;;
                    3) w=37; h=18; o=16; a=20 ;;
                esac ;;
            5)
                case $month in
                    1) w=25; h=12; o=11; a=15 ;;
                    2) w=28; h=13; o=12; a=16 ;;
                    3) w=30; h=14; o=13; a=17 ;;
                esac ;;
            6)
                case $month in
                    1) w=40; h=20; o=18; a=20 ;;
                    2) w=38; h=19; o=17; a=19 ;;
                    3) w=39; h=20; o=18; a=20 ;;
                esac ;;
            7)
                case $month in
                    1) w=33; h=16; o=15; a=19 ;;
                    2) w=34; h=17; o=16; a=20 ;;
                    3) w=36; h=18; o=17; a=20 ;;
                esac ;;
        esac
        
        echo -n "[$course/$month] "
        
        response=$(curl -s -X POST "$URL" \
            -H "Authorization: Bearer $TOKEN" \
            -H "Content-Type: application/json" \
            -d "{\"student_number\":\"20260041\",\"course_id\":$course,\"academic_year_id\":1,\"semester_id\":1,\"month\":$month,\"written_exam\":$w,\"homework\":$h,\"oral_exam\":$o,\"attendance\":$a}")
        
        if echo "$response" | grep -q '"id"'; then
            echo "✓"
            ok=$((ok + 1))
        else
            echo "✗"
            echo "  خطأ: $response"
        fi
        
        sleep 0.1
    done
done

echo "================================"
echo "$ok / $total تم إدخالها بنجاح"
