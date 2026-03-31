#!/bin/bash

TOKEN="63|KcKlGvJuoMb2HHwCFst93oIglYfDVooyHQW5s5vq5bdcfbe9"
URL="http://127.0.0.1:8000/api/v1/monthly-grades"

echo "إدخال درجات الطالب 20260041"
echo "================================"

total=0
ok=0

# 7 مواد × 3 أشهر = 21 درجة
for course in 1 2 3 4 5 6 7; do
    for month in 1 2 3; do
        total=$((total + 1))
        
        # درجات حسب المادة والشهر
        if [ $course -eq 1 ]; then
            [ $month -eq 1 ] && w=35 h=18 o=15 a=20
            [ $month -eq 2 ] && w=32 h=17 o=16 a=19
            [ $month -eq 3 ] && w=38 h=19 o=18 a=20
        elif [ $course -eq 2 ]; then
            [ $month -eq 1 ] && w=30 h=15 o=14 a=18
            [ $month -eq 2 ] && w=33 h=16 o=15 a=19
            [ $month -eq 3 ] && w=36 h=17 o=16 a=20
        elif [ $course -eq 3 ]; then
            [ $month -eq 1 ] && w=28 h=14 o=13 a=17
            [ $month -eq 2 ] && w=31 h=15 o=14 a=18
            [ $month -eq 3 ] && w=34 h=16 o=15 a=19
        elif [ $course -eq 4 ]; then
            [ $month -eq 1 ] && w=32 h=16 o=14 a=18
            [ $month -eq 2 ] && w=35 h=17 o=15 a=19
            [ $month -eq 3 ] && w=37 h=18 o=16 a=20
        elif [ $course -eq 5 ]; then
            [ $month -eq 1 ] && w=25 h=12 o=11 a=15
            [ $month -eq 2 ] && w=28 h=13 o=12 a=16
            [ $month -eq 3 ] && w=30 h=14 o=13 a=17
        elif [ $course -eq 6 ]; then
            [ $month -eq 1 ] && w=40 h=20 o=18 a=20
            [ $month -eq 2 ] && w=38 h=19 o=17 a=19
            [ $month -eq 3 ] && w=39 h=20 o=18 a=20
        else
            [ $month -eq 1 ] && w=33 h=16 o=15 a=19
            [ $month -eq 2 ] && w=34 h=17 o=16 a=20
            [ $month -eq 3 ] && w=36 h=18 o=17 a=20
        fi
        
        echo -n "[$course/$month] "
        
        curl -s -X POST "$URL" \
            -H "Authorization: Bearer $TOKEN" \
            -H "Content-Type: application/json" \
            -d "{\"student_number\":\"20260041\",\"course_id\":$course,\"academic_year_id\":1,\"semester_id\":1,\"month\":$month,\"written_exam\":$w,\"homework\":$h,\"oral_exam\":$o,\"attendance\":$a}" > /dev/null
        
        if [ $? -eq 0 ]; then
            echo "✓"
            ok=$((ok + 1))
        else
            echo "✗"
        fi
        
        sleep 0.05
    done
done

echo "================================"
echo "$ok / $total تم إدخالها بنجاح"
