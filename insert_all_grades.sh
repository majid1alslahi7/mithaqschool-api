#!/bin/bash

TOKEN="63|KcKlGvJuoMb2HHwCFst93oIglYfDVooyHQW5s5vq5bdcfbe9"
URL="http://127.0.0.1:8000/api/v1/monthly-grades"

# الأيام
days=(1 2 3)

# المواد (course_id, اسم المادة)
courses=(
    "1:الرياضيات"
    "2:اللغة العربية"
    "3:اللغة الإنجليزية"
    "4:العلوم"
    "5:الدراسات الاجتماعية"
    "6:التربية الإسلامية"
    "7:التربية الفنية"
)

# درجات لكل مادة (شهر1, شهر2, شهر3)
# التنسيق: written_exam,homework,oral_exam,attendance لكل شهر
declare -A grades
grades[1,1]="35,18,15,20"
grades[1,2]="32,17,16,19"
grades[1,3]="38,19,18,20"

grades[2,1]="30,15,14,18"
grades[2,2]="33,16,15,19"
grades[2,3]="36,17,16,20"

grades[3,1]="28,14,13,17"
grades[3,2]="31,15,14,18"
grades[3,3]="34,16,15,19"

grades[4,1]="32,16,14,18"
grades[4,2]="35,17,15,19"
grades[4,3]="37,18,16,20"

grades[5,1]="25,12,11,15"
grades[5,2]="28,13,12,16"
grades[5,3]="30,14,13,17"

grades[6,1]="40,20,18,20"
grades[6,2]="38,19,17,19"
grades[6,3]="39,20,18,20"

grades[7,1]="33,16,15,19"
grades[7,2]="34,17,16,20"
grades[7,3]="36,18,17,20"

echo "بدء إدخال الدرجات..."
echo "================================"

for course in "${courses[@]}"; do
    course_id=$(echo $course | cut -d: -f1)
    course_name=$(echo $course | cut -d: -f2)
    
    for month in "${days[@]}"; do
        grade_data="${grades[$course_id,$month]}"
        written=$(echo $grade_data | cut -d, -f1)
        homework=$(echo $grade_data | cut -d, -f2)
        oral=$(echo $grade_data | cut -d, -f3)
        attendance=$(echo $grade_data | cut -d, -f4)
        
        echo "إدخال: $course_name - شهر $month"
        
        curl -s -X POST "$URL" \
            -H "Authorization: Bearer $TOKEN" \
            -H "Content-Type: application/json" \
            -d "{
                \"student_number\": \"20260041\",
                \"course_id\": $course_id,
                \"academic_year_id\": 1,
                \"semester_id\": 1,
                \"month\": $month,
                \"written_exam\": $written,
                \"homework\": $homework,
                \"oral_exam\": $oral,
                \"attendance\": $attendance
            }"
        
        echo ""
        sleep 0.2
    done
    echo "---"
done

echo "================================"
echo "✅ تم إدخال جميع الدرجات"
