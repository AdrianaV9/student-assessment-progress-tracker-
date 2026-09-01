from PIL import Image, ImageDraw, ImageFont
from pathlib import Path

OUTPUT = Path("docs/wireframes")
OUTPUT.mkdir(parents=True, exist_ok=True)

W, H = 1400, 900

def get_font(size=22, bold=False):
    candidates = [
        "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf"
        if bold else
        "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf",
        "/usr/share/fonts/truetype/liberation2/LiberationSans-Bold.ttf"
        if bold else
        "/usr/share/fonts/truetype/liberation2/LiberationSans-Regular.ttf",
    ]

    for path in candidates:
        if Path(path).exists():
            return ImageFont.truetype(path, size)

    return ImageFont.load_default()


F_TITLE = get_font(28, True)
F_H2 = get_font(22, True)
F_BODY = get_font(18)
F_SMALL = get_font(15)
F_BTN = get_font(16, True)


def draw_box(draw, xy, label=None, title=False):
    draw.rounded_rectangle(
        xy,
        radius=10,
        fill=(248, 248, 248),
        outline=(70, 70, 70),
        width=2
    )

    if label:
        x1, y1, _, _ = xy
        selected_font = F_H2 if title else F_BODY
        draw.text(
            (x1 + 12, y1 + 10),
            label,
            font=selected_font,
            fill=(35, 35, 35)
        )


def draw_button(draw, x, y, width, height, text):
    draw.rounded_rectangle(
        (x, y, x + width, y + height),
        radius=7,
        fill=(235, 235, 235),
        outline=(60, 60, 60),
        width=2
    )

    bounds = draw.textbbox((0, 0), text, font=F_BTN)
    text_width = bounds[2] - bounds[0]
    text_height = bounds[3] - bounds[1]

    draw.text(
        (
            x + (width - text_width) / 2,
            y + (height - text_height) / 2 - 2
        ),
        text,
        font=F_BTN,
        fill=(30, 30, 30)
    )


def draw_navigation(draw, active):
    draw.rounded_rectangle(
        (40, 110, 255, 850),
        radius=12,
        fill=(244, 244, 244),
        outline=(70, 70, 70),
        width=2
    )

    draw.text((65, 135), "SAMPTS", font=F_H2, fill=(30, 30, 30))

    items = [
        "Dashboard",
        "Students",
        "Assessments",
        "Results",
        "Progress"
    ]

    y = 205

    for item in items:
        fill = (220, 220, 220) if item == active else (248, 248, 248)

        draw.rounded_rectangle(
            (58, y, 238, y + 48),
            radius=8,
            fill=fill,
            outline=(130, 130, 130),
            width=1
        )

        draw.text(
            (75, y + 13),
            item,
            font=F_BODY,
            fill=(30, 30, 30)
        )

        y += 65


def draw_header(draw, title):
    draw.text(
        (40, 35),
        "Student Assessment Management and Progress Tracking System",
        font=F_TITLE,
        fill=(25, 25, 25)
    )

    draw.line((40, 88, 1360, 88), fill=(80, 80, 80), width=2)
    draw.text((295, 115), title, font=F_H2, fill=(25, 25, 25))


def new_screen(title, active):
    image = Image.new("RGB", (W, H), "white")
    draw = ImageDraw.Draw(image)
    draw_header(draw, title)
    draw_navigation(draw, active)
    return image, draw


def draw_table(draw, x, y, widths, rows, headers):
    row_height = 45
    total_width = sum(widths)

    draw.rectangle(
        (x, y, x + total_width, y + row_height),
        fill=(230, 230, 230),
        outline=(80, 80, 80),
        width=2
    )

    current_x = x

    for i, width in enumerate(widths):
        draw.rectangle(
            (current_x, y, current_x + width, y + row_height),
            outline=(80, 80, 80),
            width=1
        )

        draw.text(
            (current_x + 8, y + 12),
            headers[i],
            font=F_SMALL,
            fill=(25, 25, 25)
        )

        current_x += width

    current_y = y + row_height

    for row in rows:
        current_x = x

        for i, width in enumerate(widths):
            draw.rectangle(
                (
                    current_x,
                    current_y,
                    current_x + width,
                    current_y + row_height
                ),
                fill=(250, 250, 250),
                outline=(120, 120, 120),
                width=1
            )

            draw.text(
                (current_x + 8, current_y + 12),
                str(row[i]),
                font=F_SMALL,
                fill=(45, 45, 45)
            )

            current_x += width

        current_y += row_height


