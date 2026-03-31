#!/bin/bash

TOKEN="63|KcKlGvJuoMb2HHwCFst93oIglYfDVooyHQW5s5vq5bdcfbe9"
URL="http://127.0.0.1:8000/api/v1/monthly-grades"

echo "اختبار إضافة درجة واحدة..."

response=$(curl -s -X POST "$URL" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d '{
        "student_number": "20260041",
        "course_id": 1,
        "academic_year_id": 1,
        "semester_id": 1,
        "month": 1,
        "written_exam": 35,
        "homework": 18,
        "oral_exam": 15,
        "attendance": 20
    }')

echo "الرد: $response"

if [[ $response == *"\"id\""* ]]; then
    echo "✅ نجاح"
else
    echo "❌ فشل"
fi
