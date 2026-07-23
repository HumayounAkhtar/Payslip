import os
import math
from PIL import Image, ImageDraw

statusbar_dir = r"c:\xampp\htdocs\Payslip\public\images\status-bar"
os.makedirs(statusbar_dir, exist_ok=True)

def save_scaled_image(img_large, filename, scale=10):
    w, h = img_large.size
    img_small = img_large.resize((w // scale, h // scale), Image.Resampling.LANCZOS)
    dest_path = os.path.join(statusbar_dir, filename)
    img_small.save(dest_path, "PNG")
    print(f"Generated {filename} ({img_small.size[0]}x{img_small.size[1]})")

# ─────────────────────────────────────────────────────────────────
# 1. SIGNAL BARS — compact, tight gaps, matches reference exactly
#    Reference: 4 thin bars, compact spacing, ≈28x18 px
# ─────────────────────────────────────────────────────────────────
def generate_signal_icon(active_bars, filename):
    # 10x canvas: 280x180
    img = Image.new("RGBA", (280, 180), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)

    # Bars: width=36, gap=10, 4 bars — tight and compact
    bar_w  = 36
    gap    = 10
    bottom = 175
    heights = [60, 100, 140, 175]  # step heights (lowest to tallest)

    for i, h in enumerate(heights):
        x0 = i * (bar_w + gap) + 5
        x1 = x0 + bar_w
        y0 = bottom - h
        y1 = bottom
        color = (0, 0, 0, 255) if i < active_bars else (0, 0, 0, 55)
        draw.rounded_rectangle([x0, y0, x1, y1], radius=8, fill=color)

    save_scaled_image(img, filename)

for bars in range(1, 5):
    generate_signal_icon(bars, f"signal-{bars}-bars.png")
generate_signal_icon(4, "signal_original.png")

# ─────────────────────────────────────────────────────────────────
# 2. WI-FI ICON — compact, matches reference size ≈18x15 px
# ─────────────────────────────────────────────────────────────────
def generate_wifi_icon():
    # 10x canvas: 180x150
    img = Image.new("RGBA", (180, 150), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)

    cx, cy = 90, 130

    # Bottom dot — small
    dot_r = 12
    draw.ellipse([cx - dot_r, cy - dot_r, cx + dot_r, cy + dot_r], fill=(0, 0, 0, 255))

    # 3 arcs — thin strokes
    radii     = [38, 70, 102]
    arc_w     = 16
    a_start, a_end = 225, 315

    for r in radii:
        bbox = [cx - r, cy - r, cx + r, cy + r]
        draw.arc(bbox, start=a_start, end=a_end, fill=(0, 0, 0, 255), width=arc_w)
        cap_r = arc_w // 2 - 1
        for angle in [a_start, a_end]:
            rad = math.radians(angle)
            ex  = cx + r * math.cos(rad)
            ey  = cy + r * math.sin(rad)
            draw.ellipse([ex - cap_r, ey - cap_r, ex + cap_r, ey + cap_r], fill=(0, 0, 0, 255))

    save_scaled_image(img, "wifi_original.png")

generate_wifi_icon()

# ─────────────────────────────────────────────────────────────────
# 3. BATTERY ICON — compact short rectangle, matches reference
#    Reference: short/narrow battery ≈25x14 px (NOT wide 48px)
# ─────────────────────────────────────────────────────────────────
def generate_battery_icon(level, filename):
    # 10x canvas: 280x150  (narrower than before)
    img = Image.new("RGBA", (280, 150), (255, 255, 255, 0))
    draw = ImageDraw.Draw(img)

    # Thin outer body — compact width
    body_l, body_t, body_r, body_b = 8, 20, 240, 130
    draw.rounded_rectangle([body_l, body_t, body_r, body_b], radius=20, outline=(0, 0, 0, 255), width=14)

    # Small nub on right
    nub_l, nub_t, nub_r, nub_b = 245, 52, 265, 98
    draw.rounded_rectangle([nub_l, nub_t, nub_r, nub_b], radius=8, fill=(0, 0, 0, 255))

    # Inner fill
    inner_l = body_l + 16
    inner_t = body_t + 14
    inner_b = body_b - 14

    if level == "full":
        fill_r = body_r - 16
        fill_color = (0, 0, 0, 255)
    elif level == "medium":
        fill_r = body_l + int((body_r - body_l) * 0.55)
        fill_color = (0, 0, 0, 255)
    elif level == "low":
        fill_r = body_l + int((body_r - body_l) * 0.2)
        fill_color = (239, 68, 68, 255)
    else:
        fill_r = inner_l + 8
        fill_color = (0, 0, 0, 255)

    if fill_r > inner_l:
        draw.rounded_rectangle([inner_l, inner_t, fill_r, inner_b], radius=8, fill=fill_color)

    save_scaled_image(img, filename)

for level in ["full", "medium", "low"]:
    generate_battery_icon(level, f"battery-{level}.png")

print("All icons generated successfully!")