def save(image, filename):
    image.save(OUTPUT / filename)


# ---------------------------------------------------------
# 1. Dashboard
# ---------------------------------------------------------

image, draw = new_screen("Dashboard", "Dashboard")

cards = [
    ("Total Students", "48"),
    ("Total Assessments", "12"),
    ("Results Recorded", "326"),
    ("Average Progress", "71%")
]

for index, (label, value) in enumerate(cards):
    x = 295 + index * 255

    draw_box(draw, (x, 165, x + 225, 275))

    draw.text(
        (x + 15, 183),
        label,
        font=F_SMALL,
        fill=(55, 55, 55)
    )

    draw.text(
        (x + 15, 220),
        value,
        font=get_font(32, True),
        fill=(25, 25, 25)
    )


draw_box(draw, (295, 310, 835, 690), "Recent Results", title=True)

draw_table(
    draw,
    315,
    360,
    [170, 170, 150],
    [
        ("S1001", "Web Dev", "78%"),
        ("S1002", "Database", "65%"),
        ("S1003", "Programming", "82%")
    ],
    ["Student", "Assessment", "Result"]
)

draw_box(
    draw,
    (865, 310, 1325, 690),
    "Progress Summary",
    title=True
)

draw.text(
    (895, 365),
    "Students on track",
    font=F_BODY,
    fill=(40, 40, 40)
)

draw.rectangle(
    (895, 410, 1280, 450),
    outline=(80, 80, 80),
    width=2
)

draw.rectangle(
    (895, 410, 1170, 450),
    fill=(225, 225, 225)
)

draw.text(
    (895, 490),
    "Average completion",
    font=F_BODY,
    fill=(40, 40, 40)
)

draw.rectangle(
    (895, 535, 1280, 575),
    outline=(80, 80, 80),
    width=2
)

draw.rectangle(
    (895, 535, 1210, 575),
    fill=(225, 225, 225)
)

save(image, "01_dashboard.png")


# ---------------------------------------------------------
# 2. Students
# ---------------------------------------------------------

image, draw = new_screen("Students", "Students")

draw_button(draw, 1125, 120, 195, 48, "+ Add Student")

draw_box(draw, (295, 185, 1325, 750))

draw.text(
    (320, 205),
    "Search",
    font=F_SMALL,
    fill=(60, 60, 60)
)

draw.rectangle(
    (320, 235, 760, 280),
    outline=(90, 90, 90),
    width=2
)

draw.text(
    (338, 248),
    "Search by name or student ID",
    font=F_SMALL,
    fill=(110, 110, 110)
)

draw_button(draw, 780, 235, 110, 45, "Search")

draw_table(
    draw,
    320,
    330,
    [150, 260, 180, 200, 190],
    [
        ("S1001", "Alex Morgan", "74%", "View | Edit", "Delete"),
        ("S1002", "Jamie Lee", "68%", "View | Edit", "Delete"),
        ("S1003", "Taylor Smith", "82%", "View | Edit", "Delete"),
        ("S1004", "Jordan Patel", "59%", "View | Edit", "Delete")
    ],
    [
        "Student ID",
        "Name",
        "Overall Progress",
        "Actions",
        ""
    ]
)

save(image, "02_students.png")


# ---------------------------------------------------------
# 3. Add Student
# ---------------------------------------------------------

image, draw = new_screen("Add Student", "Students")

draw_box(
    draw,
    (360, 170, 1200, 720),
    "Student Details",
    title=True
)

fields = [
    ("Student ID", "e.g. S1005"),
    ("First Name", ""),
    ("Last Name", ""),
    ("Email", ""),
    ("Course / Programme", "")
]

y = 235

for label, placeholder in fields:
    draw.text(
        (405, y),
        label,
        font=F_BODY,
        fill=(35, 35, 35)
    )

    draw.rectangle(
        (610, y - 4, 1100, y + 40),
        outline=(90, 90, 90),
        width=2
    )

    if placeholder:
        draw.text(
            (625, y + 8),
            placeholder,
            font=F_SMALL,
            fill=(120, 120, 120)
        )

    y += 80

draw_button(draw, 785, 640, 145, 48, "Save Student")
draw_button(draw, 950, 640, 145, 48, "Cancel")

save(image, "03_add_student.png")


# ---------------------------------------------------------
# 4. Edit Student
# ---------------------------------------------------------

image, draw = new_screen("Edit Student", "Students")

draw_box(
    draw,
    (360, 170, 1200, 720),
    "Edit Student Details",
    title=True
)

fields = [
    ("Student ID", "S1001"),
    ("First Name", "Alex"),
    ("Last Name", "Morgan"),
    ("Email", "alex@example.com"),
    ("Course / Programme", "FdSc Computing")
]

y = 235

for label, value in fields:
    draw.text(
        (405, y),
        label,
        font=F_BODY,
        fill=(35, 35, 35)
    )

    draw.rectangle(
        (610, y - 4, 1100, y + 40),
        outline=(90, 90, 90),
        width=2
    )

    draw.text(
        (625, y + 8),
        value,
        font=F_SMALL,
        fill=(70, 70, 70)
    )

    y += 80

draw_button(draw, 745, 640, 175, 48, "Save Changes")
draw_button(draw, 940, 640, 145, 48, "Cancel")

save(image, "04_edit_student.png")


# ---------------------------------------------------------
# 5. Assessments
# ---------------------------------------------------------

image, draw = new_screen("Assessments", "Assessments")

draw_button(draw, 1100, 120, 220, 48, "+ Create Assessment")

draw_box(draw, (295, 185, 1325, 750))

draw_table(
    draw,
    320,
    245,
    [270, 210, 170, 170, 200],
    [
        (
            "Web Development Project",
            "PFD200",
            "15/10/2026",
            "100",
            "Edit | Delete"
        ),
        (
            "Database Assignment",
            "DBS201",
            "22/10/2026",
            "100",
            "Edit | Delete"
        ),
        (
            "Programming Test",
            "PRG210",
            "25/10/2026",
            "50",
            "Edit | Delete"
        )
    ],
    [
        "Assessment",
        "Module",
        "Due Date",
        "Max Mark",
        "Actions"
    ]
)

save(image, "05_assessments.png")


# ---------------------------------------------------------
# 6. Assessment Form
# ---------------------------------------------------------

image, draw = new_screen(
    "Create / Edit Assessment",
    "Assessments"
)

draw_box(
    draw,
    (360, 170, 1200, 700),
    "Assessment Details",
    title=True
)

fields = [
    ("Assessment Title", ""),
    ("Module", ""),
    ("Due Date", "DD/MM/YYYY"),
    ("Maximum Mark", "100")
]

y = 245

for label, placeholder in fields:
    draw.text(
        (405, y),
        label,
        font=F_BODY,
        fill=(35, 35, 35)
    )

    draw.rectangle(
        (610, y - 4, 1100, y + 40),
        outline=(90, 90, 90),
        width=2
    )

    if placeholder:
        draw.text(
            (625, y + 8),
            placeholder,
            font=F_SMALL,
            fill=(110, 110, 110)
        )

    y += 90

draw_button(draw, 770, 605, 170, 48, "Save Assessment")
draw_button(draw, 960, 605, 145, 48, "Cancel")

save(image, "06_assessment_form.png")


# ---------------------------------------------------------
# 7. Student Results
# ---------------------------------------------------------

image, draw = new_screen("Student Results", "Results")

draw_box(draw, (295, 165, 1325, 745))

draw.text(
    (320, 190),
    "Student",
    font=F_SMALL,
    fill=(60, 60, 60)
)

draw.rectangle(
    (320, 220, 650, 265),
    outline=(90, 90, 90),
    width=2
)

draw.text(
    (338, 233),
    "S1001 - Alex Morgan",
    font=F_SMALL,
    fill=(55, 55, 55)
)

draw_button(draw, 1130, 215, 165, 48, "+ Record Result")

draw_table(
    draw,
    320,
    320,
    [250, 150, 150, 150, 250],
    [
        (
            "Web Development",
            "78 / 100",
            "78%",
            "Completed",
            "Good structure"
        ),
        (
            "Database",
            "65 / 100",
            "65%",
            "Completed",
            "Improve joins"
        ),
        (
            "Programming",
            "41 / 50",
            "82%",
            "Completed",
            "Strong logic"
        )
    ],
    [
        "Assessment",
        "Mark",
        "Percentage",
        "Status",
        "Feedback"
    ]
)

save(image, "07_student_results.png")


# ---------------------------------------------------------
# 8. Record Result
# ---------------------------------------------------------

image, draw = new_screen("Record Assessment Result", "Results")

draw_box(
    draw,
    (360, 170, 1200, 720),
    "Result Details",
    title=True
)

fields = [
    ("Student", "S1001 - Alex Morgan"),
    ("Assessment", "Web Development Project"),
    ("Mark Achieved", "78"),
    ("Maximum Mark", "100"),
    ("Calculated Percentage", "78%")
]

y = 235

for label, value in fields:
    draw.text(
        (405, y),
        label,
        font=F_BODY,
        fill=(35, 35, 35)
    )

    draw.rectangle(
        (650, y - 4, 1100, y + 40),
        outline=(90, 90, 90),
        width=2
    )

    draw.text(
        (665, y + 8),
        value,
        font=F_SMALL,
        fill=(70, 70, 70)
    )

    y += 70

draw.text(
    (405, 585),
    "Feedback",
    font=F_BODY,
    fill=(35, 35, 35)
)

draw.rectangle(
    (650, 565, 1100, 635),
    outline=(90, 90, 90),
    width=2
)

draw_button(draw, 785, 655, 145, 48, "Save Result")
draw_button(draw, 950, 655, 145, 48, "Cancel")

save(image, "08_record_result.png")


# ---------------------------------------------------------
# 9. Progress Overview
# ---------------------------------------------------------

image, draw = new_screen("Progress Overview", "Progress")

draw_box(draw, (295, 165, 1325, 745))

draw.text(
    (320, 190),
    "Search / Select Student",
    font=F_SMALL,
    fill=(60, 60, 60)
)

draw.rectangle(
    (320, 220, 690, 265),
    outline=(90, 90, 90),
    width=2
)

draw.text(
    (338, 233),
    "S1001 - Alex Morgan",
    font=F_SMALL,
    fill=(55, 55, 55)
)

draw_box(
    draw,
    (320, 305, 780, 670),
    "Overall Progress",
    title=True
)

draw.text(
    (365, 395),
    "74%",
    font=get_font(54, True),
    fill=(30, 30, 30)
)

draw.rectangle(
    (365, 485, 730, 535),
    outline=(80, 80, 80),
    width=2
)

draw.rectangle(
    (365, 485, 635, 535),
    fill=(225, 225, 225)
)

draw_box(
    draw,
    (815, 305, 1290, 670),
    "Assessment Progress",
    title=True
)

draw_table(
    draw,
    845,
    370,
    [220, 130],
    [
        ("Web Development", "78%"),
        ("Database", "65%"),
        ("Programming", "82%")
    ],
    ["Assessment", "Result"]
)

save(image, "09_progress.png")

print("Nine wireframes created in docs/wireframes/")
